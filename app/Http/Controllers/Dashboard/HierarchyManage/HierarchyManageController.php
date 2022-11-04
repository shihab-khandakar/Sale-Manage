<?php

namespace App\Http\Controllers\Dashboard\HierarchyManage;

use App\Http\Controllers\Controller;
use App\Models\Hierarchy\Hierarchy;
use App\Models\Location\District;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HierarchyManageController extends Controller
{
    public function index(){
       return DB::table('districts')->orderBy('id')->get()->groupBy('division_id');
    }
}
