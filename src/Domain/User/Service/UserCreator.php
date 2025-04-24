<?php

namespace App\Domain\User\Service;
use App\Domain\User\Repository\UserCreatorRepository;
use App\Exception\ValidationException;

final class UserCreator
{
    private UserCreatorRepository $repository;
    public function __construct(UserCreatorRepository $repository)
    {
        $this->repository = $repository;
    }

    public function createUser(array $data): int
    {
        // Input validation
        $this->validateNewUser($data);
        // Insert user
        $userId = $this->repository->insertUser($data);
        return $userId;
    }

    private function validateNewUser(array $data): void
    {
        // Validation rules
    }
}