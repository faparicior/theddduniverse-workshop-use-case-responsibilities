<?php
declare(strict_types=1);

namespace Demo\App\Framework\Database;

interface TransactionManager
{
    public function beginTransaction(): void;
    public function commit(): void;
    public function rollback(): void;
}
