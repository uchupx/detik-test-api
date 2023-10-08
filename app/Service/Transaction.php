<?php

namespace App\Service;

// use App\Model\DAO\Transaction as TransactionModel;
use App\Enum\Transaction as TransactionEnum;
use App\Exception\InvalidBodyException;
use App\Exception\NotFoundException;
use App\Model\DTO\Transaction as TransactionDTO;
use App\Repository\TransactionRepo;

class Transaction
{
    protected TransactionRepo $repo;
    public function __construct()
    {
        $this->repo = new TransactionRepo();
    }

    /**
     * Retrieve all transactions.
     *
     * @return TransactionDTO[] An array of transaction DTOs.
     */
    public function findAll(): array
    {
        return $this->repo->findAll();
    }

    /**
     * Find a transaction by ID.
     *
     * @param int $id The ID of the transaction to find.
     *
     * @return TransactionDTO|null The transaction DTO if found, null otherwise.
     */
    public function find(int $id): ?TransactionDTO
    {
        $res = $this->repo->find($id);

        if (!$res) {
            return null;
        }

        $transaction = (new TransactionDTO())->setAll($res);
        return $transaction;
    }

    /**
     * Create a transaction.
     *
     * @param TransactionDTO $transaction The transaction to create.
     *
     * @return TransactionDTO The created transaction.
     */
    public function create(TransactionDTO $transaction): TransactionDTO
    {
        if ($transaction->payment_type === "virtual_account") {
            $transaction->number_va = rand(10000000, 99999999);
        }

        $transaction->references_id = rand(10000000, 99999999);
        $transaction->status        = TransactionEnum::PENDING;

        try {
            $id = $this->repo->create($transaction->toDAO());
        } catch (\Throwable $th) {
            throw $th;
        }
        $transaction->id = $id;

        return $transaction;
    }

    /**
     * Find a transaction by merchant ID and reference ID.
     *
     * @param int $merchantId The merchant ID of the transaction to find.
     * @param int $referenceId The reference ID of the transaction to find.
     *
     * @return TransactionDTO|null The transaction DTO if found, null otherwise.
     */
    public function findByPair(int $merchantId, int $referenceId): ?TransactionDTO
    {
        $res = $this->repo->status($merchantId, $referenceId);

        if (!$res) {
            return null;
        }

        $transaction = (new TransactionDTO())->setAll($res);
        return $transaction;
    }

    /**
     * Update a transaction status.
     *
     * @param int $id The ID of the transaction to update.
     * @param string $status The status of the transaction to update.
     *
     * @return TransactionDTO The updated transaction.
     */
    public function updateStatus(int $id, string $status): TransactionDTO
    {
        $transaction = $this->find($id);
        if (!$transaction) {
            throw new NotFoundException();
        }

        if (!array_search($status, TransactionEnum::STATUSES)) {
            throw new InvalidBodyException("Invalid status");
        }

        $transaction->status = $status;
        $this->repo->update($transaction->toDAO());

        return $transaction;
    }

    /**
     * Find a transaction by reference ID.
     *
     * @param int $referenceId The reference ID of the transaction to find.
     *
     * @return TransactionDTO|null The transaction DTO if found, null otherwise.
     */
    public function findByReferencesId(int $referenceId): ?TransactionDTO
    {
        $res = $this->repo->findByReferencesId($referenceId);

        if (!$res) {
            return null;
        }

        $transaction = (new TransactionDTO())->setAll($res);
        return $transaction;
    }
}
