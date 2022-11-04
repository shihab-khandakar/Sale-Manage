<?php

namespace App\Http\Controllers\Dashboard\RolePermission;

use App\Http\Controllers\Controller;
use App\Http\Requests\RoleRequest;
use App\Http\Traits\ResponseTrait;
use App\Repositories\Common\CommonRepository;
use App\Repositories\RolePermission\RoleRepository;
use Illuminate\Database\QueryException;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class RoleController extends Controller
{
    use ResponseTrait;

    public function __construct(RoleRepository $roleRepository, CommonRepository $commonRepository)
    {
        $this->role = $roleRepository;
        $this->common = $commonRepository;
    }

    public function retrive()
    {
        DB::beginTransaction();

        try {
            $permissions = $this->common->getAllDataWithoutPaginate('permissions');
            $permissions = $permissions->sortBy('module_name')->groupBy('module_name')->sortDesc();

            DB::commit();

            $message = "";
            return $this->successResponse(200, $message, $permissions->toArray());

        } catch (QueryException $e) {
            DB::rollBack();
            return $this->errorResponse(Response::HTTP_INTERNAL_SERVER_ERROR, $e->getMessage(), []);
        }
    }

    public function store(RoleRequest $request)
    {
        if ($request->isMethod('post')) {
            DB::beginTransaction();

            try {
                $request['name'] = strtolower($request->name);
                $role = $this->common->storeInModel('Spatie\Permission\Models\Role', $request->all());

                $role->syncPermissions($request->input('permission_ids'));

                DB::commit();
                $message = "Role stored Successfully";
                return $this->successResponse(Response::HTTP_CREATED, $message, $role->toArray());

            } catch (QueryException $e) {

                DB::rollBack();
                return $this->errorResponse(Response::HTTP_INTERNAL_SERVER_ERROR, $e->getMessage(), []);
            }
        }else{
            return $this->errorResponse(405, 'Method Not Supported', []);
        }
    }

    public function update(RoleRequest $request, $id)
    {
        if ($request->isMethod('patch')) {
            DB::beginTransaction();

            try {
                $role = $this->common->findOnModel('Spatie\Permission\Models\Role', 'id', $id);

                $request['name'] = strtolower($request->name);
                $role = $this->common->update($request->all(), $role);

                $role->syncPermissions($request->input('permission_ids'));


                DB::commit();
                $message = "Role update successful";
                return $this->successResponse(Response::HTTP_OK, $message, $role->toArray());

            } catch (QueryException $e) {
                DB::rollBack();

                return $this->errorResponse(Response::HTTP_INTERNAL_SERVER_ERROR, $e->getMessage(), []);
            }
        }else{
            return $this->errorResponse(405, 'Method Not Supported', []);
        }
    }
}
