<?php

namespace App;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class Theme
{
    public static function getMainColor(){      // {{$theme->getMainColor()}}
        return "80bb01";
    }
}
