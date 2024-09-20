<?php

namespace App\Services\Interfaces;
use Illuminate\Http\Request;

/**
 * Interface UserServiceInterface
 * @package App\Services\Interfaces
 */
interface UserServiceInterface
{
    // Khai báo interface
    public function paginate($request);
    public function create(Request $request);
    public function update($id, $request);
    public function destroy($id);
    public function updateStatus($post = []);
    public function updateStatusAll($post);

}
