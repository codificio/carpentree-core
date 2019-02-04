<?php

use Carpentree\Core\Http\Controllers\Controller;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{

    public function list()
    {


        Permission::all();
    }

}
