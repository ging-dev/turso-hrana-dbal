<?php

namespace Gingdev\TursoHranaDBAL;

use Doctrine\DBAL\Driver\Connection as ConnectionInterface;
use Gingdev\TursoHranaPHP\LibSQL;

final class Connection implements ConnectionInterface
{
    public function __construct(private LibSQL $connection)
    {
    }

    public function beginTransaction(): void
    {
        try {
            $this->connection->exec('BEGIN');
        } catch (\Exception $e) {
            throw Exception::new($e);
        }
    }

    public function commit(): void
    {
        try {
            $this->connection->exec('COMMIT');
        } catch (\Exception $e) {
            throw Exception::new($e);
        }
    }

    public function exec(string $sql): int|string
    {
        try {
            $this->connection->exec($sql);
        } catch (\Exception $e) {
            throw Exception::new($e);
        }

        return $this->connection->changes();
    }

    public function getNativeConnection(): LibSQL
    {
        return $this->connection;
    }

    public function lastInsertId(): int|string
    {
        return $this->connection->lastInsertRowID();
    }

    public function prepare(string $sql): Statement
    {
        try {
            $statement = $this->connection->prepare($sql);
        } catch (\Exception $e) {
            throw Exception::new($e);
        }

        return new Statement($this->connection, $statement);
    }

    public function query(string $sql): Result
    {
        try {
            $result = $this->connection->query($sql);
        } catch (\Exception $e) {
            throw Exception::new($e);
        }

        return new Result($result, $this->connection->changes());
    }

    public function quote(string $value): string
    {
        return sprintf('\'%s\'', \SQLite3::escapeString($value));
    }

    public function rollBack(): void
    {
        try {
            $this->connection->exec('ROLLBACK');
        } catch (\Exception $e) {
            throw Exception::new($e);
        }
    }

    public function getServerVersion(): string
    {
        return \SQLite3::version()['version'];
    }
}
