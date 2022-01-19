<?php

declare(strict_types=1);

namespace App\Tests;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\SchemaTool;

class SchemaLoader
{
    public static function load(EntityManagerInterface $em)
    {
        $metaData = $em->getMetadataFactory()->getAllMetadata();
        $schemaTools = new SchemaTool($em);
        $schemaTools->updateSchema($metaData);
    }
}