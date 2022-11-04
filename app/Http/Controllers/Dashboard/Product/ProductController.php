<?php

namespace App\Http\Controllers\Dashboard\Product;

use Illuminate\Http\Request;
use App\Http\Traits\ResponseTrait;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProductRequest;
use App\Http\Traits\ImageManageTrait;
use App\Repositories\Common\CommonRepository;
use Illuminate\Database\QueryException;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    use ResponseTrait;
    use ImageManageTrait;

    protected $common;

    public function __construct(CommonRepository $commonRepository)
    {
        $this->common = $commonRepository;
    }


    public function store(ProductRequest $request){

        //  $d = $this->common->findOnTable("products", 'id',9);

        // // //  $product->properties
        // $ss = json_decode($d->offer_ids);

        // // return $ss;
        
        // foreach($ss as $key => $id){
        //     return $id.'----'.$key;
        // }
        // //  return $d->offer_id;

        if($request->isMethod('post')){
            DB::beginTransaction();
            try{
                // $request['offer_ids'] = json_encode($request->offer_ids);
                // return $request;

                $product = $this->common->storeInModel('App\Models\Product', $request->all());
                
                if ($request->hasFile('image')) {
                    $this->imageUpload($product->id, 'image', 'product', 'products', $request->name, $request->image);
                }

                DB::commit();
                $massage = 'Product Created Successfully';
                return $this->successResponse(Response::HTTP_CREATED, $massage, $product->toArray());
            }catch(QueryException $e){
                DB::rollBack();
                return $this->errorResponse(Response::HTTP_INTERNAL_SERVER_ERROR, $e->getMessage(), []);
            }
        }

    }


    public function update(ProductRequest $request, $id){

        if($request->isMethod('post')){

            DB::beginTransaction();
            try{
                $product = $this->common->findOnModel('App\Models\Product', 'id', $id);
                if (!$product){
                    return $this->errorResponse(404, 'Not Found Your Targeted Data', []);
                }

                $product = $this->common->update($request->all(), $product);

                if ($request->hasFile('image')) {
                    $this->imageUploadForUpdate($product->id, 'image', 'product', 'products', $request->name, $request->image);
                }

                DB::commit();
                $message = "Product Updated Successfully";
                return $this->successResponse(Response::HTTP_OK, $message, $product->toArray());

            } catch (QueryException $e) {
                DB::rollBack();
                return $this->errorResponse(Response::HTTP_INTERNAL_SERVER_ERROR, $e->getMessage(), []);
            }
        }

    }


}
