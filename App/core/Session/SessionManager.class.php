<?php

declare(strict_types=1);

class SessionManager
{
    private SessionFactory $sessionFactory;

    public function __construct(SessionFactory $sessionFactory)
    {
        $this->sessionFactory = $sessionFactory;
    }

    public function initialize()
    {
        return $this->sessionFactory->create('generic_session_name', YamlFile::get('session'));
    }
}
