<?php

namespace App\Services\Interfaces;
use Illuminate\Http\Request;

interface LanguageServiceInterface
{
    // Khai báo để chống báo lỗi
    public function paginate($request);
    public function create(Request $request);
    public function update($id, $request);
    public function destroy($id);
}
