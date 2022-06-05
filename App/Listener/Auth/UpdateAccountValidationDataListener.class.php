<?php

declare(strict_types=1);

class UpdateAccountValidationDataListener implements ListenerInterface
{
    /**
     * @param EventsInterface $event
     */
    public function handle(EventsInterface $event): iterable
    {
        $object = $event->getObject();
        if ($object instanceof UsersRequestsEntity) {
            $user = Container::getInstance()->make(UsersManager::class)->assign([
                'userID' => $object->getUserID(),
                'verified' => 1,
            ])->save();
            return [$user];
        }
        return [];
    }
}