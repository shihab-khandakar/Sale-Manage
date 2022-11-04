<?php

namespace App\Http\Controllers\Dashboard\Benefit;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Traits\ResponseTrait;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\BenefitRequest;
use Illuminate\Database\QueryException;
use App\Repositories\Common\CommonRepository;

class BenefitController extends Controller
{
    
    use ResponseTrait;

    protected $common;

    public function __construct(CommonRepository $commonRepository)
    {
        $this->common = $commonRepository;
    }


    public function store(BenefitRequest $request){

        if($request->isMethod('post')){
            DB::beginTransaction();
            try{
                $Benefits = $this->common->storeInModel('App\Models\User\Benefit', $request->all());

                DB::commit();
                $message = "Benefits Created Successfully";
                return $this->successResponse(Response::HTTP_CREATED, $message, $Benefits->toArray());

            }catch(QueryException $e){
                DB::rollBack();
                return $this->errorResponse(Response::HTTP_INTERNAL_SERVER_ERROR, $e->getMessage(), []);
            }
        }

    }


    public function update(BenefitRequest $request, $id){

        if( $request->isMethod('post') ){
            DB::beginTransaction();
            try{
                $benefit = $this->common->findOnModel('App\Models\User\Benefit','id',$id);
                if (!$benefit){
                    return $this->errorResponse(404, 'Not Found Your Targeted Data', []);
                }

                $benefit = $this->common->update($request->all(), $benefit);

                DB::commit();
                $message = "Benefits Updated Successfully";
                return $this->successResponse(Response::HTTP_OK, $message, $benefit->toArray());
            } catch (QueryException $e) {
                DB::rollBack();
                return $this->errorResponse(Response::HTTP_INTERNAL_SERVER_ERROR, $e->getMessage(), []);
            }   

        }

    }


}
