<?php

namespace App\Services;

use App\Services\Interfaces\PostServiceInterface;
use App\Repositories\Interfaces\PostRepositoryInterface as PostRepository;
use App\Services\BaseService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

/**
 * Class PostService
 * @package App\Services
 */
class PostService extends BaseService implements PostServiceInterface
{
    protected $postRepository;
    protected $language;

    public function __construct(
        PostRepository $postRepository,
    )
    {
        $this->language = $this->currentLanguage();
        $this->postRepository = $postRepository;
    }   

    // Phân trang
    public function paginate($request) {

        $condition['keyword'] = addslashes($request->input('keyword'));
        $condition['publish'] = $request->integer('publish');
        $condition['where'] = [
            ['tb2.language_id', '=', $this->language]
        ];

        $perPage = $request->integer('perpage');

        $posts = $this->postRepository->pagination(
            $this->paginateSelect(), 
            $condition, 
            $perPage,
            ['path' => 'post.index'],
            ['posts.id', 'ASC'], // order by // cái post nào mới (id to nhất) thì đưa lên trước
            [
                ['post_language as tb2', 'tb2.post_id', '=' , 'posts.id'] // val[0], val[1], val[2], val[3] bên base repository
            ],           
            ['post_catalogues'], // relations
        );

        return $posts;
    }

    // Tạo một Post
    public function create(Request $request) {
        DB::beginTransaction();
        try {
            $payload = $request->only($this->payload());
            $payload['user_id'] = Auth::id(); // lấy id là id của người đang thêm vào
            $payload['album'] = (isset($payload['album']) && !empty($payload['album'])) ? json_encode($payload['album']) : '';

            $post = $this->postRepository->create($payload);

            // Nếu create ở trên thành công (tức có dòng thêm vào : > 0) sẽ tiến hành thêm ở bảng language
            if($post->id > 0) {
                $payloadLanguage = $request->only($this->payloadLanguage());

                $payloadLanguage['canonical'] = Str::slug($payloadLanguage['canonical']); // slug là hàm tạo chuỗi không dấu (dùng trong URL)
                $payloadLanguage['language_id'] = $this->currentLanguage();
                $payloadLanguage['post_id'] = $post->id;

                // Tạo một bản ghi mới
                $language = $this->postRepository->createPivot($post, $payloadLanguage, 'languages');

                // Insert vào bảng pivot (chắc thế)
                $catalogue = $this->catalogue($request);
                $post->post_catalogues()->sync($catalogue);
                
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

    // Update thông tin (nghe đâu là xóa đi bản cũ xong insert mới lại?????)
    public function update($id, $request) {
        DB::beginTransaction();
        try {
            // Tìm postCatalogue
            $post = $this->postRepository->findById($id);
            $payload = $request->only($this->payload());
            $payload['album'] = (isset($payload['album']) && !empty($payload['album'])) ? json_encode($payload['album']) : '';

            $flag = $this->postRepository->update($id, $payload);
            if($flag == TRUE) { // Nếu update thành công vào bảng thì bắt lại
                $payloadLanguage = $request->only($this->payloadLanguage());

                $payloadLanguage['canonical'] = Str::slug($payloadLanguage['canonical']); // slug là hàm tạo chuỗi không dấu (dùng trong URL)
                $payloadLanguage['language_id'] = $this->currentLanguage();
                $payloadLanguage['post_id'] = $post->id;

                // detach: xóa một bản ghi khỏi bảng pivot
                $post->languages()->detach([$payloadLanguage['language_id'], $id]);
                // tạo lại bản ghi mới
                $response = $this->postRepository->createPivot($post, $payloadLanguage, 'languages');

                // Insert vào bảng pivot (chắc thế)
                $catalogue = $this->catalogue($request);
                $post->post_catalogues()->sync($catalogue);
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

    // Xóa (có lẽ là đang xóa mềm)
    public function destroy($id) {
        DB::beginTransaction();
        try {
            $post = $this->postRepository->delete($id);

            // // Tính toán lại các giá trị left - right
            // $this->nestedset->Get('level ASC', 'order ASC');
            // $this->nestedset->Recursive(0, $this->nestedset->Set());
            // $this->nestedset->Action();

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

    // Bắt postCataloguePost (bắt mối quan hệ)
    private function catalogue($request){
        if($request->input('catalogue') != null){
            return array_unique(array_merge($request->input('catalogue'), [$request->post_catalogue_id]));
        }
        return [$request->post_catalogue_id];
    }
    
    // private function catalogue($request) {
    //     return array_unique($request->input('catalogue'), [$request->post_catalogue_id]); 
    // }



    // Cập nhật tình trạng của 1 bản ghi (switch)
    public function updateStatus($post = []) {
        DB::beginTransaction();
        try {
            $payload[$post['field']] = (($post['value'] == 1)?2:1);
            $post = $this->postRepository->update($post['modelId'], $payload);
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
            $flag = $this->postRepository->updateByWhereIn('id', $post['id'], $payload);
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

    // Chọn những trường cần xuất hiện & được phân trang
    private function paginateSelect() {
        return [
            'posts.id', 
            'posts.publish', 
            'posts.image', 
            'posts.order', 
            'tb2.name', 
            'tb2.canonical',
        ];
    }

    // Những thuộc tính payload cho create & update của Post
    private function payload() {
        return [
            'follow', 
            'publish', 
            'image',
            'album',
            'post_catalogue_id',
        ]; 
    }

    // Những thuộc tính payload cho create & update của Language sau khi add Post thành công
    // post_catalogue_language
    private function payloadLanguage() {
        return [
            'name', 
            'description', 
            'content', 
            'meta_title', 
            'meta_keyword', 
            'meta_description', 
            'canonical'
        ]; 
    }
}
