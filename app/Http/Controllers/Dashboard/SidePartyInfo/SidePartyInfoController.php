<?php

namespace App\Http\Controllers\Dashboard\SidePartyInfo;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Traits\ResponseTrait;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Database\QueryException;
use App\Http\Requests\SidePartyInfoRequest;
use App\Repositories\Common\CommonRepository;

class SidePartyInfoController extends Controller
{
    
    use ResponseTrait;
    protected $common;

    public function __construct(CommonRepository $commonRepository)
    {
        $this->common = $commonRepository;
    }

    public function store(SidePartyInfoRequest $request){
        if($request->isMethod('post')){
            DB::beginTransaction();
            try{
                $sidePartyInfo = $this->common->storeInModel('App\Models\SidePartyInfo', $request->all());

                DB::commit();
                $massage = 'Side Party Details Created Successfully';
                return $this->successResponse(Response::HTTP_CREATED, $massage, $sidePartyInfo->toArray());

            }catch(QueryException $e){
                DB::rollBack();
                return $this->errorResponse(Response::HTTP_INTERNAL_SERVER_ERROR, $e->getMessage(), []);
            }
        }
    }

    public function update(SidePartyInfoRequest $request, $id){
        if($request->isMethod('post')){
            DB::beginTransaction();
            try{
                $sidePartyInfo = $this->common->findOnModel('App\Models\SidePartyInfo', 'id', $id);
                if (!$sidePartyInfo){
                    return $this->errorResponse(404, 'Not Found Your Targeted Data', []);
                }

                $sidePartyInfo = $this->common->update($request->all(), $sidePartyInfo);
                DB::commit();
                $message = "Side Party Info Details Updated Successfully";
                return $this->successResponse(Response::HTTP_OK, $message, $sidePartyInfo->toArray());

            }catch(QueryException $e) {
                DB::rollBack();
                return $this->errorResponse(Response::HTTP_INTERNAL_SERVER_ERROR, $e->getMessage(), []);
            }
        }
    }

}
