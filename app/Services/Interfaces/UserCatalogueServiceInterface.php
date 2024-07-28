<?php

namespace App\Services\Interfaces;
use Illuminate\Http\Request;

/**
 * Interface UseCataloguerServiceInterface
 * @package App\Services\Interfaces
 */
interface UserCatalogueServiceInterface
{
    public function paginate($request);

    public function create(Request $request);
    public function update($id, $request);
    public function destroy($id);
}
