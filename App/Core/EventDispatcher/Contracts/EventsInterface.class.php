<?php

declare(strict_types=1);
interface EventsInterface
{
    public function getName() : string;

    public function getObject(): object;

    public function setResults(object $results) : self;

    public function getResults() : object;

    public function setParams($params) : self;

    public function getParams() : array;
}