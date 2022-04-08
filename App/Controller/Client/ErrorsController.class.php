<?php

declare(strict_types=1);
class ErrorsController extends Controller
{
    // Promotions page
    public function indexPage($data)
    {
        $this->view_instance->pageTitle('Errors');
        $this->view_instance->siteTitle('Errors');
        $this->view_instance->render('errors' . DS . '_errors', $data);
    }
}