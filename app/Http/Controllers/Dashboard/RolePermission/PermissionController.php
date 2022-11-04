<?php

namespace App\Http\Controllers\Dashboard\RolePermission;

use App\Http\Controllers\Controller;
use App\Http\Requests\PermissionRequest;
use App\Http\Traits\ResponseTrait;
use App\Repositories\Common\CommonRepository;
use App\Repositories\RolePermission\PermissionRepository;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;


class PermissionController extends Controller
{
    use ResponseTrait;

    protected $permission;
    protected $common;

    public function __construct(PermissionRepository $permissionRepository, CommonRepository $commonRepository)
    {
        $this->permission = $permissionRepository;
        $this->common = $commonRepository;
    }

    public function store(PermissionRequest $request)
    {
        if ($request->isMethod('post')) {
            DB::beginTransaction();

            try {
                $onlyGo = $request->only('name', 'module_name');
                $permission = $this->permission->store($onlyGo);

                DB::commit();

                $message = "Permission store successful";
                return $this->successResponse(Response::HTTP_CREATED, $message, $permission->toArray());


            } catch (QueryException $e) {
                DB::rollBack();
                return $this->errorResponse(Response::HTTP_INTERNAL_SERVER_ERROR, $e->getMessage(), []);
            }
        }else{
            return $this->errorResponse(405, 'Method Not Supported', []);
        }
    }


    public function update(PermissionRequest $request, $id)
    {
        if ($request->isMethod("PATCH")) {
            DB::beginTransaction();

            try {
                $permission = $this->common->findOnModel('Spatie\Permission\Models\Permission', 'id', $id);

                $permission = $this->common->update($request->all(), $permission);

                DB::commit();
                $message = "Permission update successful";
                return $this->successResponse(Response::HTTP_OK, $message, $permission->toArray());

            } catch (QueryException $e) {
                DB::rollBack();
                return $this->errorResponse(Response::HTTP_INTERNAL_SERVER_ERROR, $e->getMessage(), []);
            }
        }else{
            return $this->errorResponse(405, 'Method Not Supported', []);
        }
    }
}
