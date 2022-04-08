<?php

declare(strict_types=1);

interface ViewInterface
{
    /**
     * Render View
     * -----------------------------------------------------------------.
     * @param string $viewname
     * @param array $params
     * @return void
     */
    public function render(string $viewname = '', array $params = []) : void;

    /**
     * Set Bloc Content into Memory.
     * -----------------------------------------------------------------.
     * @param string $type
     * @return void
     */
    public function start(string $type) : void;

    /**
     * Get View Content.
     * ----------------------------------------------------------------.
     * @param string $type
     * @return bool|string
     */
    public function content(string $type) : bool|string;

    /**
     * Store view Content.
     * ----------------------------------------------------------------.
     * @return void
     */
    public function end() : void;

    /**
     * Get Asset link.
     * --------------------------------------------------------------.
     * @param string $asset
     * @param string $ext
     * @return string
     */
    public function asset($asset = '', $ext = '') : string;
}