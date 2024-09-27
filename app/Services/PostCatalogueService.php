<?php

namespace App\Services;

use App\Services\Interfaces\PostCatalogueServiceInterface;
use App\Services\BaseService;

use App\Repositories\Interfaces\PostCatalogueRepositoryInterface as PostCatalogueRepository;
use App\Repositories\Interfaces\RouterRepositoryInterface as RouterRepository;

use App\Classes\Nestedsetbie;
use Illuminate\Support\Facades\Session;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

/**
 * Class PostCatalogueService
 * @package App\Services
 */
class PostCatalogueService extends BaseService implements PostCatalogueServiceInterface
{
    protected $postCatalogueRepository;
    protected $routerRepository;
    protected $nestedset;
    protected $language;

    public function __construct(
        PostCatalogueRepository $postCatalogueRepository,
        RouterRepository $routerRepository,
    )
    {
        $this->language = $this->currentLanguage();
        //$this->language = Session::get('locale');
        $this->postCatalogueRepository = $postCatalogueRepository;
        $this->routerRepository = $routerRepository;
        $this->controllerName = 'PostCatalogueController';

        $this->nestedset = new Nestedsetbie([
            'table' => 'post_catalogues',
            'foreign_key' => 'post_catalogue_id',
            'language_id' => $this->language,
        ]);
    }   

    /////////////////////////////////////////////////////////////////////////////////////////////////////////
    // CRUD
    
    // Phân trang
    public function paginate($request) {
        $perPage = $request->integer('perpage');
        $condition = [
            'keyword' => addslashes($request->input('keyword')),
            'publish' => $request->integer('publish'),
            'where' => [
                ['tb2.language_id', '=', $this->language]
            ],
        ];
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

    // Create PostCatalogue (create post cata -> update language -> update router -> adjust nestedset)
    public function create(Request $request) {
        DB::beginTransaction();
        try {         
            $postCatalogue = $this->createCatalogue($request);
            // Nếu create ở trên thành công (tức có dòng thêm vào : > 0) sẽ tiến hành thêm ở bảng language
            if($postCatalogue->id > 0) {
                $this->updateLanguageForCatalogue($postCatalogue, $request);
                $this->createRouter($postCatalogue, $request, $this->controllerName);
                $this->nestedset();
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

    // Update PostCatalogue (update post cata -> update language -> update router -> adjust nestedset)
    public function update($id, $request) {
        DB::beginTransaction();
        try {
            // Tìm PostCatalogue theo Id
            $postCatalogue = $this->postCatalogueRepository->findById($id);
            // Nếu update thành công vào bảng thì bắt lại
            if($this->updateCatalogue($postCatalogue, $request) == TRUE) { 
                $this->updateLanguageForCatalogue($postCatalogue, $request);
                $this->updateRouter($postCatalogue, $request, $this->controllerName);                
                $this->nestedset();
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

    /////////////////////////////////////////////////////////////////////////////////////////////////////////
    // FUNCTION CHO CREATE & UPDATE

    // Format language payload
    private function formatLanguagePayload($postCatalogue, $request) {
        $payload = $request->only($this->payloadLanguage());
        $payload['canonical'] = Str::slug($payload['canonical']); // slug là hàm tạo chuỗi không dấu (dùng trong URL) 
        $payload['language_id'] = $this->currentLanguage();
        $payload['post_catalogue_id'] = $postCatalogue->id;

        return $payload;
    }

    /////////////////////////////////////////////////////////////////////////////////////////////////////////
    // FUNCTION CHO CREATE

    // Create Post Catalogue
    private function createCatalogue($request) {
        $payload = $request->only($this->payload());
        $payload['album'] = $this->formatAlbum($request);
        $payload['user_id'] = Auth::id(); // lấy id là id của người đang thêm vào
        $postCatalogue = $this->postCatalogueRepository->create($payload);
        return $postCatalogue;
    }

    /////////////////////////////////////////////////////////////////////////////////////////////////////////
    // FUNCTION CHO UPDATE
    
    // Update Post Catalogue
    private function updateCatalogue($postCatalogue, $request) {
        // Tìm postCatalogue
        $payload = $request->except(['_token', 'send']);
        $payload['album'] = $this->formatAlbum($request);
        $flag = $this->postCatalogueRepository->update($postCatalogue->id, $payload);
        return $flag;
    }

    // Update Language cho post catalogue
    private function updateLanguageForCatalogue($postCatalogue, $request) {
        $payload = $this->formatLanguagePayload($postCatalogue, $request);
        $postCatalogue->languages()->detach([$this->language, $postCatalogue->id]);
        // Tạo một bản ghi mới
        $language = $this->postCatalogueRepository->createPivot($postCatalogue, $payload, 'languages');
        return $language;
    }

    /////////////////////////////////////////////////////////////////////////////////////////////////////////

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
