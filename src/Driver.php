<?php

namespace Gingdev\TursoHranaDBAL;

use Doctrine\DBAL\Driver\AbstractSQLiteDriver;
use Gingdev\TursoHranaPHP\Hrana\HranaClient;
use Gingdev\TursoHranaPHP\LibSQL;

class Driver extends AbstractSQLiteDriver
{
    public function connect(array $params): Connection
    {
        try {
            $libsql = new LibSQL(
                HranaClient::create(
                    'wss://'.$params['host'],
                    $params['password'] ?? '',
                )
            );
        } catch (\Exception $e) {
            throw Exception::new($e);
        }

        return new Connection($libsql);
    }
}
