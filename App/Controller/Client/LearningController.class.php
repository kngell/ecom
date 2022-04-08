<?php

declare(strict_types=1);

class LearningController extends Controller
{
    public function indexPage()
    {
        $this->render('learn' . DS . 'learn', [
            'text' => 'Hello world',
        ]);
    }
}