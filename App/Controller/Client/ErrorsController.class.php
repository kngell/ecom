<?php

declare(strict_types=1);
class ErrorsController extends Controller
{
    // Promotions page
    public function indexPage($data)
    {
        $this->view_instance->set_pageTitle('Errors');
        $this->view_instance->set_siteTitle('Errors');
        $this->view_instance->render('errors' . DS . '_errors', $data);
    }
}