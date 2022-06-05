<?php

declare(strict_types=1);

class LoginUserWithAjaxController extends Controller
{
    public function index()
    {
        /** @var AuthenticateUserManager */
        $model = $this->model(AuthenticateUserManager::class)->assign($this->isValidRequest());
        $this->isIncommingDataValid(m: $model, ruleMethod:'login');
        if (list($user, $number) = $model->authenticate()) {
            $this->isLoginAttempsValid($number);
            $this->isPasswordValid($user);
            $resp = $user->login($this->isRememberingLogin(), [
                'password' => $model->getEntity()->{'getPassword'}(),
            ]);
            if ($resp !== null) {
                $this->dispatcher->dispatch(new LoginEvent($resp->getEntity()));
                $this->jsonResponse(['result' => 'success', 'msg' => 'success']);
            }
            $this->jsonResponse(['result' => 'error', 'msg' => $this->helper->showMessage('warning text-warning', 'Something goes wrong! plase contact the administrator!')]);
        } else {
            $this->jsonResponse(['result' => 'error', 'msg' => $this->helper->showMessage('warning text-center', 'Your user account does not exist! Please register')]);
        }
    }

    public function rememberMeCheck(array $args = [])
    {
        /** @var AuthenticateUserManager */
        $model = $this->model(AuthenticateUserManager::class)->assign($this->isValidRequest());
        $resp = $model->rememberMeCheck();
        if (!empty($resp)) {
            $this->jsonResponse(['result' => 'success', 'msg' => $resp]);
        } else {
            $this->jsonResponse(['result' => 'error', 'msg' => '']);
        }
    }

    private function isRememberingLogin() : bool|string
    {
        $remember_me = $this->request->get('remember_me');
        if ($remember_me) {
            return $remember_me;
        }
        return false;
    }

    private function isLoginAttempsValid(int $nbLoginAttempt)
    {
        if ($nbLoginAttempt > MAX_LOGIN_ATTEMPTS_PER_HOUR) {
            $this->jsonResponse(['result' => 'error', 'msg' => $this->helper->showMessage('danger text-center', 'Too many login attempts in this hour')]);
        }
    }

    private function isPasswordValid(Model $user)
    {
        if (!password_verify($user->getEntity()->{'getPassword'}(), $user->password)) {
            try {
                $this->model(LoginAttemptsManager::class)->assign([
                    'userID' => $user->userID,
                    'timestamp' => time(),
                    'ip' => $this->request->getServer('REMOTE_ADDR'),
                ])->save();
                $this->jsonResponse(['result' => 'error', 'msg' => $this->helper->showMessage('danger text-center', 'Your password is incorrect, Please try again!')]);
            } catch (\Throwable $th) {
                throw new DatabaseConnexionExceptions($th->getMessage(), $th->getCode());
            }
        }
    }
}