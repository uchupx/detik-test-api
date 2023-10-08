<?php

namespace App\Enum;

class Transaction
{
    const PENDING = 'pending';
    const PAID    = 'paid';
    const EXPIRED = 'expired';
    const FAILED  = 'failed';

    const STATUSES = [
        SELF::PENDING,
        SELF::PAID,
        SELF::EXPIRED,
        SELF::FAILED,
    ];

    const PAYMENT_TYPE_CREDIT_CARD     = 'credit_card';
    const PAYMENT_TYPE_VIRTUAL_ACCOUNT = 'virtual_account';

    const PAYMENT_TYPE = [
        SELF::PAYMENT_TYPE_CREDIT_CARD,
        SELF::PAYMENT_TYPE_VIRTUAL_ACCOUNT,
    ];
}
