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

    /////////////////////////////////////////////////////////////////////////////////////////////////////////
    // CRUD

    // Phân trang (View)
    public function paginate($request) {

        $condition['keyword'] = addslashes($request->input('keyword'));
        $condition['publish'] = $request->integer('publish');
        $condition['post_catalogue_id'] = $request->input('post_catalogue_id');
        $condition['where'] = [
            ['tb2.language_id', '=', $this->language],
        ];

        $perPage = $request->integer('perpage');

        $posts = $this->postRepository->pagination(
            $this->paginateSelect(), 
            $condition, 
            $perPage,
            [
                'path' => 'post.index', 
                'groupBy' => $this->paginateSelect(),
            ],
            ['posts.id', 'DESC'], // order by // cái post nào mới (id to nhất) thì đưa lên trước
            [
                ['post_language as tb2', 'tb2.post_id', '=' , 'posts.id'], // val[0], val[1], val[2], val[3]
                ['post_catalogue_post as tb3', 'posts.id', '=', 'tb3.post_id'],
            ], //joins
            ['post_catalogues'], // relations
            $this->whereRaw($request),
        );
        return $posts;
    }

    // Create Post
    public function create(Request $request) {
        DB::beginTransaction();
        try {
            // Tạo Post
            $post = $this->createPost($request);
            // Nếu create ở trên thành công (tức có dòng thêm vào : > 0)
            if($post->id > 0) {
                // Update Language và Catalogue
                $this->updateLanguageForPost($post, $request);
                $this->updateCatalogueForPost($post, $request);               
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
            // Nếu update thành công vào bảng thì bắt lại
            if($this->uploadPost($post, $request) == TRUE) { 
                $this->updateLanguageForPost($post, $request);
                $this->updateCatalogueForPost($post, $request);
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
            $post = $this->postRepository->delete($id); // Soft delete

            DB::commit();
            return true;
        }
        catch(\Exception $e) {
            DB::rollback();
            // Log::error($e->getMessage());
            // echo $e->getMessage(); die();
            return false;
        }
    }

    /////////////////////////////////////////////////////////////////////////////////////////////////////////
    // FUNCTION CHO CREATE & UPDATE

    // Format Album
    private function formatAlbum($request) {
        //return (isset($payload['album']) && !empty($payload['album'])) ? json_encode($payload['album']) : '';
        //return (isset($album) && !empty($album)) ? json_encode($album) : '';
        return ($request->input('album') && !empty($request->input('album'))) ? json_encode($request->input('album')) : '';
    }

    // Format Language
    private function formatLanguagePayload($payload, $postId) {
        $payload['canonical'] = Str::slug($payload['canonical']); // slug là hàm tạo chuỗi không dấu (dùng trong URL)
        $payload['language_id'] = $this->currentLanguage();
        $payload['post_id'] = $postId;
        return $payload;
    }

    /////////////////////////////////////////////////////////////////////////////////////////////////////////
    // FUNCTION CHO CREATE

    // Create Post
    private function createPost($request) {
        $payload = $request->only($this->payload());
        $payload['user_id'] = Auth::id(); // lấy id là id của người đang thêm vào
        $payload['album'] = $this->formatAlbum($request);

        $post = $this->postRepository->create($payload);
        return $post;
    }

    /////////////////////////////////////////////////////////////////////////////////////////////////////////
    // FUNCTION CHO UPDATE

    // Tiến hành upload Post
    private function uploadPost($post, $request) {
        $payload = $request->only($this->payload());
        $payload['album'] = $this->formatAlbum($request);
        return $this->postRepository->update($post->id, $payload);
    }

    // Update language cho Post
    private function updateLanguageForPost($post, $request) {
        $payload = $request->only($this->payloadLanguage());
        $payload = $this->formatLanguagePayload($payload, $post->id);
        // detach: xóa một bản ghi khỏi bảng pivot
        $post->languages()->detach([$this->language, $post->id]);
        // tạo lại bản ghi mới
        return $response = $this->postRepository->createPivot($post, $payload, 'languages');
    }
    
    // Update Catalogue cho bảng Post
    private function updateCatalogueForPost($post, $request) {
        // Insert vào bảng pivot
        $post->post_catalogues()->sync($this->catalogue($request));
    }

    /////////////////////////////////////////////////////////////////////////////////////////////////////////

    // Bắt postCataloguePost (bắt mối quan hệ)
    private function catalogue($request){
        if($request->input('catalogue') != null){
            return array_unique(array_merge($request->input('catalogue'), [$request->post_catalogue_id]));
        }
        return [$request->post_catalogue_id];
    }

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

    // Viết hàm để thêm câu truy vấn chay: lấy các danh mục con trong một danh mục cha được chọn
    private function whereRaw($request) {
        $rawCondition = [];
        if($request->integer('post_catalogue_id') > 0) {
            $rawCondition['whereRaw'] = [
                [
                    'tb3.post_catalogue_id IN (
                        SELECT id
                        FROM post_catalogues
                        WHERE lft >= (SELECT lft FROM post_catalogues as pc WHERE pc.id = ?)
                        AND rgt <= (SELECT rgt FROM post_catalogues as pc WHERE pc.id = ?)
                    )',
                    [$request->integer('post_catalogue_id'), $request->integer('post_catalogue_id')]
                ] // Gồm câu truy vấn và mảng tham số binding vào trong dấu "?"
            ];
        }
        return $rawCondition;
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
