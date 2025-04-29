<?php

namespace App\Domain\User\Service;

use App\Domain\User\Repository\UserGetterRepository;
use App\Database\TransactionInterface;

final class UserGetter
{
    private UserGetterRepository $repository;
    private TransactionInterface $transaction;
    public function __construct(UserGetterRepository $repository, TransactionInterface $transaction)
    {
        $this->repository = $repository;
        $this->transaction = $transaction;
    }

    public function getAllUsers(): array
    {
        $this->transaction->begin();
        try {
            $users = $this->repository->getAll();
            $this->transaction->commit();
            return $users;
        } catch (\Exception $exception) {
            $this->transaction->rollback();
            return [];
        }
    }
}