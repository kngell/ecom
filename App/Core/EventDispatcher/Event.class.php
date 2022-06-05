<?php

declare(strict_types=1);

class Event implements StoppableEventInterface, EventsInterface
{
    private bool $propagationStopped = false;
    private object $object;
    private string $name;
    private array $params;
    private ?object $results = null;

    public function __construct(Object $object, string $name = '', array $params = [])
    {
        $this->object = $object;
        $this->name = $name;
        $this->params = $params;
    }

    /**
     * Is propagation stopped?
     *
     * This will typically only be used by the Dispatcher to determine if the
     * previous listener halted propagation.
     *
     * @return bool
     *   True if the Event is complete and no further listeners should be called.
     *   False to continue calling listeners.
     */
    public function isPropagationStopped(): bool
    {
        return $this->propagationStopped;
    }

    /**
     * Stops the propagation of the event to further event listeners.
     *
     * If multiple event listeners are connected to the same event, no
     * further event listener will be triggered once any trigger calls
     * stopPropagation().
     */
    public function stopPropagation(): void
    {
        $this->propagationStopped = true;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getObject(): object
    {
        return $this->object;
    }

    /**
     * Set the value of object.
     *
     * @param  object  $object
     *
     * @return  self
     */
    public function setObject(object $object) : self
    {
        $this->object = $object;
        return $this;
    }

    /**
     * Set the value of name.
     *
     * @return  self
     */
    public function setName($name) : self
    {
        $this->name = $name;
        return $this;
    }

    /**
     * Get the value of params.
     */
    public function getParams() : array
    {
        return $this->params;
    }

    /**
     * Set the value of params.
     *
     * @return  self
     */
    public function setParams($params) : self
    {
        $this->params = $params;
        return $this;
    }

    /**
     * Set the value of propagationStopped.
     *
     * @return  self
     */
    public function setPropagation(bool $propagationStopped)
    {
        $this->propagationStopped = $propagationStopped;
        return $this;
    }

    /**
     * Get the value of results.
     */
    public function getResults() : object
    {
        return $this->results;
    }

    /**
     * Set the value of results.
     *
     * @return  self
     */
    public function setResults(object $results) : self
    {
        $this->results = $results;
        return $this;
    }

    protected function parseDom(string $html) : string
    {
        $doc = new DOMDocument('1.0', 'UTF-8');
        $doc->preserveWhiteSpace = false;
        $doc->encoding = 'utf-8';
        $doc->loadHTML($html);
        return $doc->saveHTML($doc->documentElement) . PHP_EOL . PHP_EOL;
    }
}