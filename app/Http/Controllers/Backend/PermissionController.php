<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Services\Interfaces\PermissionServiceInterface as PermissionService;
use App\Repositories\Interfaces\PermissionRepositoryInterface as PermissionRepository;

use App\Http\Requests\StorePermissionRequest;
use App\Http\Requests\UpdatePermissionRequest;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class PermissionController extends Controller
{
    protected $permissionService;
    protected $permissionRepository;

    // Constructor
    public function __construct(
        PermissionService $permissionService,
        PermissionRepository $permissionRepository,
    ){
        $this->permissionService = $permissionService;
        $this->permissionRepository = $permissionRepository;
    }

    // Index
    public function index(Request $request) {
        $this->authorize('modules', 'permission.index');

        $permissions = $this->permissionService->paginate($request);

        $config = [
            'js' => [
                'backend/js/plugins/switchery/switchery.js',
                'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js'
            ],
            'css' => [
                'backend/css/plugins/switchery/switchery.css',
                'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css',
            ],
            'model' => 'permission',
        ];
        $config['seo'] = __('messages.permission');
        $template = 'backend.permission.index'; // tên của view
        return view('backend.dashboard.layout', compact(
            'template',
            'config',
            'permissions',
        ));

    }

    // Create
    public function create() {
        $this->authorize('modules', 'permission.create');

        $config = $this->configData();
        $config['seo'] = __('messages.permission');
        $config['method'] = 'create';
        $template = 'backend.permission.store';
        return view('backend.dashboard.layout', compact(
            'template',
            'config',
        ));

    }

    // Store data
    public function store(StorePermissionRequest $request) {
        if($this->permissionService->create($request)) {
            toastr()->success("Thêm mới bản ghi thành công.");
            return redirect()->route('permission.index');
        }
        toastr()->error("Thêm mới bản ghi không thành công.");
        return redirect()->route('permission.index');
    }

    // Edit
    public function edit($id) {
        $this->authorize('modules', 'permission.update');

        $permission = $this->permissionRepository->findById($id);
        $config = $this->configData();
        $config['seo'] = __('messages.permission');
        $config['method'] = 'edit';
        $template = 'backend.permission.store';
        return view('backend.dashboard.layout', compact(
            'template',
            'config',
            'permission',
        ));

    }

    // Update
    public function update($id, UpdatePermissionRequest $request) {
        if($this->permissionService->update($id, $request)) {
            toastr()->success("Cập nhật bản ghi thành công.");
            return redirect()->route('permission.index');
        }
        toastr()->error("Cập nhật bản ghi không thành công.");
        return redirect()->route('permission.index');
    }

    // Delete
    public function delete($id) {
        $this->authorize('modules', 'permission.destroy');

        $config['seo'] = __('messages.permission');
        $permission = $this->permissionRepository->findById($id);
        $template = 'backend.permission.delete';
        return view('backend.dashboard.layout', compact(
            'template',
            'config',
            'permission',
        ));

    }

    // Destroy
    public function destroy($id) {
        if($this->permissionService->destroy($id)) {
            toastr()->success("Xóa bản ghi thành công.");
            return redirect()->route('permission.index');
        }
        toastr()->error("Xóa bản ghi không thành công.")    ;
        return redirect()->route('permission.index');
    }

    // Function configData cho create & edit
    private function configData() {
        return [
            
        ];
    }

}
