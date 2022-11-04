<?php

namespace App\Http\Controllers\Dashboard\Company_warehouse;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Traits\ResponseTrait;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Database\QueryException;
use App\Http\Requests\CompanyOrderRequest;
use App\Repositories\Common\CommonRepository;
use PhpParser\Node\Expr\Cast\Double;

class CompanyOrderController extends Controller
{
    
    use ResponseTrait;
    protected $common;

    public function __construct(CommonRepository $commonRepository)
    {
        $this->common = $commonRepository;
    }

    public function store(CompanyOrderRequest $request)
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

                // return $allSubTotalBill;
                if($allSubTotalBill == $request->total_bill){
                    
                }else{
                    return $this->warningResponse(406, 'Total bill calculation is wrong');
                }

                if($request->commission){
                    $commissionAmount = ((Double)$allSubTotalBill * (Double)$request->commission) / 100;
                    $netAmount = (Double)$allSubTotalBill - $commissionAmount;
                }else{
                    $netAmount = (Double)$allSubTotalBill;
                }

                // return $netAmount;
                if($commissionAmount == $request->commission_amount){

                }else{
                    return $this->warningResponse(406, 'Commission Amount calculation is wrong');
                }

                if($netAmount == $request->net_bill){
                    
                }else{
                    return $this->warningResponse(406, 'Net bill calculation is wrong');
                }

                if($netAmount == $request->providable_amount){
                    
                }else{
                    return $this->warningResponse(406, 'Providable Amount calculation is wrong');
                }

                $orderCode = $this->common->getOrderCode('App\Models\CompanyOrder','order_code');
                $companyOrder = [
                    'dealer_id' => Auth()->user()->id,
                    'approval_id' => json_encode($request->approval_id),
                    'side_party_information_id' => $request->side_party_information_id,
                    'transport_id' => $request->transport_id,
                    'direct_receive' => $request->direct_receive,
                    'd_code' => $request->d_code,
                    'order_code' => $orderCode + 1,
                    'date' => $request->date,
                    'payment_status' => $request->payment_status,
                    'order_status' => $request->order_status,
                    'total_bill' => $request->total_bill,
                    'commission' => $request->commission,
                    'commission_amount' => $request->commission_amount,
                    'net_bill' => $request->net_bill,
                    'provided_amount' => $request->provided_amount,
                    'providable_amount' => $request->providable_amount,
                    'distribute' => $request->distribute,
                ];
                $companyOrder = $this->common->storeInModel('App\Models\CompanyOrder', $companyOrder);
                
                foreach($request->product_id as $key => $product){

                   $companyOrderDetails =[
                        'company_order_id' => $companyOrder->id,
                        'product_id' => $request->product_id[$key],
                        'category_id' => $request->category_id[$key],
                        'brand_id' => $request->brand_id[$key],
                        'quantity' => $request->quantity[$key],
                        'sub_total' => $request->sub_total[$key],
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now(),
                   ]; 
                //    return $companyOrderDetails;
                   $this->common->storeInModel('App\Models\CompanyOrderDetail', $companyOrderDetails);
                }

                DB::commit();
                $message = "Order Created Successfully";
                return $this->successResponse(Response::HTTP_CREATED, $message, $companyOrder->toArray());

            }catch(QueryException $e){
                DB::rollBack();
                return $this->errorResponse(Response::HTTP_INTERNAL_SERVER_ERROR, $e->getMessage(), []);
            }
        }

    }


    public function update(CompanyOrderRequest $request, $id)
    {

        if($request->isMethod('post')){
            DB::beginTransaction();
            try{
                $companyOrder = $this->common->findOnModel('App\Models\CompanyOrder', 'id', $id);
                if(!$companyOrder){
                    return $this->warningResponse(404, 'Not Found Your Targeted Data');
                }

                $allSubTotalBill = 0;
                $companyOrderDetails = $this->common->getAllDataWithoutPaginate('company_order_details', 'company_order_id', $id);
                
                foreach($companyOrderDetails as $key => $product){
                
                    $product = $this->common->findOnTable('products','id', $request->product_id[$key]);
                    $subTotal = (Double)$product->retailer_sale_price * (Double)$request->quantity[$key];
                    if($subTotal === (Double)$request->sub_total[$key]){
                         $allSubTotalBill+= $subTotal;
                        //  return $allSubTotalBill; 
                    }else{
                        return $this->warningResponse(406, 'Sub total calculation is wrong');

                    }
                }

                if($allSubTotalBill == $request->total_bill){
                    
                }else{
                    return $this->warningResponse(406, 'Total bill calculation is wrong');
                }

                if($request->commission){
                    $commissionAmount = ((Double)$allSubTotalBill * (Double)$request->commission) / 100;
                    $netAmount = (Double)$allSubTotalBill - $commissionAmount;
                }else{
                    $netAmount = (Double)$allSubTotalBill;
                }

                // return $netAmount;
                if($commissionAmount == $request->commission_amount){

                }else{
                    return $this->warningResponse(406, 'Commission Amount calculation is wrong');
                }

                if($netAmount == $request->net_bill){
                    
                }else{
                    return $this->warningResponse(406, 'Net bill calculation is wrong');
                }

                if($netAmount == $request->providable_amount){
                    
                }else{
                    return $this->warningResponse(406, 'Providable Amount calculation is wrong');
                }

                $orderCode = $this->common->getOrderCode('App\Models\CompanyOrder','order_code');
                $companyOrderStore = [
                    'dealer_id' => Auth()->user()->id,
                    'approval_id' => json_encode($request->approval_id),
                    'side_party_information_id' => $request->side_party_information_id,
                    'transport_id' => $request->transport_id,
                    'direct_receive' => $request->direct_receive,
                    'd_code' => $request->d_code,
                    'order_code' => $orderCode + 1,
                    'date' => $request->date,
                    'payment_status' => $request->payment_status,
                    'order_status' => $request->order_status,
                    'total_bill' => $request->total_bill,
                    'commission' => $request->commission,
                    'commission_amount' => $request->commission_amount,
                    'net_bill' => $request->net_bill,
                    'provided_amount' => $request->provided_amount,
                    'providable_amount' => $request->providable_amount,
                    'distribute' => $request->distribute,
                ];
                $companyOrder = $this->common->update($companyOrderStore, $companyOrder);
                
                $companyOrderDetails = $this->common->getAllDataWithoutPaginate('company_order_details', 'company_order_id', $id);
                foreach($companyOrderDetails as $key => $product){

                   $companyOrderDetailsStore =[
                        'company_order_id' => $companyOrder->id,
                        'product_id' => $request->product_id[$key],
                        'category_id' => $request->category_id[$key],
                        'brand_id' => $request->brand_id[$key],
                        'quantity' => $request->quantity[$key],
                        'sub_total' => $request->sub_total[$key],
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now(),
                   ]; 
                $this->common->dbTableUpdateMethod('company_order_details', 'company_order_id', $id, $companyOrderDetailsStore);
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
            $companyOrder = $this->common->findOnModel('App\Models\CompanyOrder', 'id', $id);

            if($companyOrder){
                DB::table('company_order_details')->where('company_order_id', $id)->delete();
                $companyOrder->delete();
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
