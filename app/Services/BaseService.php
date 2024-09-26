<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

use App\Repositories\Interfaces\RouterRepositoryInterface as RouterRepository;

use App\Services\Interfaces\BaseServiceInterface;

/**
 * Class LanguageService
 * @package App\Services
 */
class BaseService implements BaseServiceInterface
{
    protected $nestedset;
    protected $routerRepository;
    protected $controllerName;

    public function __construct(
        RouterRepository $routerRepository
    ){ 
        $this->routerRepository = $routerRepository;
    } 
    
    // Ngôn ngữ hiện tại (VN)
    public function currentLanguage() {
        return 1;
    }

    // Format Album
    public function formatAlbum($request) {
        return ($request->input('album') && !empty($request->input('album'))) ? json_encode($request->input('album')) : '';
    }

    // Tính toán lại các giá trị left - right
    public function nestedset() {
        $this->nestedset->Get('level ASC', 'order ASC');
        $this->nestedset->Recursive(0, $this->nestedset->Set());
        $this->nestedset->Action();
    }

    // Format Router Payload
    public function formatRouterPayload($model, $request, $controllerName) {
        $router = [
            'canonical' => $request->input('canonical'),
            'module_id' => $model->id,
            'controllers' => 'App\Http\Controllers\Frontend\\' . $controllerName . '',
        ];
        return $router;
    }

    // Create router
    public function createRouter($model, $request, $controllerName) {
        // Sau khi tạo bản ghi mới, tạo router (cái đường dẫn hay sao á)
        $router = $this->formatRouterPayload($model, $request, $controllerName);
        $this->routerRepository->create($router);
    }

    // Update router
    public function updateRouter($model, $request, $controllerName) {
        $payload = $this->formatRouterPayload($model, $request, $controllerName);
        $condition = [
            ['module_id', '=', $model->id],
            ['controllers', '=', 'App\Http\Controllers\Frontend\\' . $controllerName . ''],
        ];
        $router = $this->routerRepository->findByCondition($condition);
        $res = $this->routerRepository->update($router->id, $payload);
        return $res;
    }

}
