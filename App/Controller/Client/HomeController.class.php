<?php

declare(strict_types=1);
class HomeController extends Controller
{
    /**
     * IndexPage
     * ================================================================.
     * @param array $data
     * @return void
     */
    protected function indexPage(array $data = [])
    {
        $args = YamlFile::get('dtController')['user'];
        /** @var array */
        $r = $this->container->make(UsersManager::class)->getRepository()->findWithSearchAndPagin($args, $this->container->make(RequestHandler::class)->handler());
        // $users->getDetails('paracyrius@gmail.com', 'email');
        // $users->getEntity()->populateEntity($users->get_results());
        $tableData = (new Datatable())->create(UserDataColumn::class, $r, $args)->setAttr([
            'table_id' => 'table',
            'table_class' => ['table', 'table-striped', 'table-hover', 'my-5'],
        ])->table();

        $this->set_pageTitle('Home');
        $this->set_siteTitle('Home');
        $this->render('home' . DS . 'index', [
            'table' => $tableData,
            'pagination' => (new Datatable())->create(UserDataColumn::class, $r, $args)->pagination(),
        ]);
    }
}