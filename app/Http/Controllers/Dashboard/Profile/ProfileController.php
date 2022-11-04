<?php

namespace App\Http\Controllers\Dashboard\Profile;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Traits\ResponseTrait;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProfileRequest;
use App\Http\Traits\ImageManageTrait;
use Illuminate\Database\QueryException;
use App\Repositories\Common\CommonRepository;

class ProfileController extends Controller
{
        use ResponseTrait;
        use ImageManageTrait;

        protected $common;

        public function __construct(CommonRepository $commonRepository)
        {
            $this->common = $commonRepository;
        }
    
    public function store(ProfileRequest $request){

        if($request->isMethod('post')){
            DB::beginTransaction();
            try{
                $profile = $this->common->storeInModel('App\Models\User\Profile', $request->all());

                if ($request->hasFile('image')) {
                    $this->imageUpload($profile->id, 'image', 'profile', 'profiles', $request->f_name, $request->image);
                }
                if ($request->hasFile('nid_font')) {
                    $this->imageUpload($profile->id, 'image', 'nid_font', 'profiles', $request->f_name, $request->nid_font);
                }
                if ($request->hasFile('nid_back')) {
                    $this->imageUpload($profile->id, 'image', 'nid_back', 'profiles', $request->f_name, $request->nid_back);
                }
                if ($request->hasFile('father_nid_font')) {
                    $this->imageUpload($profile->id, 'image', 'father_nid_font', 'profiles', $request->f_name, $request->father_nid_font);
                }
                if ($request->hasFile('father_nid_back')) {
                    $this->imageUpload($profile->id, 'image', 'father_nid_back', 'profiles', $request->f_name, $request->father_nid_back);
                }

                DB::commit();
                
                $message = "Profile Created Successfully";
                return $this->successResponse(Response::HTTP_CREATED, $message, $profile->toArray());

            }catch(QueryException $e){
                DB::rollBack();
                return $this->errorResponse(Response::HTTP_INTERNAL_SERVER_ERROR, $e->getMessage(), []);
            }
        }

    }

    public function update(ProfileRequest $request, $id){

        if($request->isMethod('post')){

            DB::beginTransaction();
            try{
                $profile = $this->common->findOnModel('App\Models\User\Profile', 'id', $id);
                if (!$profile){
                    return $this->errorResponse(404, 'Not Found Your Targeted Data', []);
                }
                
                $profile = $this->common->update($request->all(), $profile);

                if ($request->hasFile('image')) {
                    $this->imageUploadForUpdate($profile->id, 'image', 'image', 'profiles', $request->f_name, $request->image);
                }
                if ($request->hasFile('nid_font')) {
                    $this->imageUploadForUpdate($profile->id, 'image', 'nid_font', 'profiles', $request->f_name, $request->nid_font);
                }
                if ($request->hasFile('nid_back')) {
                    $this->imageUploadForUpdate($profile->id, 'image', 'nid_back', 'profiles', $request->f_name, $request->nid_back);
                }
                if ($request->hasFile('father_nid_font')) {
                    $this->imageUploadForUpdate($profile->id, 'image', 'father_nid_font', 'profiles', $request->f_name, $request->father_nid_font);
                }
                if ($request->hasFile('father_nid_back')) {
                    $this->imageUploadForUpdate($profile->id, 'image', 'father_nid_back', 'profiles', $request->f_name, $request->father_nid_back);
                }

                DB::commit();
                $message = "Profile Updated Successfully";
                return $this->successResponse(Response::HTTP_OK, $message, $profile->toArray());

            } catch (QueryException $e) {
                DB::rollBack();
                return $this->errorResponse(Response::HTTP_INTERNAL_SERVER_ERROR, $e->getMessage(), []);
            }
        }

    }


}
