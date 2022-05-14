<?php

declare(strict_types=1);

class VerifyUserAccountController extends Controller
{
    public function index()
    {
        $verifyAccountFrm = $this->container->make(VerifyUserAccountForm::class)->createForm('verify');
        $this->render('users' . DS . 'account' . DS . 'verifyUserAccount', [
            'verifyForm' => $verifyAccountFrm,
        ]);
    }

    public function verify(array $args = []) : void
    {
        /** @var EmailVerificationManager */
        $model = $this->model(EmailVerificationManager::class)->assign($this->isPostRequest());
        $this->isIncommingDataValid(m: $model, ruleMethod:'email', newKeys: [
            'email' => 'forgot_email',
        ]);
        if ($model->validationPasses()) {
            $user = $model->getUser();
            list($hash, $user_request) = $this->isUserRequestValid($user);
            $emailVerifEvent = new EmailVerificationEvent($user->getEntity());
            $link = HOST . 'validate_account' . DS . $user_request->getLastID() . DS . $this->token->urlSafeEncode($hash);
            $emailVerifEvent->setLink("$link");
            $this->dispatcher->dispatch($emailVerifEvent);
            $this->jsonResponse(['result' => 'success', 'msg' => '']);
        }
    }

    private function isUserRequestValid(EmailVerificationManager $user) : bool|array
    {
        if ($user->count() === 1) {
            if ($user->getEntity()->{'getVerified'}() === 0) {
                if (current($user->get_results())->number <= MAX_PW_RESET_REQUESTS_PER_DAY) {
                    $user_request = $this->model(UsersRequestsManager::class);
                    $verif_code = $this->token->generate(16);
                    $hash = password_hash($verif_code, PASSWORD_DEFAULT);
                    /** @var UsersRequestsManager */
                    $user_request = $user_request->assign([
                        'hash' => $hash,
                        'timestamp' => time(),
                        'userID' => $user->getEntity()->{'getUserID'}(),
                    ])->save();
                    if ($user_request->count() > 1) {
                        return [$hash, $user_request];
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