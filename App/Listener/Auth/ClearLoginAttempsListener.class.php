<?php

declare(strict_types=1);
class ClearLoginAttempsListener implements ListenerInterface
{
    public function handle(EventsInterface $event): iterable
    {
        /** @var UserSessionsManager */
        $object = $event->getObject();
        if ($object instanceof UserSessionsEntity) {
            /** @var LoginAttemptsManager */
            $loginAttemps = Container::getInstance()->make(LoginAttemptsManager::class);
            $loginAttemps->table()->where(['user_id' => $object->getUserId()]);
            $delete = $loginAttemps->delete();
            return [$delete];
        }
        return [];
    }
}