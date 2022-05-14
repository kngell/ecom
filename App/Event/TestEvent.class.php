<?php

declare(strict_types=1);
class TestEvent extends Event implements EventsInterface
{
    /**
     * @var object
     */
    private $object;

    /**
     * PreCreateEvent constructor.
     * @param object $object
     */
    public function __construct(object $object)
    {
        $this->object = $object;
    }

    public function getName(): string
    {
        return 'ok';
    }

    /**
     * @return object
     */
    public function getObject(): object
    {
        return $this->object;
    }
}