<?php

namespace App\Http\Controllers\Dashboard\Company_Warehouse;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Traits\ResponseTrait;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Database\QueryException;
use App\Http\Requests\DealerStockRequest;
use App\Repositories\Common\CommonRepository;

class DealerStockController extends Controller
{
    use ResponseTrait;
    protected $common; 

    public function __construct(CommonRepository $commonRepository)
    {
        $this->common = $commonRepository;
    }

    public function store(DealerStockRequest $request){

        if($request->isMethod('post')){
            DB::beginTransaction();
            try{
                $subDealerStock = $this->common->storeInModel('App\Models\DealerStock', $request->all());

                $subDealerStockHistoris = [
                    'dealer_stock_id' => $subDealerStock->id,
                    'date_time' => $subDealerStock->date,
                    'quantity_pisces' => $subDealerStock->quantity_pisces,
                    'type' => $subDealerStock->type,
                ];

                $this->common->storeInModel('App\Models\DealerStockHistory', $subDealerStockHistoris);

                DB::commit();
                $message = "Stock Created Successfully";
                return $this->successResponse(Response::HTTP_CREATED, $message, $subDealerStock->toArray());

            }catch(QueryException $e){
                DB::rollBack();
                return $this->errorResponse(Response::HTTP_INTERNAL_SERVER_ERROR, $e->getMessage(), []);
            }
        }

    }


    public function update(DealerStockRequest $request, $id){

        if($request->isMethod('post')){
            DB::beginTransaction();
            try{
                $dealerStock = $this->common->findOnModel('App\Models\DealerStock', 'id', $id);
                if(!$dealerStock){
                    return $this->errorResponse(404, 'Not FOund Your Targeted Data');
                }

                $dealerStock = $this->common->update($request->all(), $dealerStock);

                $dealerStockHistories = $this->common->findOnModel('App\Models\DealerStockHistory', 'dealer_stock_id', $id);
                $storeDealerStockHistoris = [
                    // 'sub_dealer_stock_id' => $subDealerStock->id,
                    'date_time' => $request->date,
                    'quantity_pisces' => $request->quantity_pisces,
                    'type' => $request->type,
                ];

                $this->common->update($storeDealerStockHistoris, $dealerStockHistories);

                DB::commit();
                $message = 'Stock Updated Successfully';
                return $this->successResponse(200,$message,$dealerStock->toArray());

            }catch(QueryException $e){
                DB::rollBack();
                return $this->errorResponse(Response::HTTP_INTERNAL_SERVER_ERROR, $e->getMessage(), []);
            }

        }
    }

    public function destroy($id){
        DB::beginTransaction(); 
        try{
            $subDealerOrder = $this->common->findOnModel('App\Models\DealerStock', 'id', $id);

            if($subDealerOrder){
                DB::table('dealer_stock_histories')->where('dealer_stock_id', $id)->delete();
                $subDealerOrder->delete();
            }else{
                return $this->errorResponse(404, 'Not Found Your Targeted Data', []);
            }

            DB::commit();
            $message = "Stock Deleted Successfully";
            return $this->successResponse(200, $message, []);
           
        }catch(QueryException $e){
            DB::rollBack();
            return $this->errorResponse(Response::HTTP_INTERNAL_SERVER_ERROR, $e->getMessage(), []);
        }
    }

}
