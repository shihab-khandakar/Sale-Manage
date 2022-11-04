<?php

namespace App\Http\Controllers\Dashboard\Company_Warehouse;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Traits\ResponseTrait;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Database\QueryException;
use App\Http\Requests\SubDealerOrderRequest;
use App\Repositories\Common\CommonRepository;

class SubDealerOrderController extends Controller
{
    
    use ResponseTrait;
    protected $common;

   public function __construct(CommonRepository $commonRepository)
   {
     $this->common = $commonRepository;
   }
    
    public function store(SubDealerOrderRequest $request)
    {
        
        if($request->isMethod('post')){ 
            // return $request;
            DB::beginTransaction();
            try{
                $allSubTotalBill = 0;

                foreach($request->product_id as $key => $product){
                    $product = $this->common->findOnModel('App\Models\Product','id',$product);
                    $subTotal = (Double)$product->retailer_sale_price * (Double)$request->quantity[$key];
                    if($subTotal === (Double)$request->sub_total[$key]){
                         $allSubTotalBill+= $subTotal; 
                    }else{
                        return $this->warningResponse(406, 'Sub total calculation is wrong');
                    }
                }

                if($allSubTotalBill == $request->without_commission_bill){
                    
                }else{
                    return $this->warningResponse(406, 'Without Commission Amount calculation is wrong');
                }

                if($request->commission_type == 'taka'){
                    $commissionAmount = $request->commission_value;
                    $commissionTaka =  (Double)$allSubTotalBill - (Double)$commissionAmount;
                    $netAmount = $commissionTaka;
                }elseif($request->commission_type == 'percentage'){
                    $commissionAmount = ((Double)$allSubTotalBill * (Double)$request->commission_value) / 100;
                    $netAmount = (Double)$allSubTotalBill - $commissionAmount;
                }else{
                    $netAmount = (Double)$allSubTotalBill;
                }

                if($commissionAmount == $request->commission_amount){

                }else{
                    return $this->warningResponse(406, 'Commission Amount calculation is wrong');
                }

                if($netAmount == $request->total_bill){
                    
                }else{
                    return $this->warningResponse(406, 'Total calculation is wrong');
                }

                $orderCode = $this->common->getOrderCode('App\Models\SubDealerOrder','order_code');
                $dealerOrder = [
                    'sub_dealer_id' => $request->dealer_id,
                    'approval_id' => json_encode($request->approval_id),
                    'offer_id' => $request->offer_id,
                    'side_party_information_id' => $request->side_party_information_id,
                    'transport_id' => $request->transport_id,
                    'direct_receive' => $request->direct_receive,
                    'd_code' => $request->d_code,
                    'order_code' => $orderCode + 1,
                    'date' => $request->date,
                    'payment_status' => $request->payment_status,
                    'order_status' => $request->order_status,
                    'commission_type' => $request->commission_type,
                    'commission_value' => $request->commission_value,
                    'commission_amount' => $request->commission_amount,
                    'without_commission_bill' => $allSubTotalBill,
                    'total_bill' => $request->total_bill,
                    'provided_amount' => $request->provided_amount,
                    'providable_amount' => $request->providable_amount,
                    'distribute' => $request->distribute,
                ];
                $dealerOrder = $this->common->storeInModel('App\Models\SubDealerOrder', $dealerOrder);
                
                foreach($request->product_id as $key => $product){

                   $dealerOrderDetails =[
                        'sub_dealer_order_id' => $dealerOrder->id,
                        'product_id' => $request->product_id[$key],
                        'category_id' => $request->category_id[$key],
                        'brand_id' => $request->brand_id[$key],
                        'quantity' => $request->quantity[$key],
                        'sub_total' => $request->sub_total[$key],
                        'gift_item' => $request->gift_item[$key],
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now(),
                   ]; 
                //    return $dealerOrderDetails;
                   $this->common->storeInModel('App\Models\SubDealerOrderDetail', $dealerOrderDetails);
                }

                DB::commit();
                $message = "Order Created Successfully";
                return $this->successResponse(Response::HTTP_CREATED, $message, $dealerOrder->toArray());

            }catch(QueryException $e){
                DB::rollBack();
                return $this->errorResponse(Response::HTTP_INTERNAL_SERVER_ERROR, $e->getMessage(), []);
            }
        }

    }

    public function update(SubDealerOrderRequest $request, $id){

        if($request->isMethod('post')){
            DB::beginTransaction();
            try{
                $companyOrder = $this->common->findOnModel('App\Models\SubDealerOrder', 'id', $id);
                if(!$companyOrder){
                    return $this->warningResponse(404, 'Not Found Your Targeted Data');
                }

                $allSubTotalBill = 0;
                $companyOrderDetails = $this->common->getAllDataWithoutPaginate('sub_dealer_order_details', 'sub_dealer_order_id', $id);
                foreach($companyOrderDetails as $key => $product){

                    $product = $this->common->findOnTable('products','id',$product->product_id);
                    $subTotal = (Double)$product->retailer_sale_price * (Double)$request->quantity[$key];
                    if($subTotal === (Double)$request->sub_total[$key]){
                         $allSubTotalBill+= $subTotal; 
                    }else{
                        return $this->warningResponse(406, 'Sub total calculation is wrong');

                    }
                }

                if($allSubTotalBill == $request->without_commission_bill){
                    
                }else{
                    return $this->warningResponse(406, 'Without Commission Amount calculation is wrong');
                }

                if($request->commission_type == 'taka'){
                    $commissionAmount = $request->commission_value;
                    $commissionTaka =  (Double)$allSubTotalBill - (Double)$commissionAmount;
                    $netAmount = $commissionTaka;
                }elseif($request->commission_type == 'percentage'){
                    $commissionAmount = ((Double)$allSubTotalBill * (Double)$request->commission_value) / 100;
                    $netAmount = (Double)$allSubTotalBill - $commissionAmount;
                }else{
                    $netAmount = (Double)$allSubTotalBill;
                }

                if($commissionAmount == $request->commission_amount){

                }else{
                    return $this->warningResponse(406, 'Commission Amount calculation is wrong');
                }

                if($netAmount == $request->total_bill){
                    
                }else{
                    return $this->warningResponse(406, 'Total calculation is wrong');
                }

                $orderCode = $this->common->getOrderCode('App\Models\SubDealerOrder','order_code');
                $companyOrderStore = [
                    'sub_dealer_id' => $request->dealer_id,
                    'approval_id' => json_encode($request->approval_id),
                    'offer_id' => $request->offer_id,
                    'side_party_information_id' => $request->side_party_information_id,
                    'transport_id' => $request->transport_id,
                    'direct_receive' => $request->direct_receive,
                    'd_code' => $request->d_code,
                    'order_code' => $orderCode + 1,
                    'date' => $request->date,
                    'payment_status' => $request->payment_status,
                    'order_status' => $request->order_status,
                    'commission_type' => $request->commission_type,
                    'commission_value' => $request->commission_value,
                    'commission_amount' => $request->commission_amount,
                    'without_commission_bill' => $allSubTotalBill,
                    'total_bill' => $request->total_bill,
                    'provided_amount' => $request->provided_amount,
                    'providable_amount' => $request->providable_amount,
                    'distribute' => $request->distribute,
                ];
                $companyOrder = $this->common->update($companyOrderStore, $companyOrder);
                
                $companyOrderDetails = $this->common->getAllDataWithoutPaginate('sub_dealer_order_details', 'sub_dealer_order_id', $id);
                foreach($companyOrderDetails as $key => $product){

                   $companyOrderDetailsStore =[
                        'sub_dealer_order_id' => $companyOrder->id,
                        'product_id' => $request->product_id[$key],
                        'category_id' => $request->category_id[$key],
                        'brand_id' => $request->brand_id[$key],
                        'quantity' => $request->quantity[$key],
                        'sub_total' => $request->sub_total[$key],
                        'gift_item' => $request->gift_item[$key],
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now(),
                   ]; 
                $this->common->dbTableUpdateMethod('sub_dealer_order_details', 'sub_dealer_order_id', $id, $companyOrderDetailsStore);
            }

                DB::commit();
                $message = "Order Updated Successfully";
                return $this->successResponse(200, $message, $companyOrder->toArray());

            }catch(QueryException $e){
                DB::rollBack();
                return $this->errorResponse(Response::HTTP_INTERNAL_SERVER_ERROR, $e->getMessage(), []);
            }
        }

    }

    public function destroy($id){

        DB::beginTransaction(); 
        try{
            $subDealerOrder = $this->common->findOnModel('App\Models\SubDealerOrder', 'id', $id);

            if($subDealerOrder){
                DB::table('sub_dealer_order_details')->where('sub_dealer_order_id', $id)->delete();
                $subDealerOrder->delete();
            }else{
                return $this->errorResponse(404, 'Not Found Your Targeted Data', []);
            }

            DB::commit();
            $message = "Order Deleted Successfully";
            return $this->successResponse(200, $message, []);
           
        }catch(QueryException $e){
            DB::rollBack();
            return $this->errorResponse(Response::HTTP_INTERNAL_SERVER_ERROR, $e->getMessage(), []);
        }

    }

}
