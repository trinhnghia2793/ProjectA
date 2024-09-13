<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Services\Interfaces\PostServiceInterface as PostService;
use App\Repositories\Interfaces\PostRepositoryInterface as PostRepository;

use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Http\Requests\DeletePostRequest; // Không có cái này

use App\Classes\Nestedsetbie;

class PostController extends Controller
{
    protected $postService;
    protected $postRepository;
    protected $language;
    
    protected $nestedset;

    // Constructor
    public function __construct(
        PostService $postService,
        PostRepository $postRepository,
    ){
        $this->postService = $postService;
        $this->postRepository = $postRepository;

        $this->nestedset = new Nestedsetbie([
            'table' => 'post_catalogues',
            'foreign_key' => 'post_catalogue_id',
            'language_id' => 1, // tạm thời đê là 1
        ]);

        $this->language = $this->currentLanguage();
    }

    // Index
    public function index(Request $request) {

        $posts = $this->postService->paginate($request);

        $config = [
            'js' => [
                'backend/js/plugins/switchery/switchery.js',
                'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js'
            ],
            'css' => [
                'backend/css/plugins/switchery/switchery.css',
                'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css',
            ],
            'model' => 'Post',
        ];
        $config['seo'] = config('apps.post');
        $template = 'backend.post.post.index'; // tên của view
        $dropdown = $this->nestedset->Dropdown();

        return view('backend.dashboard.layout', compact(
            'template',
            'config',
            'dropdown',
            'posts',
        ));

    }

    // Create a post catalogue
    public function create() {
        $config = $this->configData();
        $config['seo'] = config('apps.post');
        $config['method'] = 'create';

        $dropdown = $this->nestedset->Dropdown();

        $template = 'backend.post.post.store';
        return view('backend.dashboard.layout', compact(
            'template',
            'dropdown',
            'config',
        ));
    }

    // Store data
    public function store(StorePostRequest $request) {
        if($this->postService->create($request)) {
            toastr()->success("Thêm mới bản ghi thành công.");
            return redirect()->route('post.index');
        }
        toastr()->error("Thêm mới bản ghi không thành công.");
        return redirect()->route('post.index');
    }

    // Edit
    public function edit($id) {
        $post = $this->postRepository->getPostById($id, $this->language);

        $config = $this->configData();
        $config['seo'] = config('apps.post');
        $config['method'] = 'edit';

        $dropdown = $this->nestedset->Dropdown();

        $album = json_decode($post->album);

        $template = 'backend.post.post.store';
        return view('backend.dashboard.layout', compact(
            'template',
            'config',
            'dropdown',
            'post',
            'album'
        ));
    }

    // Update
    public function update($id, UpdatePostRequest $request) {
        if($this->postService->update($id, $request)) {
            toastr()->success("Cập nhật bản ghi thành công.");
            return redirect()->route('post.index');
        }
        toastr()->error("Cập nhật bản ghi không thành công.");
        return redirect()->route('post.index');
    }

    // Delete
    public function delete($id) {
        $config['seo'] = config('apps.post');
        $post = $this->postRepository->getPostById($id, $this->language);
        $template = 'backend.post.post.delete';
        return view('backend.dashboard.layout', compact(
            'template',
            'config',
            'post',
        ));
    }

    // Destroy
    public function destroy($id) {
        if($this->postService->destroy($id)) {
            toastr()->success("Xóa bản ghi thành công.");
            return redirect()->route('post.index');
        }
        toastr()->error("Xóa bản ghi không thành công.")    ;
        return redirect()->route('post.index');
    }

    // Function configData cho create & edit
    private function configData() {
        return [
            'js' => [
                // import ckeditor
                'backend/plugins/ckeditor/ckeditor.js',

                // import ckfinder_2 để upload ảnh
                'backend/plugins/ckfinder_2/ckfinder.js',

                // Add các file js chạy hàm
                'backend/library/finder.js',
                'backend/library/seo.js',

                // switchery
                'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js'
            ],
            'css' => [
                'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css'
            ],
        ];
    }

}
