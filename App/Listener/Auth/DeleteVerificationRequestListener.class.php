<?php

declare(strict_types=1);

class DeleteVerificationRequestListener implements ListenerInterface
{
    /**
     * @param EventsInterface $event
     */
    public function handle(EventsInterface $event): iterable
    {
        $object = $event->getObject();
        if ($object instanceof UsersRequestsEntity) {
            /** @var UsersRequestsManager */
            $userRequest = Container::getInstance()->make(UsersRequestsManager::class);
            $userRequest->table()->where(['userID' => $object->getUserID()]);
            $delete = $userRequest->delete();
            return [$delete];
        }
        return [];
    }
}