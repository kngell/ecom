<?php

declare(strict_types=1);
class EmailingController extends Controller
{
    /**
     * IndexPage
     * ===================================================================.
     * @param array $data
     * @return string
     */
    protected function emailPage(array $data = []) : ?string
    {
        $this->setLayout('emailTemplate');
        $this->siteTitle('Registration Email');
        return $this->render('users' . DS . 'emailTemplate' . DS . 'welcomeTemplate');
    }
}