<?php

declare(strict_types=1);

class UserAccountController extends Controller
{
    use UserAccountTrait;

    public function index(array $args = [])
    {
        $this->render('users' . DS . 'account' . DS . 'verifyUserAccount', [
            'verifyForm' => $this->container->make(VerifyUserAccountForm::class)->createForm('verify'),
        ]);
    }

    public function verify(array $args = []) : void
    {
        /** @var EmailVerificationManager */
        $user = $this->model(EmailVerificationManager::class)->assign($this->isPostRequest());
        $this->isIncommingDataValid(m: $user, ruleMethod:'email', newKeys: [
            'email' => 'verify_email',
        ]);
        if ($user->validationPasses()) {
            $user = $user->getUser();
            list($verif_code, $user_request) = $this->isUserRequestValid($user);
            $emailVerifEvent = new VerifyUserAccountEvent($user->getEntity());
            $link = HOST . DS . 'validate_account' . DS . $this->token->urlSafeEncode($user_request->getLastID() . DS . $user->getEntity()->{'getEmail'}() . DS . $verif_code);
            $this->dispatcher->dispatch($emailVerifEvent->setLink("$link")->setUserName($user->getEntity()->{'getFirstName'}())->setHost(['host' => HOST]));
            $this->jsonResponse(['result' => 'success', 'msg' => '']);
        }
    }

    public function validate(array $args = [])
    {
        $args = $this->parseUrl($args);
        /** @var UsersRequestsManager */
        $request = $this->container->make(UsersRequestsManager::class)->getRequest((int) $args[0]);
        $msg = $this->isUserAccountValid($request, $args[2]);
        if (!empty($msg)) {
            $this->render('users' . DS . 'account' . DS . 'validateUserAccount', ['msg' => current($msg)]);
        }
        if (isset($msg['success'])) {
            /** @var ValidateUserAccountEvent */
            $userAccountEvent = new ValidateUserAccountEvent($request->assign((array) current($request->get_results()))->getEntity());
            $userAccountEvent->setPreheadText('Validation de votre compte.')
                ->setMsgTitle('Validation de votre compte.')
                ->setMsgBody('Félicitations! Votre compte a été validé avec Succès')
                ->setBtnText('Commencer la navisation')
                ->setLink(HOST)
                ->setMsgEnd('Merci d\'être un bon client. Profitez de nos offres!!')
                ->setUserName($this->getUserName())
                ->setEmail($args[1])
                ->setHost(['host' => HOST]);
            $this->dispatcher->dispatch($userAccountEvent);
        }
    }
}