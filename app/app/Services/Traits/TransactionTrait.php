<?php

namespace App\Services\Traits;

use Illuminate\Support\Facades\DB;

trait TransactionTrait
{
    public function beginTransaction(): void
    {
        DB::beginTransaction();
    }

    public function commit(): void
    {
        DB::commit();
    }

    public function rollback(): void
    {
        DB::rollback();
    }
}
