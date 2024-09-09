<?php

namespace App\Services;

use App\Services\Interfaces\PostCatalogueServiceInterface;
use App\Repositories\Interfaces\PostCatalogueRepositoryInterface as PostCatalogueRepository;
use App\Services\BaseService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Classes\Nestedsetbie;
use Illuminate\Support\Str;

/**
 * Class PostCatalogueService
 * @package App\Services
 */
class PostCatalogueService extends BaseService implements PostCatalogueServiceInterface
{
    protected $postCatalogueRepository;
    protected $nestedset;
    protected $language;

    public function __construct(
        PostCatalogueRepository $postCatalogueRepository,
    )
    {
        $this->language = $this->currentLanguage();
        $this->postCatalogueRepository = $postCatalogueRepository;
        $this->nestedset = new Nestedsetbie([
            'table' => 'post_catalogues',
            'foreign_key' => 'post_catalogue_id',
            'language_id' => $this->language,
        ]);
    }   

    // Phân trang
    public function paginate($request) {

        $condition['keyword'] = addslashes($request->input('keyword'));
        $condition['publish'] = $request->integer('publish');
        $condition['where'] = [
            ['tb2.language_id', '=', $this->language]
        ];

        $perPage = $request->integer('perpage');

        $postCatalogues = $this->postCatalogueRepository->pagination(
            $this->paginateSelect(), 
            $condition, 
            $perPage,
            ['path' => 'post.catalogue.index'],
            ['post_catalogues.lft', 'ASC'], // order by
            [
                ['post_catalogue_language as tb2', 'tb2.post_catalogue_id', '=' , 'post_catalogues.id'] // val[0], val[1], val[2], val[3] bên base repository
            ],           
            // [], // relations
        );

        return $postCatalogues;
    }

    // Tạo một PostCatalogue
    public function create(Request $request) {
        DB::beginTransaction();
        try {
            $payload = $request->only($this->payload());
            $payload['user_id'] = Auth::id(); // lấy id là id của người đang thêm vào
            $payload['album'] = json_encode($payload['album']);
            $postCatalogue = $this->postCatalogueRepository->create($payload);

            // Nếu create ở trên thành công (tức có dòng thêm vào : > 0) sẽ tiến hành thêm ở bảng language
            if($postCatalogue->id > 0) {
                $payloadLanguage = $request->only($this->payloadLanguage());
                $payloadLanguage['canonical'] = Str::slug($payloadLanguage['canonical']); // slug là hàm tạo chuỗi không dấu (dùng trong URL)
                
                $payloadLanguage['language_id'] = $this->currentLanguage();
                $payloadLanguage['post_catalogue_id'] = $postCatalogue->id;

                // Tạo một bản ghi mới
                $language = $this->postCatalogueRepository->createLanguagePivot($postCatalogue, $payloadLanguage);
            }

            // Tính toán lại các giá trị left - right
            $this->nestedset->Get('level ASC', 'order ASC');
            $this->nestedset->Recursive(0, $this->nestedset->Set());
            $this->nestedset->Action();

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
            $postCatalogue = $this->postCatalogueRepository->findById($id);

            $payload = $request->except(['_token', 'send']);
            $payload['album'] = json_encode($payload['album']);
            $flag = $this->postCatalogueRepository->update($id, $payload);
            if($flag == TRUE) { // Nếu update thành công vào bảng thì bắt lại
                $payloadLanguage = $request->only($this->payloadLanguage());
                $payloadLanguage['language_id'] = $this->currentLanguage();
                $payloadLanguage['post_catalogue_id'] = $id;

                // detach: xóa một bản ghi khỏi bảng pivot
                $postCatalogue->languages()->detach([$payloadLanguage['language_id'], $id]);
                // tạo lại bản ghi mới
                $response = $this->postCatalogueRepository->createLanguagePivot($postCatalogue, $payloadLanguage);

                // Tính toán lại các giá trị left - right
                $this->nestedset->Get('level ASC', 'order ASC');
                $this->nestedset->Recursive(0, $this->nestedset->Set());
                $this->nestedset->Action();
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
            $postCatalogue = $this->postCatalogueRepository->delete($id);

            // Tính toán lại các giá trị left - right
            $this->nestedset->Get('level ASC', 'order ASC');
            $this->nestedset->Recursive(0, $this->nestedset->Set());
            $this->nestedset->Action();

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

    // Chọn những trường cần xuất hiện & được phân trang
    private function paginateSelect() {
        return [
            'post_catalogues.id', 
            'post_catalogues.publish', 
            'post_catalogues.image', 
            'post_catalogues.level', 
            'post_catalogues.order', 
            'tb2.name', 
            'tb2.canonical',
        ];
    }

    // Những thuộc tính payload cho create & update của PostCatalogue
    private function payload() {
        return [
            'parent_id', 
            'follow', 
            'publish', 
            'image',
            'album',
        ]; 
    }

    // Những thuộc tính payload cho create & update của Language sau khi add PostCatalogue thành công
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
