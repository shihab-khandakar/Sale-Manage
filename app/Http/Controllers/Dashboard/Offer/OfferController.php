<?php

namespace App\Http\Controllers\Dashboard\Offer;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Traits\ResponseTrait;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\OfferRequest;
use Illuminate\Database\QueryException;
use App\Repositories\Common\CommonRepository;

class OfferController extends Controller
{
    use ResponseTrait;

    protected $common;

    public function __construct(CommonRepository $commonRepository)
    {
        $this->common = $commonRepository;
    }
    

    public function store(OfferRequest $request){

        if($request->isMethod('post')){
            DB::beginTransaction();
            try{

                $offer = $this->common->storeInModel('App\Models\Offer', $request->all());

                DB::commit();
                $massage = 'Offer Created Successfully';
                return $this->successResponse(Response::HTTP_CREATED, $massage, $offer->toArray());
            }catch(QueryException $e){
                DB::rollBack();
                return $this->errorResponse(Response::HTTP_INTERNAL_SERVER_ERROR, $e->getMessage(), []);
            }
        }

    }

    public function update(OfferRequest $request, $id){

        if($request->isMethod('post')){

            DB::beginTransaction();
            try{
                $offer = $this->common->findOnModel('App\Models\Offer', 'id', $id);
                if (!$offer){
                    return $this->errorResponse(404, 'Not Found Your Targeted Data', []);
                }

                $offer = $this->common->update($request->all(), $offer);

                DB::commit();
                $message = "Offer Updated Successfully";
                return $this->successResponse(Response::HTTP_OK, $message, $offer->toArray());

            } catch (QueryException $e) {
                DB::rollBack();
                return $this->errorResponse(Response::HTTP_INTERNAL_SERVER_ERROR, $e->getMessage(), []);
            }
        }

    }

}
