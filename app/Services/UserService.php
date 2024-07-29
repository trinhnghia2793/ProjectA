<?php

namespace App\Services;

use App\Services\Interfaces\UserServiceInterface;
use App\Repositories\Interfaces\UserRepositoryInterface as UserRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;

/**
 * Class UserService
 * @package App\Services
 */
class UserService implements UserServiceInterface
{
    protected $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }   

    // Truy vấn users & phân trang
    public function paginate($request) {
        $condition['keyword'] = addslashes($request->input('keyword'));
        $condition['publish'] = $request->integer('publish');
        $perPage = $request->integer('perpage');
        $users = $this->userRepository->pagination(
            $this->paginateSelect(), $condition, [], ['path' => '/user/index'], $perPage
        );
        return $users;
    }

    // Tạo user
    public function create(Request $request) {
        DB::beginTransaction();
        try {

            $payload = $request->except(['_token', 'send', 're_password']);
            $payload['birthday'] = $this->convertBirthdayDate($payload['birthday']);
            $payload['password'] = Hash::make($payload['password']);
            $user = $this->userRepository->create($payload);
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

    // Cập nhật user
    public function update($id, $request) {
        DB::beginTransaction();
        try {

            $payload = $request->except(['_token', 'send']);
            $payload['birthday'] = $this->convertBirthdayDate($payload['birthday']);
            $user = $this->userRepository->update($id, $payload);
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

    // Xóa user
    public function destroy($id) {
        DB::beginTransaction();
        try {
            $user = $this->userRepository->delete($id);

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

    // Cập nhật trạng thái của user
    public function updateStatus($post = []) {
        DB::beginTransaction();
        try {
            $payload[$post['field']] = (($post['value'] == 1)?2:1);
            $user = $this->userRepository->update($post['modelId'], $payload);
            
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

    // Cập nhật trạng thái của các user được chọn
    public function updateStatusAll($post) {
        DB::beginTransaction();
        try {
            $payload[$post['field']] = $post['value'];
            $flag = $this->userRepository->updateByWhereIn('id', $post['id'], $payload);
            
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

    // Chuyển đổi ngày tháng (dùng trong trường ngày sinh)
    private function convertBirthdayDate($birthday = '') {
        $carbonDate = Carbon::createFromFormat('Y-m-d', $birthday);
        $birthday = $carbonDate->format('Y-m-d H:i:s');
        return $birthday;
    }

    // Chọn những thuộc tính dùng để hiện phân trang
    private function paginateSelect() {
        return [
        'id', 
        'name', 
        'email', 
        'phone', 
        'address', 
        'publish',
        'user_catalogue_id',
        ];
    }
}
