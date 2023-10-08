<?php
namespace App\Repository;

use App\Database\Database;
use App\Model\DAO\Transaction as TransactionModel;
use Config\Config;
use PDO;

class TransactionRepo
{
    const FINDALL            = "SELECT * FROM transactions";
    const FIND               = "SELECT * FROM transactions WHERE id = ?";
    const FINDBYREFERENCESID = "SELECT * FROM transactions WHERE references_id = ?";
    const FINDBYPAIR         = "SELECT * FROM transactions WHERE merchant_id = ? AND references_id = ?";
    const CREATE             = "INSERT INTO transactions (invoice_id, item_name, amount, payment_type, customer_name, merchant_id, `status`, references_id, number_va) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    const UPDATE             = "UPDATE transactions SET status = ? WHERE id = ?";
    protected Database $db;

    public function __construct()
    {
        $this->db = new Database(Config::get("DATABASE_HOST"), Config::get("DATABASE_NAME"), Config::get("DATABASE_USERNAME"), Config::get("DATABASE_PASSWORD"));
    }

    /**
     * Retrieve all transactions.
     *
     * @return TransactionModel[] An array of transaction models.
     */
    public function findAll(): array
    {
        $stmt = $this->db->prepare(self::FINDALL);
        $stmt->execute();

        foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
            $transactions[] = (new TransactionModel())->setAll($row);
        }

        return $transactions;
    }

    /**
     * Find a transaction by ID.
     *
     * @param int $id The ID of the transaction to find.
     *
     * @return TransactionModel|null The transaction model if found, null otherwise.
     */
    public function find($id): ?TransactionModel
    {
        $stmt = $this->db->prepare(self::FIND);
        $stmt->execute([$id]);

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$row) {
            return null;
        }

        return (new TransactionModel())->setAll($row);
    }

    /**
     * Create a transaction.
     *
     * @param TransactionModel $transaction The transaction model to create.
     *
     * @return int The ID of the created transaction.
     */
    public function create(TransactionModel $transaction): ?int
    {
        $stmt = $this->db->prepare(self::CREATE);
        $stmt->execute([
            $transaction->invoice_id,
            $transaction->item_name,
            $transaction->amount,
            $transaction->payment_type,
            $transaction->customer_name,
            $transaction->merchant_id,
            $transaction->status,
            $transaction->references_id,
            $transaction->number_va,
        ]);

        return $this->db->lastInsertId();
    }

    /**
     * Find a transaction by merchant ID and reference ID.
     *
     * @param int $merchantId The merchant ID of the transaction to find.
     * @param int $referenceId The reference ID of the transaction to find.
     *
     * @return TransactionModel|null The transaction model if found, null otherwise.
     */
    public function status(int $merchantId, int $referenceId): ?TransactionModel
    {
        $stmt = $this->db->prepare(self::FINDBYPAIR);
        $stmt->execute([$merchantId, $referenceId]);

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$row) {
            return null;
        }

        return (new TransactionModel())->setAll($row);
    }

    /**
     * Update a transaction.
     *
     * @param TransactionModel $transaction The transaction model to update.
     *
     * @return int The number of rows affected.
     */
    public function update(TransactionModel $transaction): int
    {
        $stmt = $this->db->prepare(self::UPDATE);
        $stmt->execute([$transaction->status, $transaction->id]);

        return $stmt->rowCount();
    }

    public function findByReferencesId(int $referencesId): ?TransactionModel
    {
        $stmt = $this->db->prepare(self::FINDBYREFERENCESID);
        $stmt->execute([$referencesId]);

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$row) {
            return null;
        }

        return (new TransactionModel())->setAll($row);
    }
}
