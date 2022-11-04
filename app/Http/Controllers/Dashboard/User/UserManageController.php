<?php

namespace App\Http\Controllers\Dashboard\User;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Traits\CommonTrait;
use App\Http\Traits\ResponseTrait;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Database\QueryException;
use App\Repositories\Common\CommonRepository;

class UserManageController extends Controller
{
    protected $common;
    use ResponseTrait;
    use CommonTrait;

    public function __construct(CommonRepository $commonRepository)
    {
        $this->common = $commonRepository;
    }

    public function index(Request $request, $lavel = 'authorities')
    {

        $lavels = [
            "authorities" => [1, 2, 3, 4, 5, 6, 9],
            "dealers"     => [7],
            "sub-dealers" => [8],
            "retailers"   => [10],
            "employees"   => [11],
        ];

        $perPage = $request->perPage ?? 10;
        $page = $request->page ?? 1;

        $data = [];

        DB::beginTransaction();
        try {
            $query = DB::table('users');
            $query = $query->whereIn('hierarchy_id', $lavels[$lavel]);

            if ($request->has('keyword') || $request->has('sortBy') || $request->has('filterBy')) {

                $parameterKeys = array_keys($request->query());
                $tableColumnsName = $this->tableColumnsName('users');

                foreach ($parameterKeys as $parameterKey) {
                    if ($parameterKey === "keyword") {
                        $keyword = $request->keyword;
                        //search columns name
                        $searchColumnNames = $tableColumnsName[$parameterKey];

                        foreach ($searchColumnNames as $columnName) {
                            $query = $query->orWhere($columnName, 'LIKE', '%' . $keyword . '%');
                        }
                    }

                    if ($parameterKey === "sortBy") {

                        $columnName = $request->sortBy;
                        $direction = $request->direction ?? 'desc';

                        $query = $query->orderBy($columnName, $direction);
                    }

                    //if ($parameterKey === "filterBy"){
                    //    $filterBy = $request->filterBy;
                    //    //search columns name
                    //    $searchColumnNames = $tableColumnsName[$parameterKey];
                    //
                    //    foreach ($searchColumnNames as $columnName){
                    //        $query = $query->orWhere($columnName, 'LIKE', '%' . $keyword . '%');
                    //    }
                    //}
                }
            }


            $total = $query->count();

            $result = $query->offset(($page - 1) * $perPage)->limit($perPage)->latest()->get();

            $data = [
                'data' => $result,
                'total' => $total,
                'page' => $page,
                'perPage' => $perPage,
            ];

            return $this->successResponse(200, 'Data Successfully get', $data);

        } catch (QueryException $e) {
            DB::rollBack();
            return $this->errorResponse(Response::HTTP_INTERNAL_SERVER_ERROR, $e->getMessage(), []);
        }
    }
}
