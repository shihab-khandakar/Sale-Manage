<?php

namespace App\Http\Controllers\Dashboard\Company_Warehouse;

use Carbon\Carbon;
use App\Models\Brand;
use App\Models\Product;
use App\Models\Category;
use App\Models\CompanyStock;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\CompanyInventory;
use App\Http\Traits\ResponseTrait;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Database\QueryException;
use App\Http\Requests\CompanyStockRequest;
use App\Repositories\Common\CommonRepository;
use Hamcrest\Arrays\IsArray;

class CompanyStockController extends Controller
{

    use ResponseTrait;
    protected $common;

    public function __construct(CommonRepository $commonRepository)
    {
        $this->common = $commonRepository;
    }

    public function store(CompanyStockRequest $request)
    {

        if ($request->isMethod('post')) {
            DB::beginTransaction();
            try {
                if (count($request->product_id) > 0) {

                    foreach ($request->product_id as $key => $product) {
                        $stock = CompanyStock::where('product_id', $product)->latest()->first();

                        if ($stock) {
                            $previousQuantity = $stock->quantity;
                            $stock->update([
                                'quantity' => $request->quantity[$key] + $previousQuantity,
                            ]);
                        } else {
                            $stock = CompanyStock::create([
                                'product_id'  => $product,
                                'warehouse_id' => $request->warehouse_id,
                                'category_id' => $request->category_id[$key],
                                'brand_id' => $request->brand_id[$key],
                                'quantity' => $request->quantity[$key],
                                'date' => $request->date,
                                'stock_by' => auth()->user()->id,
                            ]);
                        }

                        // query
                        $product = Product::where('id', $product)->select('name', 'retailer_sale_price')->first();
                        $category_name = Category::where('id', $request->category_id[$key])->first()->name;
                        $brand_name = Brand::where('id', $request->brand_id[$key])->first()->name;

                        CompanyInventory::create([
                            'stock_id' => $stock->id,
                            'user_id'  => Auth()->user()->id,
                            'product_name'  => $product['name'],
                            'category_name' => $category_name,
                            'brand_name' => $brand_name,
                            'old_quantity' => 0,
                            'add_or_less' => $request->quantity[$key],
                            'now_quantity' => $request->quantity[$key],
                            'type'   => 'in',
                            'date' => $request->date,
                            'price'  => $product['retailer_sale_price'],
                            'amount' => $request->quantity[$key] * $product['retailer_sale_price'],
                        ]);
                    }
                }

                DB::commit();
                $message = "Stock Created Successfully";
                return $this->successResponse(Response::HTTP_CREATED, $message, $stock->toArray());
            } catch (QueryException $e) {
                DB::rollBack();
                return $this->errorResponse(Response::HTTP_INTERNAL_SERVER_ERROR, $e->getMessage(), []);
            }
        }

    }


    public function update(CompanyStockRequest $request, $id)
    {

        if ($request->isMethod('post')) {
            DB::beginTransaction();
            try {

                $stock = CompanyStock::findOrFail($id);

                $product       = Product::where('id', $request->product_id)->select('name', 'retailer_sale_price')->first();
                $category_name = Category::where('id', $request->category_id)->first()->name;
                $brand_name = Brand::where('id', $request->brand_id)->first()->name;

                // return $request->quantity;

                if ($stock->quantity < $request->quantity) {
                    //$add_quantity = $request->quantity - $stock->quantity;
                    $add_quantity = (int)$request->quantity - (int)$stock->quantity;
                    // return $add_quantity;
                    CompanyInventory::create([
                        'stock_id' => $stock->id,
                        'user_id'  => Auth()->user()->id,
                        'product_name'  => $product['name'],
                        'category_name' => $category_name,
                        'brand_name' => $brand_name,
                        'old_quantity' => $stock->quantity,
                        'add_or_less' => $add_quantity,
                        'now_quantity' => $request->quantity,
                        'type'   => 'in',
                        'date' => $request->date,
                        'price'  => $product['retailer_sale_price'],
                        'amount' => (int)$request->quantity * (int)$product['retailer_sale_price'],
                    ]);
                    // return 'hisoer';
                } else {
                    // return 'jsojif';
                    if ($request->quantity) {
                        $inventory = CompanyInventory::where('stock_id', $stock->id)->latest()->first();
                        $less_quantity =  (int)$stock->quantity - (int)$request->quantity;
                        // return $less_quantity;

                        $inventory->update([
                            'stock_id' => $stock->id,
                            'user_id'  => Auth()->user()->id,
                            'product_name'  => $product['name'],
                            'category_name' => $category_name,
                            'brand_name' => $brand_name,
                            'old_quantity' => $stock->quantity,
                            'add_or_less' => $less_quantity,
                            'now_quantity' => $request->quantity,
                            'type'   => 'in',
                            'date' => $request->date,
                            'price'  => $product['retailer_sale_price'],
                            'amount' => (int)$request->quantity * (int)$product['retailer_sale_price'],
                        ]);
                    }
                }

                $stock->product_id = $request->product_id;
                $stock->category_id = $request->category_id;
                $stock->brand_id = $request->brand_id;
                $stock->quantity = $request->quantity;

                $stock->save();

                DB::commit();

                return response()->json([
                    'message' => 'Stock updated successful'
                ], Response::HTTP_CREATED);
            } catch (QueryException $e) {
                DB::rollBack();
                return $this->errorResponse(Response::HTTP_INTERNAL_SERVER_ERROR, $e->getMessage(), []);
            }
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $subDealerOrder = $this->common->findOnModel('App\Models\CompanyStock', 'id', $id);

            if ($subDealerOrder) {
                DB::table('company_inventories')->where('stock_id', $id)->delete();
                $subDealerOrder->delete();
            } else {
                return $this->errorResponse(404, 'Not Found Your Targeted Data', []);
            }

            DB::commit();
            $message = "Order Deleted Successfully";
            return $this->successResponse(200, $message, []);
        } catch (QueryException $e) {
            DB::rollBack();
            return $this->errorResponse(Response::HTTP_INTERNAL_SERVER_ERROR, $e->getMessage(), []);
        }
    }


    public function stockIndex(Request $request, $id){
        // return "ksahrie";
        $perPage = $request->perPage ?? 10;
        $page = $request->page ?? 1;
        $data = [];

        DB::beginTransaction();
        try {
        $query = DB::table('company_stocks')
            ->select(
                'warehouses.name as warehouse_name',
                'users.name as user_name',
                'products.name as product_name',
                'brands.name as brand_name',
                'categories.name as category_name',
                'company_stocks.quantity as quantity',
                'company_stocks.date as date',
            )
            ->leftJoin('users', function ($join){
                $join->on('users.id', '=', 'company_stocks.stock_by');
            })
            ->leftJoin('warehouses', function ($join){
                $join->on('warehouses.id', '=', 'company_stocks.warehouse_id');
            })
            ->leftJoin('products', function ($join){
                $join->on('products.id', '=', 'company_stocks.product_id');
            })
            ->leftJoin('brands', function ($join){
                $join->on('brands.id', '=', 'company_stocks.brand_id');
            })
            ->leftJoin('categories', function ($join){
                $join->on('categories.id', '=', 'company_stocks.category_id');
            })
            ->where('company_stocks.warehouse_id', $id);

            
            $total = $query->count();

            $result = $query->offset(($page - 1) * $perPage)->limit($perPage)->latest('company_stocks.created_at')->get();

            $data = [
                'data' => $result,
                'total' => $total,
                'page' => $page,
                'perPage' => $perPage,
            ];

            return $this->successResponse(200, '', $data);

        } catch (QueryException $e) {
            DB::rollBack();
            return $this->errorResponse(Response::HTTP_INTERNAL_SERVER_ERROR, $e->getMessage(), []);
        }
    }


}
