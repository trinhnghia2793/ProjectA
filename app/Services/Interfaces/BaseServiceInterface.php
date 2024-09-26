<?php

namespace App\Services\Interfaces;
use Illuminate\Http\Request;

interface BaseServiceInterface
{
    // Khai báo interface cho BaseService
    public function currentLanguage();
    public function formatAlbum($request);
    public function nestedset();
    public function formatRouterPayload($model, $request, $controllerName);
    public function createRouter($model, $request, $controllerName);
    public function updateRouter($model, $request, $controllerName);
}
