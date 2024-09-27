<?php

namespace App\Services\Interfaces;
use Illuminate\Http\Request;

interface PostServiceInterface
{
    // Khai báo interface
    public function paginate($request, $languageId);
    public function create(Request $request, $languageId);
    public function update($id, $request, $languageId);
    public function destroy($id);
    public function updateStatus($post = []);
    public function updateStatusAll($post);
}
