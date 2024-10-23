<?php

namespace Gingdev\TursoHranaDBAL;

use Doctrine\DBAL\Driver\Statement as StatementInterface;
use Doctrine\DBAL\ParameterType;
use Gingdev\TursoHranaPHP\LibSQL;
use Gingdev\TursoHranaPHP\LibSQLStmt;

class Statement implements StatementInterface
{
    public function __construct(
        private LibSQL $connection,
        private LibSQLStmt $statement,
    ) {
    }

    public function bindValue(int|string $param, mixed $value, ParameterType $type): void
    {
        $this->statement->bindValue($param, $value, $this->convertParamType($type));
    }

    public function execute(): Result
    {
        try {
            $result = $this->statement->execute();
        } catch (\Exception $e) {
            throw Exception::new($e);
        }

        return new Result($result, $this->connection->changes());
    }

    /** @psalm-return self::TYPE_* */
    private function convertParamType(ParameterType $type): int
    {
        return match ($type) {
            ParameterType::NULL => SQLITE3_NULL,
            ParameterType::INTEGER, ParameterType::BOOLEAN => SQLITE3_INTEGER,
            ParameterType::STRING, ParameterType::ASCII => SQLITE3_TEXT,
            ParameterType::BINARY, ParameterType::LARGE_OBJECT => SQLITE3_BLOB,
        };
    }
}
