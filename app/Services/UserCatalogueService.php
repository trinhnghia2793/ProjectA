<?php

namespace App\Services;

use App\Services\Interfaces\UserCatalogueServiceInterface;
use App\Repositories\Interfaces\UserCatalogueRepositoryInterface as UserCatalogueRepository;
use App\Repositories\Interfaces\UserRepositoryInterface as UserRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;

/**
 * Class UserCatalogueService
 * @package App\Services
 */
class UserCatalogueService implements UserCatalogueServiceInterface
{
    protected $userCatalogueRepository;
    protected $userRepository;

    public function __construct(
        UserCatalogueRepository $userCatalogueRepository,
        UserRepository $userRepository
    )
    {
        $this->userCatalogueRepository = $userCatalogueRepository;
        $this->userRepository = $userRepository;
    }   

    // Phân trang
    public function paginate($request) {
        $condition = [
            'keyword' => addslashes($request->input('keyword')),
            'publish' => $request->integer('publish'),
        ];
        $perPage = $request->integer('perpage');
        $userCatalogues = $this->userCatalogueRepository->pagination(
            $this->paginateSelect(), 
            $condition, 
            $perPage,
            ['path' => '/user/catalogue/index'], 
            ['id', 'DESC'], 
            [], // join
            ['users'], // relations
        );
        return $userCatalogues;
    }

    // Tạo một UserCatalogue
    public function create(Request $request) {
        DB::beginTransaction();
        try {

            $payload = $request->except(['_token', 'send']);
            $user = $this->userCatalogueRepository->create($payload);
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
            $user = $this->userCatalogueRepository->update($id, $payload);
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
            $user = $this->userCatalogueRepository->delete($id);

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
            $user = $this->userCatalogueRepository->update($post['modelId'], $payload);
            // Chuyển tất cả bên User
            $this->changeUserStatus($post, $payload[$post['field']]);
            
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

    // Cập nhật tình trạng của tất cả bản ghi được tick (toolbox)
    public function updateStatusAll($post) {
        DB::beginTransaction();
        try {
            $payload[$post['field']] = $post['value'];
            $flag = $this->userCatalogueRepository->updateByWhereIn('id', $post['id'], $payload);
            // Chuyển tất cả bên User
            $this->changeUserStatus($post, $post['value']);
            
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

    // Cập nhật tình trạng của tất cả User khi cập nhật tình trạng của UserCatalogue
    private function changeUserStatus($post, $value) {
        DB::beginTransaction();
        try {
            $array = [];
            if(isset($post['modelId'])) {
                $array[] = $post['modelId'];
            }
            else {
                $array = $post['id'];
            }    
            $payload[$post['field']] = $value;
            $this->userRepository->updateByWhereIn('user_catalogue_id', $array, $payload);

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

    // Lưu những quyền cấp cho nhóm thành viên lại vào trong bảng user_catalogue_permission
    public function setPermission($request) {
        DB::beginTransaction();
        try {
            
            $permissions = $request->input('permission');
            if(count($permissions)) {
                foreach($permissions as $key => $val) {
                    $userCatalogue = $this->userCatalogueRepository->findById($key);
                    // Insert vào bảng pivot
                    $userCatalogue->permissions()->detach();
                    $userCatalogue->permissions()->sync($val);
                }
            }
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
    
    // Chọn những trường cần được phân trang
    private function paginateSelect() {
        return [
            'id', 
            'name', 
            'description', 
            'publish'
        ];
    }
}
