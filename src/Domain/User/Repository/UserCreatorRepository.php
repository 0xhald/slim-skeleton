<?php

namespace App\Domain\User\Repository;

use Vendor\Database\Connection;

final class UserCreatorRepository
{
    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function insertUser (array $user): int
    {
        $row = [
            "username" => $user["username"],
            "first_name" => $user["first_name"],
            "last_name" => $user["last_name"],
            "email" => $user["email"],
        ];

        $sql = "INSERT INTO users SET
                username = :username,
                first_name = :first_name,
                last_name = :last_name,
                email = :email;";
        
        $this->connection->prepare($sql)->execute($row);
        return (int) $this->connection->lastInsertId();
    }
}