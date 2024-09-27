<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Services\Interfaces\LanguageServiceInterface as LanguageService;

use App\Repositories\Interfaces\LanguageRepositoryInterface as LanguageRepository;

use App\Http\Requests\StoreLanguageRequest;
use App\Http\Requests\UpdateLanguageRequest;
use App\Http\Requests\TranslateRequest;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

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
        $this->authorize('modules', 'language.index');

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
        $this->authorize('modules', 'language.create');

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
        $this->authorize('modules', 'language.update');

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
        $this->authorize('modules', 'language.destroy');

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

    // Hàm chuyển đổi ngôn ngữ
    public function switchBackendLanguage($id) {
        $language = $this->languageRepository->findById($id);
        if($this->languageService->switch($id)) {
            Session::put('locale', $language->canonical);
        };
        return back();
    }

    // Hàm dịch ngôn ngữ (lần lượt là id bài viết, id ngôn ngữ cần dịch, tên model)
    public function translate($id = 0, $languageId = 0, $model = '') {
        $this->authorize('modules', 'language.translate');

        // Lấy ra ngôn ngữ hiện tại (lấy repo của language -> lấy hàm ngôn ngữ hiện tại)
        $languageInstance = $this->repositoryInstance('Language');
        $currentLanguage = $languageInstance->findByCondition([
            ['canonical', '=', Session::get('locale')]
        ]);

        // Lấy ra repository -> lấy hàm để truy xuất thông tin
        $repositoryInstance = $this->repositoryInstance($model);
        $method = 'get' . $model . 'ById';
        $object = $repositoryInstance->{$method}($id, $currentLanguage->id); // cái hiện tại
        $objectTranslate = $repositoryInstance->{$method}($id, $languageId); // cái cần dịch

        $config = [
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
        $option = [
            'id' => $id,
            'languageId' => $languageId,
            'model' => $model,
        ];
        $config['seo'] = config('apps.language'); // cái này để chuyển sau
        $template = 'backend.language.translate';
        return view('backend.dashboard.layout', compact(
            'template',
            'config',
            'object',
            'objectTranslate',
            'option',
        ));
    }

    // Lưu lại bản dịch
    public function storeTranslate(TranslateRequest $request) {
        $option = $request->input('option');
        if($this->languageService->saveTranslate($option, $request)) {
            toastr()->success("Cập nhật bản ghi thành công.");
            return redirect()->back();
        }
        toastr()->error("Có vấn đề xảy ra, vui lòng thử lại");
        return redirect()->back();
    }

    // Tạo ra một instance của một repository dựa trên tên model
    private function repositoryInstance($model) {
        $repositoryNamespace = '\App\Repositories\\' . ucfirst($model) . 'Repository';
        if(class_exists($repositoryNamespace)) {
            $repositoryInstance = app($repositoryNamespace);
        }
        return $repositoryInstance ?? null;
    }

}
