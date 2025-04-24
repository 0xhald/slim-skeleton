<?php

namespace App\Domain\User\Data;

use DateTimeImmutable;

final class UserData
{
    public string $name = '';
    public string $email = '';
    public DateTimeImmutable $dateOfBirth = null;
}