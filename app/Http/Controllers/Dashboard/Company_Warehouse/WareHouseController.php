<?php

namespace App\Http\Controllers\Dashboard\Company_Warehouse;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Traits\ResponseTrait;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\WarehouseRequest;
use Illuminate\Database\QueryException;
use App\Repositories\Common\CommonRepository;
use App\Repositories\warehouse\warehouseRepository;

class WareHouseController extends Controller
{
    
    use ResponseTrait;

    protected $warehouse;
    protected $common;

    public function __construct(CommonRepository $commonRepository)
    {
        $this->common = $commonRepository;
    }


    public function store(WarehouseRequest $request){

        if($request->isMethod('post')){
            DB::beginTransaction();
            try{
                $warehouse = $this->common->storeInModel('App\Models\Warehouse', $request->all());
                DB::commit();
                
                $message = "Warehouse Created Successfully";
                return $this->successResponse(Response::HTTP_CREATED, $message, $warehouse->toArray());

            }catch(QueryException $e){
                DB::rollBack();
                return $this->errorResponse(Response::HTTP_INTERNAL_SERVER_ERROR, $e->getMessage(), []);
            }
        }

    }


    public function update(WarehouseRequest $request, $id){

        if($request->isMethod('patch')){

            DB::beginTransaction();
            try{
                $warehouse = $this->common->findOnModel('App\Models\Warehouse', 'id', $id);
                if (!$warehouse){
                    return $this->errorResponse(404, 'Not Found Your Targeted Data', []);
                }
                $warehouse = $this->common->update($request->all(), $warehouse);
                
                DB::commit();
                $message = "Warehouse Updated Successfully";
                return $this->successResponse(Response::HTTP_OK, $message, $warehouse->toArray());

            } catch (QueryException $e) {
                DB::rollBack();
                return $this->errorResponse(Response::HTTP_INTERNAL_SERVER_ERROR, $e->getMessage(), []);
            }
        }

    }


}
