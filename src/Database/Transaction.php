<?php

namespace App\Database;

use Cycle\Database\DatabaseInterface;

final class Transaction implements TransactionInterface
{
    private DatabaseInterface $database;
    public function __construct(DatabaseInterface $database)
    {
        $this->database = $database;
    }
    public function begin(): void
    {
        $this->database->begin();
    }
    public function commit(): void
    {
        $this->database->commit();
    }
    public function rollback(): void
    {
        $this->database->rollback();
    }
}
