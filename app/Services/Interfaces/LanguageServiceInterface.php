<?php

namespace App\Services\Interfaces;
use Illuminate\Http\Request;

interface LanguageServiceInterface
{
    public function paginate($request);

    // Khai báo để chống báo lỗi
    public function create(Request $request);
    public function update($id, $request);
    public function destroy($id);
}
