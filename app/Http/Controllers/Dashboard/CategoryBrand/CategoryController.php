<?php

namespace App\Http\Controllers\Dashboard\CategoryBrand;

use Illuminate\Http\Request;

use Illuminate\Http\Response;
use App\Http\Traits\ResponseTrait;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Traits\ImageManageTrait;
use App\Http\Requests\CategoryRequest;
use Illuminate\Database\QueryException;
use App\Repositories\Common\CommonRepository;


class CategoryController extends Controller
{
    use ResponseTrait;
    use ImageManageTrait;

    protected $common;

    public function __construct(CommonRepository $commonRepository)
    {
        $this->common = $commonRepository;
    }

    public function store(CategoryRequest $request)
    {
        if ($request->isMethod('post')) {
            DB::beginTransaction();

            try {
                $onlyGo = $request->only('name', 'description', 'status');
                $category = $this->common->storeInModel('App\Models\Category', $onlyGo);

                if ($request->hasFile('image')) {
                    $this->imageUpload($category->id, 'image', 'category', 'categories', $request->name, $request->image);
                }

                DB::commit();
                $message = "Category store successful";
                return $this->successResponse(Response::HTTP_CREATED, $message, $category->toArray());

            } catch (QueryException $e) {
                DB::rollBack();
                return $this->errorResponse(Response::HTTP_INTERNAL_SERVER_ERROR, $e->getMessage(), []);
            }
        }else{
            return $this->errorResponse(405, 'Method Not Supported', []);
        }
    }


    public function update(CategoryRequest $request, $id)
    {
        if ($request->isMethod("patch") || $request->isMethod("post")) {
            DB::beginTransaction();
            try {
                $category = $this->common->findOnModel('App\Models\Category', 'id', $id);

                if (!$category) {
                    return $this->errorResponse(404, 'Not Found Your Targeted Data', []);
                }

                $onlyGo = $request->only('name', 'description', 'status');
                $category = $this->common->update($onlyGo, $category);

                if ($request->hasFile('image')) {
                    $this->imageUploadForUpdate($category->id, 'image', 'category', 'categories', $request->name, $request->image);
                }

                DB::commit();
                $message = "Category update successful";
                return $this->successResponse(Response::HTTP_OK, $message, $category->toArray());

            } catch (QueryException $e) {
                DB::rollBack();
                return $this->errorResponse(Response::HTTP_INTERNAL_SERVER_ERROR, $e->getMessage(), []);
            }
        }else{
            return $this->errorResponse(405, 'Method Not Supported', []);
        }
    }
}
