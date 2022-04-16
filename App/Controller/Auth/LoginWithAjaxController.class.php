<?php

declare(strict_types=1);
class LoginWithAjaxController extends Controller
{
    public function index()
    {
        if ($this->request->exists('post') && $this->loginFrm->canHandleRequest()) {
            $data = $this->request->get();
            if ($data['csrftoken'] && $this->token->validateToken($data['csrftoken'], $data['frm_name'])) {
                $model = $this->model(AuthenticationManager::class)->assign($data);
                $this->isIncommingDataValid($model);
                if (list($user, $number) = $model->authenticate()) {
                    $this->isLoginAttempsValid($number);
                    $this->isPasswordValid($user);
                    $resp = $user->login($this->isRememberingLogin());
                    $this->jsonResponse(['result' => 'success', 'msg' => FH::showMessage('warning text-success', 'Welcome')]);
                } else {
                    $this->jsonResponse(['result' => 'error', 'msg' => FH::showMessage('warning text-center', 'Your user account does not exist! Please register')]);
                }
            } else {
                $this->jsonResponse(['result' => 'error', 'msg' => FH::showMessage('danger text-center', 'Invalid Token! Please try again')]);
            }
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

    private function isIncommingDataValid(ModelInterface $m) : void
    {
        method_exists('Form_rules', 'login') ? $m->validator(Form_rules::login()) : '';
        if (!$m->validationPasses()) {
            $this->jsonResponse(['result' => 'error-field', 'msg' => $m->getErrorMessages()]);
        }
    }

    private function isLoginAttempsValid(int $nbLoginAttempt)
    {
        if ($nbLoginAttempt > MAX_LOGIN_ATTEMPTS_PER_HOUR) {
            $this->jsonResponse(['result' => 'error', 'msg' => FH::showMessage('danger text-center', 'Too many login attempts in this hour')]);
        }
    }

    private function isPasswordValid(ModelInterface $user)
    {
        if (!password_verify($user->getEntity()->{'getPassword'}(), $user->password)) {
            $attempt = [
                'userID' => $user->userID,
                'timestamp' => time(),
                'ip' => $this->request->getServer('REMOTE_ADDR'),
            ];
            $this->model(LoginAttemptsManager::class)->assign($attempt)->save();
            $this->jsonResponse(['result' => 'error', 'msg' => FH::showMessage('danger text-center', 'Your password is incorrect, Please try again!')]);
        }
    }
}