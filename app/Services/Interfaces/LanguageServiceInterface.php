<?php

namespace App\Services\Interfaces;
use Illuminate\Http\Request;

interface LanguageServiceInterface
{
    // Khai báo Interface
    public function paginate($request);
    public function create(Request $request);
    public function update($id, $request);
    public function destroy($id);
    public function updateStatus($post = []);
    public function updateStatusAll($post);
    public function switch($id);
}
