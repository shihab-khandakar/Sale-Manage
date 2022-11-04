<?php

namespace App\Http\Controllers\Dashboard\Bank;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests\BankRequest;
use App\Http\Traits\ResponseTrait;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Database\QueryException;
use App\Repositories\Common\CommonRepository;

class BankController extends Controller
{
    
    use ResponseTrait;
    protected $common;

    public function __construct(CommonRepository $commonRepository)
    {
        $this->common = $commonRepository;
    }

    public function store(BankRequest $request){
        if($request->isMethod('post')){
            DB::beginTransaction();
            try{
                $bank = $this->common->storeInModel('App\Models\Bank', $request->all());

                DB::commit();
                $massage = 'Bank Created Successfully';
                return $this->successResponse(Response::HTTP_CREATED, $massage, $bank->toArray());

            }catch(QueryException $e){
                DB::rollBack();
                return $this->errorResponse(Response::HTTP_INTERNAL_SERVER_ERROR, $e->getMessage(), []);
            }
        }
    }

    public function update(BankRequest $request, $id){
        if($request->isMethod('post')){
            DB::beginTransaction();
            try{
                $bank = $this->common->findOnModel('App\Models\Bank', 'id', $id);
                if (!$bank){
                    return $this->errorResponse(404, 'Not Found Your Targeted Data', []);
                }

                $bank = $this->common->update($request->all(), $bank);
                DB::commit();
                $message = "Bank Updated Successfully";
                return $this->successResponse(Response::HTTP_OK, $message, $bank->toArray());

            }catch(QueryException $e) {
                DB::rollBack();
                return $this->errorResponse(Response::HTTP_INTERNAL_SERVER_ERROR, $e->getMessage(), []);
            }
        }
    }

}
