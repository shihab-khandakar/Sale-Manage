<?php

namespace App\Http\Controllers\Dashboard\CategoryBrand;

use App\Http\Controllers\Controller;
use App\Http\Requests\BrandRequest;
use App\Http\Traits\ImageManageTrait;
use App\Http\Traits\ResponseTrait;
use App\Repositories\Common\CommonRepository;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;


class BrandController extends Controller
{
    use ResponseTrait;
    use ImageManageTrait;

    protected $common;

    public function __construct(CommonRepository $commonRepository)
    {
        $this->common = $commonRepository;
    }

    public function store(BrandRequest $request)
    {
        if ($request->isMethod('post')) {
            DB::beginTransaction();

            try {
                $onlyGo = $request->only('name', 'description', 'status');
                $brand = $this->common->storeInModel('App\Models\Brand',$onlyGo);

                if ($request->hasFile('image')) {
                    $this->imageUpload($brand->id, 'image', 'brand', 'brands', $request->name, $request->image);
                }

                DB::commit();
                $message = "Brand store successful";
                return $this->successResponse(Response::HTTP_CREATED, $message, $brand->toArray());

            } catch (QueryException $e) {
                DB::rollBack();
                return $this->errorResponse(Response::HTTP_INTERNAL_SERVER_ERROR, $e->getMessage(), []);
            }
        }else{
            return $this->errorResponse(405, 'Method Not Supported', []);
        }
    }

    public function update(BrandRequest $request, $id)
    {
        if ($request->isMethod("post")) {
            DB::beginTransaction();
            try {
                $brand = $this->common->findOnModel('App\Models\Brand', 'id', $id);

                if (!$brand){
                    return $this->errorResponse(404, 'Not Found Your Targeted Data', []);
                }

                $brand = $this->common->update($request->all(), $brand);
                if ($request->hasFile('image')) {
                    $this->imageUploadForUpdate($brand->id, 'image', 'image', 'brands', $request->name, $request->image);
                }

                DB::commit();
                $message = "Brand update successful";
                return $this->successResponse(Response::HTTP_OK, $message, $brand->toArray());

            } catch (QueryException $e) {
                DB::rollBack();
                return $this->errorResponse(Response::HTTP_INTERNAL_SERVER_ERROR, $e->getMessage(), []);
            }
        }else{
            return $this->errorResponse(405, 'Method Not Supported', []);
        }
    }
}
