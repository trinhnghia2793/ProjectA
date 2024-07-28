<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Services\Interfaces\UserCatalogueServiceInterface as UserCatalogueService;

// tạo lại, chưa đổi vì nó sẽ báo lỗi
use App\Http\Requests\StoreUserCatalogueRequest;

class UserCatalogueController extends Controller
{
    protected $userCatalogueService;
    protected $userCatalogueRepository;

    // Constructor
    public function __construct(
        UserCatalogueService $userCatalogueService,
    ){
        $this->userCatalogueService = $userCatalogueService;
    }

    // Index
    public function index(Request $request) {

        $userCatalogues = $this->userCatalogueService->paginate($request);

        $config = [
            'js' => [
                'backend/js/plugins/switchery/switchery.js',
                'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js'
            ],
            'css' => [
                'backend/css/plugins/switchery/switchery.css',
                'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css',
            ]
        ];
        $config['seo'] = config('apps.usercatalogue');
        $template = 'backend.user.catalogue.index'; // tên của view
        return view('backend.dashboard.layout', compact(
            'template',
            'config',
            'userCatalogues',
        ));

    }

    // Create user
    public function create() {

        $config['seo'] = config('apps.usercatalogue');
        $config['method'] = 'create';
        $template = 'backend.user.catalogue.store';
        return view('backend.dashboard.layout', compact(
            'template',
            'config',
        ));

    }

    // Store data
    public function store(StoreUserCatalogueRequest $request) {
        if($this->userCatalogueService->create($request)) {
            toastr()->success("Thêm mới bản ghi thành công.");
            return redirect()->route('user.catalogue.index');
        }
        toastr()->error("Thêm mới bản ghi không thành công.");
        return redirect()->route('user.catalogue.index');
    }

    // Edit
    public function edit($id) {

        $user = $this->userCatalogueRepository->findById($id);
        $provinces = $this->provinceRepository->all();
        $config = [
            'css' => [
                'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css',
            ],
            'js' => [
                'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js',
                'backend/library/location.js',
            ],
        ];
        $config['seo'] = config('apps.usercatalogue');
        $config['method'] = 'edit';
        $template = 'backend.user.catalogue.store';
        return view('backend.dashboard.layout', compact(
            'template',
            'config',
            'provinces',
            'user',
        ));

    }

    // Update
    public function update($id, UpdateUserRequest $request) {
        if($this->userCatalogueService->update($id, $request)) {
            toastr()->success("Cập nhật bản ghi thành công.");
            return redirect()->route('user.catalogue.catalogue.index');
        }
        toastr()->error("Cập nhật bản ghi không thành công.");
        return redirect()->route('user.catalogue.index');
    }

    // Delete
    public function delete($id) {

        $config['seo'] = config('apps.usercatalogue');
        $user = $this->userCatalogueRepository->findById($id);
        $template = 'backend.user.catalogue.delete';
        return view('backend.dashboard.layout', compact(
            'template',
            'config',
            'user',
        ));

    }

    // Destroy
    public function destroy($id) {
        if($this->userCatalogueService->destroy($id)) {
            toastr()->success("Xóa bản ghi thành công.");
            return redirect()->route('user.catalogue.index');
        }
        toastr()->error("Xóa bản ghi không thành công.")    ;
        return redirect()->route('user.catalogue.index');
    }
}
