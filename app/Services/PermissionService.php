<?php

namespace App\Services;

use App\Services\Interfaces\PermissionServiceInterface;
use App\Repositories\Interfaces\PermissionRepositoryInterface as PermissionRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

/**
 * Class PermissionService
 * @package App\Services
 */
class PermissionService implements PermissionServiceInterface
{
    protected $permissionRepository;

    public function __construct(
        PermissionRepository $permissionRepository,
    )
    {
        $this->permissionRepository = $permissionRepository;
    }   

    // Phân trang
    public function paginate($request) {

        $condition['keyword'] = addslashes($request->input('keyword'));
        $condition['publish'] = $request->integer('publish');
        $perPage = $request->integer('perpage');
        $permissions = $this->permissionRepository->pagination(
            $this->paginateSelect(), 
            $condition, 
            $perPage,
            ['path' => '/permission/index'], 
        );
        return $permissions;
    }

    // Tạo một Permission
    public function create(Request $request) {
        DB::beginTransaction();
        try {
            $payload = $request->except(['_token', 'send']);
            $payload['user_id'] = Auth::id(); // lấy id là id của người đang thêm vào
            $permission = $this->permissionRepository->create($payload);
            DB::commit();
            return true;
        }
        catch(\Exception $e) {
            DB::rollback();
            echo $e->getMessage();
            die();
            return false;
        }
    }

    // Update thông tin
    public function update($id, $request) {
        DB::beginTransaction();
        try {
            $payload = $request->except(['_token', 'send']);
            $permission = $this->permissionRepository->update($id, $payload);
            DB::commit();
            return true;
        }
        catch(\Exception $e) {
            DB::rollback();
            echo $e->getMessage();
            die();
            return false;
        }
    }

    // Xóa (có lẽ là đang xóa mềm)
    public function destroy($id) {
        DB::beginTransaction();
        try {
            $permission = $this->permissionRepository->delete($id);

            DB::commit();
            return true;
        }
        catch(\Exception $e) {
            DB::rollback();
            echo $e->getMessage();
            die();
            return false;
        }
    }

    // Cập nhật tình trạng của 1 bản ghi (switch)
    public function updateStatus($post = []) {
        DB::beginTransaction();
        try {
            $payload[$post['field']] = (($post['value'] == 1)?2:1);
            $permission = $this->permissionRepository->update($post['modelId'], $payload);
            
            DB::commit();
            return true;
        }
        catch(\Exception $e) {
            DB::rollback();
            echo $e->getMessage();
            die();
            return false;
        }
    }

    // Chọn những trường cần xuất hiện & được phân trang
    private function paginateSelect() {
        return [
            'id', 
            'name', 
            'canonical', 
        ];
    }
}
