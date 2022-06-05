<?php

declare(strict_types=1);

class RegisterUserWithAjaxController extends Controller
{
    public function index(array $args = []) : void
    {
        /** @var RegisterUserManager */
        $model = $this->model(RegisterUserManager::class)->assign($this->isValidRequest());
        $this->isIncommingDataValid(m: $model, ruleMethod:'users', newKeys: [
            'email' => 'reg_email',
            'password' => 'reg_password',
        ]);
        $model = $this->uploadFiles($model);
        if ($model->validationPasses()) {
            $model->setLastID($model->register()->count());
            $this->dispatcher->dispatch(new RegistrationEvent($model->getEntity()));
            $this->jsonResponse(['result' => 'success', 'msg' => '']);
        }
    }
}