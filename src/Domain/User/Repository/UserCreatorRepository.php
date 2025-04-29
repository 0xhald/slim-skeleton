<?php

namespace App\Domain\User\Repository;

use Cycle\Database\DatabaseInterface;

final class UserCreatorRepository
{
    private DatabaseInterface $database;

    public function __construct(DatabaseInterface $database)
    {
        $this->database = $database;
    }

    public function insertUser (array $user): int
    {
        $row = [
            "username" => $user["username"],
            "first_name" => $user["first_name"],
            "last_name" => $user["last_name"],
            "email" => $user["email"],
        ];

        return (int) $this->database->insert('users')->values($row)->run();
    }
}