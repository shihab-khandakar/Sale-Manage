<?php
namespace App\Http\Controllers\Dashboard\User;
use App\Http\Controllers\Controller;
use App\Http\Requests\AccountRequest;
use App\Http\Traits\ResponseTrait;
use App\Repositories\Common\CommonRepository;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class AccountController extends Controller{
    use ResponseTrait;

    protected $common;
    public function __construct(CommonRepository $commonRepository)
    {
        $this->common = $commonRepository;
    }


    public function store(AccountRequest $request)
    {
        if ($request->isMethod('post')) {
            DB::beginTransaction();

            try {
                $onlyGo = $request->only('user_id', 'account_number', 'bank_id', 'credit', 'debit', 'discount', 'note');
                $account = $this->common->storeInModel('App\Models\User\Account', $onlyGo);

                DB::commit();

                $message = "User Account store successful";
                return $this->successResponse(Response::HTTP_CREATED, $message, $account->toArray());

            } catch (QueryException $e) {
                DB::rollBack();
                return $this->errorResponse(Response::HTTP_INTERNAL_SERVER_ERROR, $e->getMessage(), []);
            }
        }else{
            return $this->errorResponse(405, 'Method Not Supported', []);
        }
    }


    public function update(AccountRequest $request, $id)
    {
        if($request->isMethod("post")){
            DB::beginTransaction();
            try {
                $account = $this->common->findOnModel('App\Models\User\Account', 'id', $id);

                if (!$account) {
                    return $this->errorResponse(404, 'Not Found Your Targeted Data', []);
                }

                $onlyGo = $request->only('user_id', 'account_number', 'bank_id', 'credit', 'debit', 'discount', 'note');
                $account = $this->common->update($onlyGo, $account);

                DB::commit();
                $message = "User account update successful";
                return $this->successResponse(Response::HTTP_OK, $message, $account->toArray());

            } catch (QueryException $e) {
                DB::rollBack();
                return $this->errorResponse(Response::HTTP_INTERNAL_SERVER_ERROR, $e->getMessage(), []);
            }
        }else{
            return $this->errorResponse(405, 'Method Not Supported', []);
        }
    }


    public function accountInformation($id){
        $accountInformation = DB::table('accounts')
                            ->leftJoin('users', function ($join){
                                $join->on('users.id', '=', 'accounts.user_id');
                            })
                            ->leftJoin('banks', function ($join){
                                $join->on('banks.id', '=', 'accounts.bank_id');
                            })
                            ->where('accounts.user_id', '=', $id)
                            ->first();

        return response()->json(['data' => $accountInformation]);
    }

}
