<?php

declare(strict_types=1);

class ForgotPasswordAjaxController extends Controller
{
    public function index(array $args = []) : void
    {
        /** @var ForgotPasswordManager */
        $model = $this->model(ForgotPasswordManager::class)->assign($this->isValidRequest());
        $this->isIncommingDataValid(m: $model, ruleMethod:'forgot', newKeys: [
            'email' => 'forgot_email',
        ]);
        if ($model->validationPasses()) {
            $user = $model->forgotPw();
            if ($this->isUserRequestValid($user)) {
                $this->jsonResponse(['result' => 'success', 'msg' => '']);
            }
        }
    }

    private function isUserRequestValid(ForgotPasswordManager $user) : bool
    {
        if ($user->count() === 1) {
            if ($user->getEntity()->{'getVerified'}() === 0) {
                if (current($user->get_results())->number <= MAX_PW_RESET_REQUESTS_PER_DAY) {
                    $user_request = $this->model(UsersRequestsManager::class);
                    $hash = password_hash($this->token->generate(16), PASSWORD_DEFAULT);
                    $user_request = $user_request->assign([
                        'hash' => $hash,
                        'timestamp' => time(),
                        'userID' => $user->getEntity()->{'getUserID'}(),
                    ])->save();
                    if ($user_request->count() > 1) {
                        $this->dispatcher->dispatch(new ForgotPasswordEvent($user->getEntity()));
                        return true;
                    } else {
                        $this->jsonResponse(['error' => 'error', 'msg' => $this->helper->showMessage('warning', 'Failed to proceed request!')]);
                    }
                } else {
                    $this->jsonResponse(['error' => 'error', 'msg' => $this->helper->showMessage('warning', 'Too Many resquest in a day!')]);
                }
            } else {
                $this->jsonResponse(['error' => 'success', 'msg' => $this->helper->showMessage('success', 'Email already Verified!')]);
            }
        }
        $this->jsonResponse(['error' => 'success', 'msg' => $this->helper->showMessage('success', 'You do not have an account')]);
    }
}