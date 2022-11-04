<?php

namespace App\Http\Controllers\Dashboard\Transport;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Traits\ResponseTrait;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\TransportRequest;
use Illuminate\Database\QueryException;
use App\Repositories\Common\CommonRepository;

class TransportController extends Controller
{
    
    use ResponseTrait;
    protected $common;

    public function __construct(CommonRepository $commonRepository){
        $this->common = $commonRepository;        
    }

    public function store(TransportRequest $request){
        
        if($request->isMethod('post')){
            DB::beginTransaction();
            try{
                $transport = $this->common->storeInModel('App\Models\Transport', $request->all());

                DB::commit();
                $massage = 'Transport Details Created Successfully';
                return $this->successResponse(Response::HTTP_CREATED, $massage, $transport->toArray());

            }catch(QueryException $e){
                DB::rollBack();
                return $this->errorResponse(Response::HTTP_INTERNAL_SERVER_ERROR, $e->getMessage(), []);
            }
        }

    }


    public function update(TransportRequest $request, $id){

        if($request->isMethod('post')){

            DB::beginTransaction();
            try{
                $transport = $this->common->findOnModel('App\Models\Transport', 'id', $id);
                if (!$transport){
                    return $this->errorResponse(404, 'Not Found Your Targeted Data', []);
                }

                $transport = $this->common->update($request->all(), $transport);
                DB::commit();
                $message = "Transport Updated Successfully";
                return $this->successResponse(Response::HTTP_OK, $message, $transport->toArray());

            }catch(QueryException $e) {
                DB::rollBack();
                return $this->errorResponse(Response::HTTP_INTERNAL_SERVER_ERROR, $e->getMessage(), []);
            }
        }

    }

    
}
