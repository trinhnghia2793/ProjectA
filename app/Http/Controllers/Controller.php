<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

use Illuminate\Support\Facades\Session;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    protected $language;

    public function __construct()
    {
        $this->language = Session::get('locale');
    }

    // Lẩy ra ngôn ngữ hiện tại (để tạm ở đây)
    public function currentLanguage() {
        return 1;
    }
}
