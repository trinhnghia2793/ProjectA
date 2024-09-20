<?php

namespace App\Services\Interfaces;
use Illuminate\Http\Request;

/**
 * Interface UseCataloguerServiceInterface
 * @package App\Services\Interfaces
 */
interface UserCatalogueServiceInterface
{
    // Khai báo interface
    public function paginate($request);
    public function create(Request $request);
    public function update($id, $request);
    public function destroy($id);
    public function updateStatus($post = []);
    public function updateStatusAll($post);
}
