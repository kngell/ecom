<?php

declare(strict_types=1);
trait UserAccountTrait
{
    private function isUserAccountValid(UsersRequestsManager $request, string $hash) : array
    {
        $verification_result = [];
        if ($request->count() === 1) {
            $request = current($request->get_results());
            if (time() - $request->timestamp < 60 * 60 * 24) {
                if (password_verify($hash, $request->hash)) {
                    $verification_result['success'] = $this->helper->showMessage('success', 'Votre compte est vérifié et validé.' . PHP_EOL . ' Vous pouvez naviguer sur notre site en toute sécurité!');
                } else {
                    $verification_result['error'] = $this->helper->showMessage('warning', 'Invalid token!');
                }
            } else {
                $verification_result['error'] = $this->helper->showMessage('warning', 'Verification request has expired!');
            }
        } else {
            $verification_result['error'] = $this->helper->showMessage('danger', 'Invalid verification request!');
        }
        return $verification_result;
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
                        return [$verif_code, $user_request];
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

    private function getUserName() : string
    {
        if ($this->session->exists(CURRENT_USER_SESSION_NAME)) {
            return $this->session->get(CURRENT_USER_SESSION_NAME)['name'];
        }
        return '';
    }

    private function parseUrl(array $args) : array
    {
        $args = $this->token->urlSafeDecode(current($args));
        $args = explode(DS, $args);
        return $args;
    }
}