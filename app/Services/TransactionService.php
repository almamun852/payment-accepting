<?php

namespace App\Services;

use App\Models\TransactionHistory;

class TransactionService
{
    protected $transactionModel, $userModel;

    public function __construct(TransactionHistory $transactionModel, $userModel)
    {
        $this->transactionModel = $transactionModel;
    }

    public function getUserInfoByEmail($email)
    {
        return $this->userModel->where("email", str(trim($email)))->first();
    }
    public function initialPayment($data)
    {
        return $this->transactionModel->create($data);
    }
    public function updatePaymentInfoByTransactionId($transactionId,$data)
    {
        return $this->transactionModel->create($data);
    }
}
