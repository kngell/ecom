<?php

declare(strict_types=1);

class Flash implements FlashInterface
{
    protected const FLASH_KEY = 'flash_message';

    /**
     * Add message to session.
     *
     * @param string $msg
     * @param string $type
     * @return void
     */
    public static function add(string $msg = '', string $type = FlashTypes::SUCCESS): void
    {
        $session = GlobalsManager::get('global_session');
        if (!$session->exsits(self::FLASH_KEY)) {
            $session->set(self::FLASH_KEY, []);
        }
        $session->setArray(self::FLASH_KEY, ['message' => $msg, 'type' => $type]);
    }

    /**
     * Get Message from session.
     *
     * @return void
     */
    public static function get()
    {
        $session = GlobalsManager::get('global_session');
        $session->flush(self::FLASH_KEY);
    }
}
