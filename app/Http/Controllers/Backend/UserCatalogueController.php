<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Services\Interfaces\UserCatalogueServiceInterface as UserCatalogueService;
use App\Repositories\Interfaces\UserCatalogueRepositoryInterface as UserCatalogueRepository;
use App\Repositories\Interfaces\PermissionRepositoryInterface as PermissionRepository;

use App\Http\Requests\StoreUserCatalogueRequest;

class UserCatalogueController extends Controller
{
    protected $userCatalogueService;
    protected $userCatalogueRepository;
    protected $permissionRepository;

    // Constructor
    public function __construct(
        UserCatalogueService $userCatalogueService,
        UserCatalogueRepository $userCatalogueRepository,
        PermissionRepository $permissionRepository,
    ){
        $this->userCatalogueService = $userCatalogueService;
        $this->userCatalogueRepository = $userCatalogueRepository;
        $this->permissionRepository = $permissionRepository;
    }

    // Index
    public function index(Request $request) {
        $this->authorize('modules', 'user.catalogue.index');

        $userCatalogues = $this->userCatalogueService->paginate($request);

        $config = [
            'js' => [
                'backend/js/plugins/switchery/switchery.js',
                'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js'
            ],
            'css' => [
                'backend/css/plugins/switchery/switchery.css',
                'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css',
            ],
            'model' => 'UserCatalogue',
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
        $this->authorize('modules', 'user.catalogue.create');

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
        $this->authorize('modules', 'user.catalogue.update');

        $userCatalogue = $this->userCatalogueRepository->findById($id);
        $config['seo'] = config('apps.usercatalogue');
        $config['method'] = 'edit';
        $template = 'backend.user.catalogue.store';
        return view('backend.dashboard.layout', compact(
            'template',
            'config',
            'userCatalogue',
        ));

    }

    // Update
    public function update($id, StoreUserCatalogueRequest $request) {
        if($this->userCatalogueService->update($id, $request)) {
            toastr()->success("Cập nhật bản ghi thành công.");
            return redirect()->route('user.catalogue.index');
        }
        toastr()->error("Cập nhật bản ghi không thành công.");
        return redirect()->route('user.catalogue.index');
    }

    // Delete
    public function delete($id) {
        $this->authorize('modules', 'user.catalogue.destroy');

        $config['seo'] = config('apps.usercatalogue');
        $userCatalogue = $this->userCatalogueRepository->findById($id);
        $template = 'backend.user.catalogue.delete';
        return view('backend.dashboard.layout', compact(
            'template',
            'config',
            'userCatalogue',
        ));

    }

    // Destroy
    public function destroy($id) {
        if($this->userCatalogueService->destroy($id)) {
            toastr()->success("Xóa bản ghi thành công.");
            return redirect()->route('user.catalogue.index');
        }
        toastr()->error("Xóa bản ghi không thành công.");
        return redirect()->route('user.catalogue.index');
    }

    // Phân quyền: lấy ra danh sách nhóm thành viên
    public function permission() {
        $this->authorize('modules', 'user.catalogue.permission');

        $userCatalogues = $this->userCatalogueRepository->all(['permissions']);
        $permissions = $this->permissionRepository->all();
        $config['seo'] = __('messages.userCatalogue');
        $template = 'backend.user.catalogue.permission';

        return view('backend.dashboard.layout', compact(
            'template',
            'userCatalogues',
            'permissions',
            'config',
        ));
    }

    // Cập nhật quyền cho các nhóm người dùng
    public function updatePermission(Request $request) {
        if($this->userCatalogueService->setPermission($request)) {
            toastr()->success("Cập nhật quyền thành công.");
            return redirect()->route('user.catalogue.index');
        }
        toastr()->error("Có vấn đề xảy ra.");
        return redirect()->route('user.catalogue.index');
    }
}
