<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\AuthorizeRequest;
use App\Http\Requests\PaymentRequest;
use App\Http\Requests\PaymentUpdateRequest;
use App\Services\TransactionService;
use Illuminate\Auth\Middleware\Authorize;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class TransactionController extends Controller
{
    protected $transactionService;

    public function __construct(TransactionService $transactionService)
    {
        $this->transactionService = $transactionService;
    }

    public function makeMockResponse(AuthorizeRequest $request)
    {
        $request->validated();

        $userInfo = $this->transactionService->getUserInfoByEmail(request()->email);

        if (!empty($userInfo)) {

            if (Hash::check(request()->password, $userInfo->password)) {
                Auth::login($userInfo);
                $token = Hash::make(strtotime(now()) . uniqid());
                $userInfo->mock_token = $token;
                $userInfo->updated_at = now();
                $userInfo->save();
                return response()->json(['status' => 'accepted', 'message' => 'Authorized', 'X-Mock-Status' => $token]);
            } else {
                return response()->json(['status' => 'failed', 'message' => 'UnAuthorized',]);
            }
        } else {
            return response()->json(['status' => 'failed', 'message' => 'UnAuthorized',]);
        }
    }

    public function payment(PaymentRequest $request)
    {
        try {
            $data = $request->validated();
            $transaction_id = strtok(now() . uniqid());
            $data['transaction_id'] = $transaction_id;
            $data["payment_created_at"] = now();
            $data["payment_date"] = today();
            $response = $this->transactionService->initialPayment($data);
            if ($response) {
                return response()->json(['status' => 'success', 'message' => 'Payment Created Successfully', 'transaction_id' => $transaction_id]);
            } else {
                return response()->json(['status' => 'failed', 'message' => 'Failed To Create Payment',]);

            }
        } catch (\Exception $e) {
            return response()->json(['status' => 'failed', 'message' => 'Internal Server Error.Please Again Later.',], 500);
        }
    }
    public function paymentUpdate(PaymentUpdateRequest $request)
    {
        try {
            $data = $request->validated();
            $data["payment_updated_at"] = now();
            $data["updated_at"] = now();
            $data["payment_date"] = today();
            $response = $this->transactionService->updatePaymentInfoByTransactionId($request->$transaction_id, $data);
            if ($response) {
                return response()->json(['status' => 'success', 'message' => 'Payment Updated Successfully',]);
            } else {
                return response()->json(['status' => 'failed', 'message' => 'Failed To Update Payment Info',]);

            }
        } catch (\Exception $e) {
            return response()->json(['status' => 'failed', 'message' => 'Internal Server Error.Please Again Later.',], 500);
        }
    }
}