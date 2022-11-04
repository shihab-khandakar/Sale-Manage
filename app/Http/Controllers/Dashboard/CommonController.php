<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Traits\ResponseTrait;
use App\Repositories\Common\CommonRepository;
use Illuminate\Database\QueryException;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use App\Http\Traits\CommonTrait;
use Illuminate\Http\Request;

class CommonController extends Controller
{
    protected $common;
    use ResponseTrait;
    use CommonTrait;

    public function __construct(CommonRepository $commonRepository)
    {
        $this->common = $commonRepository;
    }

    public function index($table, Request $request)
    {

        DB::beginTransaction();
        try {

            $data = $this->common->getDataWithPaginate($table, $request);

            return $this->successResponse(200, '', $data);

        } catch (QueryException $e) {
            DB::rollBack();
            return $this->errorResponse(Response::HTTP_INTERNAL_SERVER_ERROR, $e->getMessage(), []);
        }

    }

    public function getAllData($table)
    {

        DB::beginTransaction();
        try {

            $data = $this->common->getAllDataWithoutPaginate($table);
            return $data;

            return $this->successResponse(200, '', $data);

        } catch (QueryException $e) {
            DB::rollBack();
            return $this->errorResponse(Response::HTTP_INTERNAL_SERVER_ERROR, $e->getMessage(), []);
        }

    }

    public function retrive($table, $id, $columnName = "id", $type = 'client-request')
    {
        DB::beginTransaction();
        try {

            $data = $this->common->findOnTable($table, $columnName, $id);

            if ($type === "server-request") {
                return array($data);
            }

            DB::commit();

            if (empty($data)) {
                $message = "Not Found Your Targeted Data";
                return $this->errorResponse(404, $message, []);
            } else {
                $message = "";
                return $this->successResponse(200, $message, $data);
            }
        } catch (QueryException $e) {
            DB::rollBack();
            return $this->errorResponse(Response::HTTP_INTERNAL_SERVER_ERROR, $e->getMessage(), []);
        }
    }

    public function destroy($table, $id, $columnName = "id")
    {
        DB::beginTransaction();
        try {

            $delete = $this->common->deleteOnTable($table, $columnName, $id);
            DB::commit();

            if (empty($delete)) {
                $message = "Not Found Your Targeted Data";
                return $this->errorResponse(404, $message, []);
            } else {
                $message = ucwords(str_replace("_", " ", $table)) . " delete successful";
                return $this->successResponse(200, $message, []);
            }
        } catch (QueryException $e) {
            DB::rollBack();
            return $this->errorResponse(Response::HTTP_INTERNAL_SERVER_ERROR, $e->getMessage(), []);
        }
    }
}
