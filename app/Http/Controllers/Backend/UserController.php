<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Services\Interfaces\UserServiceInterface as UserService;
use App\Repositories\Interfaces\ProvinceRepositoryInterface as ProvinceRepository;
use App\Repositories\Interfaces\UserRepositoryInterface as UserRepository;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;

class UserController extends Controller
{
    protected $userService;
    protected $provinceRepository;
    protected $userRepository;

    // Constructor
    public function __construct(
        UserService $userService,
        ProvinceRepository $provinceRepository,
        UserRepository $userRepository,
    ){
        $this->userService = $userService;
        $this->provinceRepository = $provinceRepository;
        $this->userRepository = $userRepository;
    }

    // Index
    public function index(Request $request) {
        $this->authorize('modules', 'user.index');

        $users = $this->userService->paginate($request);

        $config = [
            'js' => [
                'backend/js/plugins/switchery/switchery.js',
                'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js'
            ],
            'css' => [
                'backend/css/plugins/switchery/switchery.css',
                'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css',
            ],
            'model' => 'User',
        ];
        $config['seo'] = config('apps.user');
        $template = 'backend.user.user.index'; // tên của view
        return view('backend.dashboard.layout', compact(
            'template',
            'config',
            'users',
        ));

    }

    // Create user
    public function create() {
        $this->authorize('modules', 'user.create');

        $provinces = $this->provinceRepository->all();
        $config = $this->configData();
        $config['seo'] = config('apps.user');
        $config['method'] = 'create';
        $template = 'backend.user.user.store';
        return view('backend.dashboard.layout', compact(
            'template',
            'config',
            'provinces',
        ));

    }

    // Store data
    public function store(StoreUserRequest $request) {
        if($this->userService->create($request)) {
            toastr()->success("Thêm mới bản ghi thành công.");
            return redirect()->route('user.index');
        }
        toastr()->error("Thêm mới bản ghi không thành công.");
        return redirect()->route('user.index');
    }

    // Edit
    public function edit($id) {
        $this->authorize('modules', 'user.update');

        $user = $this->userRepository->findById($id);
        $provinces = $this->provinceRepository->all();
        $config = $this->configData();
        $config['seo'] = config('apps.user');
        $config['method'] = 'edit';
        $template = 'backend.user.user.store';
        return view('backend.dashboard.layout', compact(
            'template',
            'config',
            'provinces',
            'user',
        ));

    }

    // Update
    public function update($id, UpdateUserRequest $request) {
        if($this->userService->update($id, $request)) {
            toastr()->success("Cập nhật bản ghi thành công.");
            return redirect()->route('user.index');
        }
        toastr()->error("Cập nhật bản ghi không thành công.");
        return redirect()->route('user.index');
    }

    // Delete
    public function delete($id) {
        $this->authorize('modules', 'user.destroy');

        $config['seo'] = config('apps.user');
        $user = $this->userRepository->findById($id);
        $template = 'backend.user.user.delete';
        return view('backend.dashboard.layout', compact(
            'template',
            'config',
            'user',
        ));

    }

    // Destroy
    public function destroy($id) {
        if($this->userService->destroy($id)) {
            toastr()->success("Xóa bản ghi thành công.");
            return redirect()->route('user.index');
        }
        toastr()->error("Xóa bản ghi không thành công.")    ;
        return redirect()->route('user.index');
    }

    // function configData cho create & edit
    private function configData() {
        return [
            'css' => [
                'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css',
            ],
            'js' => [
                // Select2 combobox
                'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js',
                'backend/library/location.js',

                // CKFinder2 Upload image
                'backend/plugins/ckfinder_2/ckfinder.js',
                'backend/library/finder.js',
            ],
        ];
    }
}
