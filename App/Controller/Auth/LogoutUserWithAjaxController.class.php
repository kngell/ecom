<?php

declare(strict_types=1);

class LogoutUserWithAjaxController extends Controller
{
    public function index()
    {
        if ($this->request->exists('post')) {
            $data = $this->request->get();
            if ($data['csrftoken'] && $this->token->validate($data['csrftoken'], $data['frm_name'])) {
                $model = $this->model(LogoutManager::class)->assign($data);
                $resp = $model->logout();
                $resp !== null ? $this->jsonResponse(['result' => 'success', 'msg' => $this->helper->showMessage('warning text-success', 'Welcome')]) : $this->jsonResponse(['result' => 'error', 'msg' => $this->helper->showMessage('warning text-warning', 'Something goes wrong! plase contact the administrator!')]);
            }
        }
    }
}
