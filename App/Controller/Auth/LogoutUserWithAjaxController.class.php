<?php

declare(strict_types=1);

class LogoutUserWithAjaxController extends Controller
{
    public function index()
    {
        /** @var LogoutUserManager */
        $model = $this->model(LogoutUserManager::class)->assign($this->isValidRequest());
        $resp = $model->logout();
        if ($resp !== null) {
            $this->dispatcher->dispatch(new LogoutEvent($resp->getEntity()));
            $this->jsonResponse(['result' => 'success', 'msg' => 'success']);
        }
        $this->jsonResponse(['result' => 'error', 'msg' => $this->helper->showMessage('warning text-warning', 'Something goes wrong! plase contact the administrator!')]);
    }
}