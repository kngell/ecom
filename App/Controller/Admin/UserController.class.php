<?php

declare(strict_types=1);
class UserController extends Controller
{
    protected function indexPage()
    {
        echo $this->routeParams['controller'];
    }

    protected function editPage($id = 0)
    {
        $user = $this->container->make(UsersManager::class)->getRepository()->findByID((int) $this->routeParams['id']);
        if ($user->count() > 0) {
            echo '<pre>';
            print_r($user->get_results());
            echo '</pre>';
        }
    }
}