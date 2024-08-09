<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

use App\Services\Interfaces\BaseServiceInterface;

/**
 * Class LanguageService
 * @package App\Services
 */
class BaseService implements BaseServiceInterface
{
    protected $LanguageRepository;

    public function __construct()
    { 

    } 
    
    public function currentLanguage() {
        return 1;
    }

}
