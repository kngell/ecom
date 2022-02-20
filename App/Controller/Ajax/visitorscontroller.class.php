<?php

declare(strict_types=1);

class VisitorsController extends Controller
{
    //=======================================================================
    //Tract visitors
    //=======================================================================

    //visitor visit the page => save hits
    public function track()
    {
        if ($this->request->exists('post')) {
            $data = $this->request->get();
            $table = str_replace(' ', '', ucwords(str_replace('_', ' ', $data['table'])));
            $model = $this->container->make($table . 'Manager'::class);
            if ($output = $model->manageVisitors($data)) {
                $this->jsonResponse(['result' => 'success', 'msg' => true]);
            }
        }
    }

    public function saveipdata()
    {
        if ($this->request->exists('post')) {
            $data = $this->request->transform_keys($this->request->get(), H_visitors::new_IpAPI_keys());
            $this->model_instance->assign($data);
            if (isset($data['ipAddress']) && !$this->model_instance->getByIp($data['ipAddress'])) {
                $this->model_instance->save();
            }
        }
    }
}