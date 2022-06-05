<?php

declare(strict_types=1);

class UpdateUserSessionListener implements ListenerInterface
{
    /**
     * @param EventsInterface $event
     */
    public function handle(EventsInterface $event): iterable
    {
        $object = $event->getObject();
        /** @var SessionInterface */
        $session = Container::getInstance()->make(SessionInterface::class);
        if ($session->exists(CURRENT_USER_SESSION_NAME)) {
            $session_values = $session->get(CURRENT_USER_SESSION_NAME);
            isset($session_values['verified']) ? $session_values['verified'] = 1 : '';
        }
        return [];
    }
}