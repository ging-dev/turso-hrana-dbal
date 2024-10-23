<?php

namespace Gingdev\TursoHranaDBAL;

use Doctrine\DBAL\Driver\FetchUtils;
use Doctrine\DBAL\Driver\Result as ResultInterface;
use Doctrine\DBAL\Exception\InvalidColumnIndex;
use Gingdev\TursoHranaPHP\LibSQLResult;

class Result implements ResultInterface
{
    public function __construct(
        private ?LibSQLResult $result,
        private int $changes,
    ) {
    }

    public function columnCount(): int
    {
        if (null === $this->result) {
            return 0;
        }

        return $this->result->numColumns();
    }

    public function getColumnName(int $index): string
    {
        if (null === $this->result) {
            throw InvalidColumnIndex::new($index);
        }

        $name = $this->result->columnName($index);

        if (false === $name) {
            throw InvalidColumnIndex::new($index);
        }

        return $name;
    }

    public function fetchAllAssociative(): array
    {
        return FetchUtils::fetchAllAssociative($this);
    }

    public function fetchAllNumeric(): array
    {
        return FetchUtils::fetchAllNumeric($this);
    }

    public function fetchAssociative(): array|false
    {
        if (null === $this->result) {
            return false;
        }

        return $this->result->fetchArray(SQLITE3_ASSOC);
    }

    public function fetchFirstColumn(): array
    {
        return FetchUtils::fetchFirstColumn($this);
    }

    public function fetchNumeric(): array|false
    {
        if (null === $this->result) {
            return false;
        }

        return $this->result->fetchArray(SQLITE3_NUM);
    }

    public function fetchOne(): mixed
    {
        return FetchUtils::fetchOne($this);
    }

    public function free(): void
    {
        if (null === $this->result) {
            return;
        }

        $this->result->finalize();
        $this->result = null;
    }

    public function rowCount(): int
    {
        return $this->changes;
    }
}
