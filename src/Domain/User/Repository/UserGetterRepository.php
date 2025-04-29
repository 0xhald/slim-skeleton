<?php

namespace App\Domain\User\Repository;

use Cycle\Database\DatabaseInterface;

final class UserGetterRepository
{
    private DatabaseInterface $database;

    public function __construct(DatabaseInterface $database)
    {
        $this->database = $database;
    }

    public function getAll (): array
    {
        return $this->database->select('name, email, created_at')->from("users")->fetchAll();
    }
}