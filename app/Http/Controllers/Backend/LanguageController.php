<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Services\Interfaces\LanguageServiceInterface as LanguageService;
use App\Repositories\Interfaces\LanguageRepositoryInterface as LanguageRepository;

use App\Http\Requests\StoreLanguageRequest;
use App\Http\Requests\UpdateLanguageRequest;

class LanguageController extends Controller
{
    protected $languageService;
    protected $languageRepository;

    // Constructor
    public function __construct(
        LanguageService $languageService,
        LanguageRepository $languageRepository,
    ){
        $this->languageService = $languageService;
        $this->languageRepository = $languageRepository;
    }

    // Index
    public function index(Request $request) {

        $languages = $this->languageService->paginate($request);

        $config = [
            'js' => [
                'backend/js/plugins/switchery/switchery.js',
                'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js'
            ],
            'css' => [
                'backend/css/plugins/switchery/switchery.css',
                'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css',
            ],
            'model' => 'language',
        ];
        $config['seo'] = config('apps.language');
        $template = 'backend.language.index'; // tên của view
        return view('backend.dashboard.layout', compact(
            'template',
            'config',
            'languages',
        ));

    }

    // Create user
    public function create() {

        $config = $this->configData();
        $config['seo'] = config('apps.language');
        $config['method'] = 'create';
        $template = 'backend.language.store';
        return view('backend.dashboard.layout', compact(
            'template',
            'config',
        ));

    }

    // Store data
    public function store(StoreLanguageRequest $request) {
        if($this->languageService->create($request)) {
            toastr()->success("Thêm mới bản ghi thành công.");
            return redirect()->route('language.index');
        }
        toastr()->error("Thêm mới bản ghi không thành công.");
        return redirect()->route('language.index');
    }

    // Edit
    public function edit($id) {

        $language = $this->languageRepository->findById($id);
        $config = $this->configData();
        $config['seo'] = config('apps.language');
        $config['method'] = 'edit';
        $template = 'backend.language.store';
        return view('backend.dashboard.layout', compact(
            'template',
            'config',
            'language',
        ));

    }

    // Update
    public function update($id, UpdateLanguageRequest $request) {
        if($this->languageService->update($id, $request)) {
            toastr()->success("Cập nhật bản ghi thành công.");
            return redirect()->route('language.index');
        }
        toastr()->error("Cập nhật bản ghi không thành công.");
        return redirect()->route('language.index');
    }

    // Delete
    public function delete($id) {

        $config['seo'] = config('apps.language');
        $language = $this->languageRepository->findById($id);
        $template = 'backend.language.delete';
        return view('backend.dashboard.layout', compact(
            'template',
            'config',
            'language',
        ));

    }

    // Destroy
    public function destroy($id) {
        if($this->languageService->destroy($id)) {
            toastr()->success("Xóa bản ghi thành công.");
            return redirect()->route('language.index');
        }
        toastr()->error("Xóa bản ghi không thành công.")    ;
        return redirect()->route('language.index');
    }

    // Function configData cho create & edit
    private function configData() {
        return [
            'js' => [
                // import ckfinder_2 để upload ảnh
                'backend/plugins/ckfinder_2/ckfinder.js',
                'backend/library/finder.js',
            ],
        ];
    }

}
