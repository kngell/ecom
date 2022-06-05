<?php

declare(strict_types=1);

class VisitorsController extends Controller
{
    public function track()
    {
        $data = $this->isValidRequest();
        if (!$this->cache->exists('visitor' . $data['ip'])) {
            /** @var VisitorsManager */
            $model = $this->model(VisitorsManager::class)->assign($data);
            $output = $model->manageVisitors($data);
            if ($output->count() > 0) {
                $this->cache->set('visitor' . $data['ip'], $output->count());
                $this->jsonResponse(['result' => 'success', 'msg' => true]);
            }
        }

        if ($this->cache->get('visitor' . $data['ip']) > 0) {
            $this->jsonResponse(['result' => 'success', 'msg' => true]);
        }
    }

    public function saveipdata()
    {
        if ($this->request->exists('post')) {
            $data = $this->response->transform_keys($this->request->get(), H_visitors::new_IpAPI_keys());
            $this->model_instance->assign($data);
            if (isset($data['ipAddress']) && !$this->model_instance->getByIp($data['ipAddress'])) {
                $this->model_instance->save();
            }
        }
    }
}