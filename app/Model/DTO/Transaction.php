<?php

namespace App\Model\DTO;

use App\Model\DAO\Transaction as DAOTransaction;

class Transaction
{
    public ?int $id;
    public ?int $invoice_id;
    public ?string $item_name;
    public ?int $amount;
    public ?string $payment_type;
    public ?string $customer_name;
    public ?int $merchant_id;
    public ?int $references_id;
    public ?int $number_va;
    public ?string $status;

    public function setAll(array | object $data)
    {
        $properties = [
            'id',
            'invoice_id',
            'item_name',
            'amount',
            'payment_type',
            'customer_name',
            'merchant_id',
            'references_id',
            'number_va',
            'status',
        ];

        foreach ($properties as $property) {
            $this->$property = is_array($data) && isset($data[$property]) ? $data[$property] : (isset($data->$property) ? $data->$property : null);
        }

        return $this;
    }

    public function toDAO(): DAOTransaction
    {
        return (new DAOTransaction())->setAll($this);
    }

    public function isValid(): bool
    {
        return $this->invoice_id !== null
        && $this->item_name !== null
        && $this->amount !== null
        && $this->payment_type !== null
        && $this->customer_name !== null
        && $this->merchant_id !== null;
    }

}
