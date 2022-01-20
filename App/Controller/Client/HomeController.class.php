<?php

declare(strict_types=1);
class HomeController extends Controller
{
    /**
     * IndexPage
     * ===========================================================================.
     * @param array $data
     * @return void
     */
    public function indexPage(array $data = [])
    {
        /** @var Model */
        $users = $this->container->make(UsersManager::class);
        $users->getDetails('paracyrius@gmail.com', 'email');
        $users->getEntity()->populateEntity($users->get_results());
        dump($users->getEntity());

        $this->view_instance->set_pageTitle('Home');
        $this->view_instance->set_siteTitle('Home');
        $this->view_instance->render('home' . DS . 'index', ['user' => $users->getEntity()]);
    }
}
