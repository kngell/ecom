<?php

declare(strict_types=1);
class HomeController extends Controller
{
    /**
     * IndexPage
     * ===================================================================.
     * @param array $data
     * @return void
     */
    protected function indexPage(array $data = [])
    {
        $args = YamlFile::get('dtController')['user'];
        /** @var RepositoryInterface */
        $r = $this->model(UsersManager::class)->getRepository();
        $data = $r->findWithSearchAndPagin($args, $this->container->make(RequestHandler::class)->handler());
        $tableData = (new Datatable())->create(UserDataColumn::class, $data, $args)->setAttr([
            'table_id' => 'table',
            'table_class' => ['table', 'table-striped', 'table-hover', 'my-5'],
        ])->table();

        $this->pageTitle('Home');
        $this->siteTitle('Home');
        $this->render('home' . DS . 'index', [
            'table' => $tableData,
            'pagination' => (new Datatable())->create(UserDataColumn::class, $data, $args)->pagination(),
        ]);
    }
}