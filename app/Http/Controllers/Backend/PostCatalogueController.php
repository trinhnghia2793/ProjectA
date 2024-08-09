<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Services\Interfaces\PostCatalogueServiceInterface as PostCatalogueService;
use App\Repositories\Interfaces\PostCatalogueRepositoryInterface as PostCatalogueRepository;

use App\Http\Requests\StorePostCatalogueRequest;
use App\Http\Requests\UpdatePostCatalogueRequest;

class PostCatalogueController extends Controller
{
    protected $postCatalogueService;
    protected $postCatalogueRepository;

    // Constructor
    public function __construct(
        PostCatalogueService $postCatalogueService,
        PostCatalogueRepository $postCatalogueRepository,
    ){
        $this->postCatalogueService = $postCatalogueService;
        $this->postCatalogueRepository = $postCatalogueRepository;
    }

    // Index
    public function index(Request $request) {

        $postCatalogues = $this->postCatalogueService->paginate($request);

        $config = [
            'js' => [
                'backend/js/plugins/switchery/switchery.js',
                'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js'
            ],
            'css' => [
                'backend/css/plugins/switchery/switchery.css',
                'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css',
            ]
        ];
        $config['seo'] = config('apps.postcatalogue');
        $template = 'backend.post.catalogue.index'; // tên của view
        return view('backend.dashboard.layout', compact(
            'template',
            'config',
            'postCatalogues',
        ));

    }

    // Create user
    public function create() {
        $config = $this->configData();
        $config['seo'] = config('apps.postcatalogue');
        $config['method'] = 'create';
        $template = 'backend.post.catalogue.store';
        return view('backend.dashboard.layout', compact(
            'template',
            'config',
        ));
    }

    // Store data
    public function store(StorePostCatalogueRequest $request) {
        if($this->postCatalogueService->create($request)) {
            toastr()->success("Thêm mới bản ghi thành công.");
            return redirect()->route('post.catalogue.index');
        }
        toastr()->error("Thêm mới bản ghi không thành công.");
        return redirect()->route('post.catalogue.index');
    }

    // Edit
    public function edit($id) {
        $postCatalogue = $this->postCatalogueRepository->findById($id);
        $config = $this->configData();
        $config['seo'] = config('apps.postcatalogue');
        $config['method'] = 'edit';
        $template = 'backend.post.catalogue.store';
        return view('backend.dashboard.layout', compact(
            'template',
            'config',
            'postCatalogue',
        ));
    }

    // Update
    public function update($id, UpdatePostCatalogueRequest $request) {
        if($this->postCatalogueService->update($id, $request)) {
            toastr()->success("Cập nhật bản ghi thành công.");
            return redirect()->route('post.catalogue.index');
        }
        toastr()->error("Cập nhật bản ghi không thành công.");
        return redirect()->route('post.catalogue.index');
    }

    // Delete
    public function delete($id) {
        $config['seo'] = config('apps.postcatalogue');
        $postCatalogue = $this->postCatalogueRepository->findById($id);
        $template = 'backend.post.catalogue.delete';
        return view('backend.dashboard.layout', compact(
            'template',
            'config',
            'postCatalogue',
        ));
    }

    // Destroy
    public function destroy($id) {
        if($this->postCatalogueService->destroy($id)) {
            toastr()->success("Xóa bản ghi thành công.");
            return redirect()->route('post.catalogue.index');
        }
        toastr()->error("Xóa bản ghi không thành công.")    ;
        return redirect()->route('post.catalogue.index');
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