<?php

namespace App\Http\Controllers\Register;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Traits\ResponseTrait;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\RegisterRequest;
use Illuminate\Database\QueryException;
use App\Repositories\Common\CommonRepository;
use Illuminate\Http\Client\Request as ClientRequest;

class RegisterController extends Controller
{
    use ResponseTrait;

    protected $common;

    public function __construct(CommonRepository $commonRepository){

        $this->common = $commonRepository;
    }

    public function register(RegisterRequest $request){

        if($request->isMethod('post')){
            DB::beginTransaction();
            try{
                $onlyGo = $request->only('name','shope_name', 'email', 'role', 'role_id', 'hierarchy_id', 'code', 'phone', 'position', 'password');
                $onlyGo['password'] = Hash::make($request->password);
                $user = $this->common->storeInModel('App\Models\User', $onlyGo);

                DB::commit();
                $message = "User Created Successfully";
                return $this->successResponse(Response::HTTP_CREATED, $message, $user->toArray());

            }catch(QueryException $e){
                DB::rollBack();
                return $this->errorResponse(Response::HTTP_INTERNAL_SERVER_ERROR, $e->getMessage(), []);
            }
        }
    }

    public function update(Request $request, $id){

        if($request->isMethod('patch')){

            DB::beginTransaction();
            try{
                $user = $this->common->findOnModel('App\Models\User', 'id', $id);
                if($request['password']){
                    $request['password'] = Hash::make($request->password);
                }
                $user = $this->common->update($request->all(), $user);

                DB::commit();
                $message = "User Updated successfully";
                return $this->successResponse(Response::HTTP_OK, $message, $user);

            } catch (QueryException $e) {
                DB::rollBack();
                return $this->errorResponse(Response::HTTP_INTERNAL_SERVER_ERROR, $e->getMessage(), []);
            }
        }

    }

    public function userDelete($id){
        DB::beginTransaction();
        try{
            $getProfileData = $this->common->findOnModel('App\Models\User\Profile', 'user_id', $id);
            $userData = $this->common->findOnModel('App\Models\User', 'id', $id);

            if($getProfileData){
                $message = "You Cannot Delete This User";
                return $this->warningResponse(406,$message);
            }else{
                $userData->delete();
            }

            DB::commit();
            $message = "User Deleted successfully";
            return $this->successResponse(Response::HTTP_OK, $message, []);
        } catch (QueryException $e) {
            DB::rollBack();
            return $this->errorResponse(Response::HTTP_INTERNAL_SERVER_ERROR, $e->getMessage(), []);
        }

    }


}
