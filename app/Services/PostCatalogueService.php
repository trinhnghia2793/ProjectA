<?php

namespace App\Services;

use App\Services\Interfaces\PostCatalogueServiceInterface;
use App\Repositories\Interfaces\PostCatalogueRepositoryInterface as PostCatalogueRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

/**
 * Class PostCatalogueService
 * @package App\Services
 */
class PostCatalogueService implements PostCatalogueServiceInterface
{
    protected $postCatalogueRepository;

    public function __construct(
        PostCatalogueRepository $postCatalogueRepository,
    )
    {
        $this->postCatalogueRepository = $postCatalogueRepository;
    }   

    // Phân trang
    public function paginate($request) {

        $condition['keyword'] = addslashes($request->input('keyword'));
        $condition['publish'] = $request->integer('publish');
        $perPage = $request->integer('perpage');
        $postCatalogues = $this->postCatalogueRepository->pagination(
            $this->paginateSelect(), $condition, [], ['path' => '/PostCatalogue/index'], 
            $perPage,
            [], // relation
        );
        return $postCatalogues;
    }

    // Tạo một PostCatalogue
    public function create(Request $request) {
        DB::beginTransaction();
        try {
            $payload = $request->except(['_token', 'send']);
            $payload['user_id'] = Auth::id(); // lấy id là id của người đang thêm vào
            $postCatalogue = $this->postCatalogueRepository->create($payload);
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
            $postCatalogue = $this->postCatalogueRepository->update($id, $payload);
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
            $postCatalogue = $this->postCatalogueRepository->delete($id);

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
            $postCatalogue = $this->postCatalogueRepository->update($post['modelId'], $payload);
            // Chuyển tất cả bên User
            // $this->changeUserStatus($post, $payload[$post['field']]);
            
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
            $flag = $this->postCatalogueRepository->updateByWhereIn('id', $post['id'], $payload);
            // Chuyển tất cả bên User
            // $this->changeUserStatus($post, $post['value']);
            
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

    // Cập nhật tình trạng của tất cả User khi cập nhật tình trạng của PostCatalogue
    // private function changeUserStatus($post, $value) {
    //     DB::beginTransaction();
    //     try {
    //         $array = [];
    //         if(isset($post['modelId'])) {
    //             $array[] = $post['modelId'];
    //         }
    //         else {
    //             $array = $post['id'];
    //         }    
    //         $payload[$post['field']] = $value;
    //         $this->userRepository->updateByWhereIn('user_catalogue_id', $array, $payload);

    //         DB::commit();
    //         return true;
    //     }
    //     catch(\Exception $e) {
    //         DB::rollback();
    //         echo $e->getMessage();
    //         die();
    //         return false;
    //     }
    // }

    // Chọn những trường cần xuất hiện & được phân trang
    private function paginateSelect() {
        return ['id', 'name', 'canonical', 'publish', 'image'];
    }
}