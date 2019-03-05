<?php

namespace Carpentree\Core\Http\Controllers;

class LocaleController extends Controller
{

    public function all()
    {
        return response()->json(config('translatable.locales'));
    }

}
