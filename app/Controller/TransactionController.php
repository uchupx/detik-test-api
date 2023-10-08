<?php

namespace App\Controller;

use App\Exception\InvalidBodyException;
use App\Exception\NotFoundException;
use App\Exception\PageNotFoundException;
use App\Model\DTO\Transaction as DTOTransaction;
use App\Service\Transaction;

class TransactionController
{
    public static function get(array $reqs)
    {
        if (!ctype_digit($reqs[0])) {
            throw new PageNotFoundException();
        }

        $param          = intval($reqs[0]);
        $transactionSvc = new Transaction();
        $transaction    = $transactionSvc->find($param);

        if (!$transaction) {
            throw new NotFoundException();
        }

        return $transaction;
    }

    public static function getAll()
    {
        $transactionSvc = new Transaction();
        return $transactionSvc->findAll();
    }

    public static function post()
    {
        $json = file_get_contents('php://input');
        $data = json_decode($json, true);

        $transaction = (new DTOTransaction())->setAll($data);
        if (!$transaction->isValid()) {
            throw new InvalidBodyException();
        }
        $transactionSvc = new Transaction();
        $transactionSvc->create($transaction);

        return array(
            "number_va"     => $transaction->number_va,
            "status"        => $transaction->status,
            "references_id" => $transaction->references_id,
        );
    }

    public static function status()
    {
        $merchantId  = $_GET['merchant_id'];
        $referenceId = $_GET['references_id'];

        if (!is_numeric($merchantId) || !is_numeric($referenceId)) {
            return null;
        }

        $transactionSvc = new Transaction();
        $transaction    = $transactionSvc->findByPair($merchantId, $referenceId);
        if (!$transaction) {
            return null;
        }

        return array(
            "invoice_id"    => $transaction->invoice_id,
            "status"        => $transaction->status,
            "references_id" => $transaction->references_id,
        );
    }
}
