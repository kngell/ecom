<?php

declare(strict_types=1);

namespace App\Tests;

use DateTimeInterface;
use Doctrine\ORM\EntityManager;
use PHPUnit\Framework\TestCase;

class DatabaseDependantTestCase extends TestCase
{
    protected null|EntityManager $entityManager;

    protected function setUp(): void
    {
        parent::setUp();
        require 'bootstrap-test.php';

        $this->entityManager = $entityManager;
        SchemaLoader::load($entityManager);
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        $this->entityManager->close();
        $this->entityManager = null;
    }

    public function assertDataBaseHas(string $tableName, array $criteria)
    {
        $sqlParameters = $keys = array_keys($criteria);
        $firstColumn = array_shift($sqlParameters);
        $sql = "SELECT 1 FROM {$tableName} WHERE {$firstColumn} = :{$firstColumn}";
        foreach ($sqlParameters as $column) {
            $sql .= " AND {$column} = :{$column}";
        }
        //Create stmt
        $conn = $this->entityManager->getConnection();
        $stmt = $conn->prepare($sql);
        //Bind
        foreach ($keys as $key) {
            $stmt->bindValue($key, $criteria[$key]);
        }
        $keyValueString = $this->asKeyValueString($criteria);
        $failureMessage = "A record could not be found in the $tableName table with the following attributes :  {$keyValueString}";
        //Result
        $result = $stmt->executeQuery();
        self::assertTrue((bool) $result->fetchOne(), $failureMessage);
    }

    public function assertDatabaseHasEntity(string $entityName, array $criteria)
    {
        $results = $this->entityManager->getRepository($entityName)->findOneBy($criteria);
        $keyValueString = $this->asKeyValueString($criteria);
        $failureMessage = "A $entityName record could not be found with the following attributes :  {$keyValueString}";
        self::assertTrue((bool) $results, $failureMessage);
    }

    public function assertDatabaseNotHas(string $entityName, array $criteria)
    {
        $results = $this->entityManager->getRepository($entityName)->findOneBy($criteria);
        $keyValueString = $this->asKeyValueString($criteria);
        $failureMessage = "A $entityName record Was found with the following attributes :  {$keyValueString}";
        self::assertFalse((bool) $results, $failureMessage);
    }

    public function asKeyValueString(array $criteria, string $separator = ' = ') : string
    {
        $mappedAttribute = array_map(function ($key, $value) use ($separator) {
            if ($value instanceof DateTimeInterface) {
                $value = $value->format('Y-m-d');
            }
            return $key . $separator . $value;
        }, array_keys($criteria), $criteria);
        return implode(', ', $mappedAttribute);
    }
}
