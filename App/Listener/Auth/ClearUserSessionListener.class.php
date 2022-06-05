<?php

declare(strict_types=1);

class ClearUserSessionListener implements ListenerInterface
{
    /**
     * @param EventsInterface $event
     */
    public function handle(EventsInterface $event): iterable
    {
        $object = $event->getObject();
        if ($object instanceof UserSessionsEntity) {
            if (!( new ReflectionProperty($object, $object->getFields('rememberMeCookie')))->isInitialized($object)) {
                /** @var UserSessionsManager */
                $userSession = Container::getInstance()->make(UserSessionsManager::class);
                $userSession->table()->where(['userID' => $object->getUserID()]);
                $delete = $userSession->delete();
                return [$delete];
            }
        }
        return [];
    }
}