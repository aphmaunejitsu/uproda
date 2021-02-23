<?php

namespace App\Services;

interface TransactionInterface
{
    public function beginTransaction(): void;
    public function commit(): void;
    public function rollback(): void;
}
