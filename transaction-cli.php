<?php

use App\Enum\Transaction as TransactionEnum;
use Config\Config;

include 'autoload.php';

// initialize config
new Config();

$reference_id = $argv[1];
$status       = $argv[2];

if (!ctype_digit($reference_id)) {
    echo "Invalid reference ID\n";
    exit;
}

$reference_id = (int) $reference_id;

if (array_search($status, TransactionEnum::STATUSES) === false) {
    echo "Invalid status\n";
    exit;
}

$service     = new \App\Service\Transaction();
$transaction = $service->findByReferencesId($reference_id);

if (!$transaction) {
    echo "Transaction not found\n";
    exit;
}

$transaction->status = $status;
try {
    $row = $service->updateStatus($transaction->id, $status);
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    exit;
}

echo "Transaction updated\n";
