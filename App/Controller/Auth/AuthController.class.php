<?php

declare(strict_types=1);
class AuthController extends Controller
{
    public function ajaxLogin()
    {
        if ($this->request->exists('post') && $this->loginFrm->canHandleRequest()) {
            $data = $this->request->get();
            if ($data['csrftoken'] && $this->token->validateToken($data['csrftoken'], $data['frm_name'])) {
                $model = $this->model(AuthenticationManager::class)->authenticate($data);
                method_exists('Form_rules', 'login') ? $model->validator($data, Form_rules::login()) : '';
                if ($model->validationPasses()) {
                    list($user, $number) = $model->loginAttemps($data['email']);
                    if ($user) {
                        if ($user->verified) {
                            if ($number <= MAX_LOGIN_ATTEMPTS_PER_HOUR) {
                                $loginAtempt = $this->container->make(LoginAttemptsManager::class);
                                if (password_verify($data['password'], $user->password)) {
                                    $remember = (isset($data['remember_me']) && $data['remember_me'] === 'on') ? true : false;
                                    if ($user->login($remember)) {
                                        $loginAtempt->delete(['userID' => $user->userID]);
                                        if (isset($data['checkout'])) {
                                            $this->jsonResponse(['result' => 'success', 'msg' => $data['checkout']]);
                                        }
                                        $this->jsonResponse(['result' => 'success', 'msg' => '']);
                                    } else {
                                        $this->jsonResponse(['result' => 'error', 'msg' => FH::showMessage('danger text-center', 'Failed to login')]);
                                    }
                                } else {
                                    $loginAtempt->userID = $user->userID;
                                    $loginAtempt->timestamp = time();
                                    $loginAtempt->ip = $_SERVER['REMOTE_ADDR'];
                                    if ($loginAtempt->save()) {
                                        $this->jsonResponse(['result' => 'error', 'msg' => FH::showMessage('danger text-center', 'Your password is incorrect')]);
                                    } else {
                                        $this->jsonResponse(['result' => 'error', 'msg' => FH::showMessage('warning text-center', 'Fails to verify login attempts')]);
                                    }
                                    $loginAtempt = null;
                                }
                            } else {
                                $this->jsonResponse(['result' => 'error', 'msg' => FH::showMessage('danger text-center', 'Too many login attempts in this hour')]);
                            }
                        } else {
                            $this->jsonResponse(['result' => 'error', 'msg' => FH::showMessage('danger text-center', 'Your email is not verified')]);
                        }
                    } else {
                        $this->jsonResponse(['result' => 'error', 'msg' => FH::showMessage('warning text-center', 'Your user account does not exist! Please register')]);
                    }
                } else {
                    $this->jsonResponse(['result' => 'error-field', 'msg' => $model->getErrorMessages()]);
                }
            } else {
                $this->jsonResponse(['result' => 'error', 'msg' => FH::showMessage('danger text-center', 'Invalid Token! Please try again')]);
            }
        }
    }

    //=======================================================================
    //Forgot password
    //=======================================================================
    public function forgotPassword()
    {
        if ($this->request->exists('post')) {
            $data = $this->request->get();
            if ($data['csrftoken'] && $this->token->validateToken($data['csrftoken'], $data['frm_name'])) {
                $this->model_instance->assign($data);
                method_exists('Form_rules', 'forgot') ? $this->model_instance->validator($data, Form_rules::forgot()) : '';
                if ($this->model_instance->validationPasses()) {
                    $user = $this->model_instance->getUserRequests($data['email'], 1);
                    if ($user && (int) $user[0]->count() === 1) {
                        if ($user[1] < MAX_PW_RESET_REQUESTS_PER_DAY) {
                            $request = $this->container->make(UsersRequestsManager::class)->UsersRequests;
                            $code = $request->get_unique('hash');
                            $request->hash = password_hash($code, PASSWORD_DEFAULT);
                            $request->userID = $user[0]->userID;
                            $request->type = 1;
                            $request->timestamp = time();
                            if ($lastID = $request->save()['saveID']->get_lastID()) {
                                $msg = '<a href="http://localhost/kngell/users/resetpassword/' . $lastID . '/' . $this->token->urlSafeEncode($code) . '">Click to reset your password.</a>';
                            // if (H_Email::sendEmail($data['email'], $user[0]->name, 'Password Reset', $msg)) {
                                //     $this->jsonResponse(['result' => 'success', 'msg' => FH::showMessage('success text-center', 'An Email has been sent if an account with that email exist.')]);
                                // } else {
                                //     $this->jsonResponse(['result' => 'error', 'msg' => FH::showMessage('warning text-center', 'Failed to send email')]);
                                // }
                            } else {
                                $this->jsonResponse(['result' => 'error', 'msg' => FH::showMessage('danger text-center', 'Failed to create request in database! Plase try again.')]);
                            }
                        } else {
                            $this->jsonResponse(['result' => 'error', 'msg' => FH::showMessage('danger text-center', 'Too many requests in the last 24 hours.')]);
                        }
                    } else {
                        $this->jsonResponse(['result' => 'error', 'msg' => FH::showMessage('secondary text-center', 'An Email has been sent if an account with that email exist.')]);
                    }
                } else {
                    $newKeys = ['email' => 'forgot_email'];
                    $this->jsonResponse(['result' => 'error-field', 'msg' => $this->response->transform_keys($this->model_instance->getErrorMessages(), $newKeys)]);
                }
            } else {
                $this->jsonResponse(['result' => 'error', 'msg' => FH::showMessage('danger text-center', 'Invalid Token! Please try again')]);
            }
        }
    }

    // =======================================================================
    // Reset Password
    // =======================================================================
    public function resetpassword()
    {
        if ($this->request->exists('post')) {
            $data = $this->request->get();
            if ($data['csrftoken'] && $this->token->validateToken($data['csrftoken'], $data['frm_name'])) {
                $this->model_instance->assign($data);
                $this->model_instance->setConfirm($data['cpassword']);
                method_exists('Form_rules', 'resetPass') ? $this->model_instance->validator($data, Form_rules::resetPass()) : '';
                if ($this->model_instance->validationPasses()) {
                    $user_data = $data['user_data'] ? explode('/', $data['user_data']) : [];
                    $hash = array_pop($user_data);
                    $id = array_pop($user_data);
                    if (!empty($id) && !empty($hash)) {
                        // $user_request = (new UsersRequestsManager())->getDetails($id);
                        // if ($user_request && $user_request->count() === 1) {
                        //     if (password_verify($this->token->urlSafeDecode($hash), $user_request->hash)) {
                        //         if ($user_request->timestamp >= time() - PASSWORD_RESET_REQUEST_EXPIRY_TIME) {
                        //             $hash = password_hash($data['password'], PASSWORD_DEFAULT);
                        //             if ($this->model_instance->update(['userID' => $user_request->userID], ['password' => $hash])) {
                        //                 $user_request->delete('', ['userID' => $user_request->userID, 'type' => 1]);
                        //                 $this->jsonResponse(['result' => 'success', 'msg' => FH::showMessage('success text-center', 'Your password has been updated.')]);
                        //             } else {
                        //                 $this->jsonResponse(['result' => 'error', 'msg' => FH::showMessage('danger text-center', 'Failed to update password')]);
                        //             }
                        //         } else {
                        //             $this->jsonResponse(['result' => 'error', 'msg' => FH::showMessage('danger text-center', 'This request has expired! Please try again.')]);
                        //         }
                        //     } else {
                        //         $this->jsonResponse(['result' => 'error', 'msg' => FH::showMessage('danger text-center', 'Invalid password reset request')]);
                        //     }
                        // } else {
                        //     $this->jsonResponse(['result' => 'error', 'msg' => FH::showMessage('danger text-center', 'Invalid password reset request')]);
                        // }
                    } else {
                        $this->jsonResponse(['result' => 'error', 'msg' => FH::showMessage('danger text-center', 'Your Link is corrupt! Please request a new password reset link.')]);
                    }
                } else {
                    $newKeys = ['password' => 'r_password', 'cpassword' => 'r_cpassword'];
                    $this->jsonResponse(['result' => 'error-field', 'msg' => $this->response->transform_keys($this->model_instance->getErrorMessages(), $newKeys)]);
                }
            } else {
                $this->jsonResponse(['result' => 'error', 'msg' => FH::showMessage('danger text-center', 'Invalid Token! Please try again')]);
            }
        }
    }

    //=======================================================================
    //Delete account
    //=======================================================================
    public function deleteUserAccount()
    {
        if ($this->request->exists('post')) {
            $data = $this->request->get();
            if ($data['csrftoken'] && $this->token->validateToken($data['csrftoken'], $data['frm_name'])) {
                if ($this->session->exists(CURRENT_USER_SESSION_NAME) && isset(AuthManager::$currentLoggedInUser)) {
                    $user = AuthManager::$currentLoggedInUser;
                    $user->id = $user->userID;
                    $user->deleted = 1;
                    if ($user->save()) {
                        if ($user->deleteUserAccount($user)) {
                            $this->jsonResponse(['result' => 'success', 'msg' => '']);
                        } else {
                            $this->jsonResponse(['result' => 'error', 'msg' => FH::showMessage('danger text-center', 'Failed to delete account')]);
                        }
                    } else {
                        $this->jsonResponse(['result' => 'error', 'msg' => FH::showMessage('danger text-center', 'Failed to delete account')]);
                    }
                    echo json_encode(['ok']);
                } else {
                    $this->jsonResponse(['result' => 'error', 'msg' => FH::showMessage('danger text-center', 'Your are not logged in')]);
                }
            }
        }
    }

    //=======================================================================
    //Check for remember me cookies
    //=======================================================================

    public function rememberCheck(array $arg = [])
    {
        if ($this->request->exists('post')) {
            if ($userdata = $this->container->make(AuthManager::class)->rememberMe_checker()) {
                $this->jsonResponse(['result' => 'success', 'msg' => $userdata]);
            } else {
                $this->jsonResponse(['result' => 'error', 'msg' => '']);
            }
        }
    }

    //=======================================================================
    //Register
    //=======================================================================

    public function ajaxRegister()
    {
        if ($this->request->exists('post')) {
            $data = $this->request->get();
            if ($data['csrftoken'] && $this->token->validateToken($data['csrftoken'], $data['frm_name'])) {
                $model = $this->container->make(AuthManager::class)->assign($data);
                $model->setConfirm($data['password']);
                $model->cpassword = $data['cpassword'];
                $model->terms = !isset($data['terms']) ? '' : $data['terms'];
                method_exists('Form_rules', 'users') ? $model->validator($data, Form_rules::users()) : '';
                $file = $this->uploadHelper->upload_files($this->request->getFiles(), $model, $this->container);
                if ($file['success']) {
                    if ($model->validationPasses()) {
                        if ($lastID = $model->register()) {
                            $msgsuccess = FH::showMessage('success text-center', ' <p>Welcome!</p> <p>Please check your email to confirm your account</p>');
                            if (isset($data['email'])) {
                                if ($this->sendValidationEmailRequest($data['email'], $model)) {
                                    $this->jsonResponse(['result' => 'success', 'msg' => $msgsuccess]);
                                } else {
                                    $this->jsonResponse(['result' => 'error', 'msg' => FH::showMessage('warning', 'Varification link cannot be sent! Please click here to request another verification link!')]);
                                }
                            }
                        } else {
                            $this->jsonResponse(['result' => 'error', 'msg' => FH::showMessage('warning', 'erreur server!contacter l\'admin')]);
                        }
                    } else {
                        $newKeys = ['email' => 'reg_email', 'password' => 'pass', 'cpassword' => 'cpass'];
                        $this->jsonResponse(['result' => 'error-field', 'msg' => $this->response->transform_keys($model->getErrorMessages(), $newKeys)]);
                    }
                } else {
                    $this->jsonResponse(['result' => 'error-field', 'msg' => $file['msg']]);
                }
            } else {
                $this->jsonResponse(['result' => 'error', 'msg' => FH::showMessage('danger text-center', 'Permission denied! Invalid CSRF Token! Please try again!')]);
            }
        }
    }

    //=======================================================================
    //Verifiy Email
    //=======================================================================

    public function verifyEmail($data)
    {
        if ($data) {
            $hash = array_pop($data);
            $id = array_pop($data);
            $request = $this->container->make(UsersRequestsManager::class)->getDetails($id);
            if ($request && (int) $request->count() === 1) {
                if ($request->timestamp > time() - 60 * 60 * 24) {
                    if (password_verify($this->token->urlSafeDecode($hash), $request->hash)) {
                        $this->model_instance->id = $request->userID;
                        $this->model_instance->verified = 1;
                        if ($resp = $this->model_instance->update(['userID' => $request->userID], ['verified' => 1])) {
                            $msg = '<h2>Account Verified</h2>';
                            $request->delete('', ['type' => 0, 'userID' => $request->userID]);
                        } else {
                            $msg = '<h2>Error when verifying Email!</h2>';
                        }
                    } else {
                        $msg = '<h2>Invalid Verification Request</h2>';
                    }
                } else {
                    $msg = '<h2>Verification request expired</h2><a href="' . PROOT . 'home' . DS . 'users' . 'emailverification' . '">Click here to send another one</a>';
                }
            } else {
                $msg = '<h2>Invalid Verification Request</h2>';
            }
            $request = null;
        // Rooter::redirect('users' . DS . 'emailverified' . DS . $msg);
        } else {
            // Rooter::redirect('restricted');
        }
    }

    //=======================================================================
    //Send Validation Email Request
    //=======================================================================

    public function sendValidationEmailRequest($email = '', ?AuthManager $m = null)
    {
        $user = $m->getUserRequests($email, 'email');
        if ($user) {
            if ((int) $user[0]->verified === 0) {
                if ($user[1] <= MAX_PW_RESET_REQUESTS_PER_DAY) {
                    $request = $this->container->make(UsersRequestsManager::class);
                    $request->userID = $user[0]->userID;
                    $verifiCode = $request->get_unique('hash');
                    $request->timestamp = time();
                    $request->hash = password_hash($verifiCode, PASSWORD_DEFAULT);
                    if ($resp = $request->save()) {
                        // if (H_Email::sendEmail($email, $user[0]->name, 'Email Verification', '<a href="http://localhost/kngell/auth/verifyEmail/' . $resp->get_lastID() . '/' . $this->token->urlSafeEncode($verifiCode) . '">Click to verify your email</a>')) {
                        //     return true;
                        // }
                    }
                }
            }
        }

        return false;
    }

    //=======================================================================
    //Register - Email verification
    //=======================================================================

    public function emailVerified($userInfos)
    {
        $email = array_pop($userInfos);
        $salt = array_pop($userInfos);
        // $this->view_instance->setPageTitle('Email verification');
        $user = $this->model_instance['users']->getDetails($email, 'email');
        $msg = file_get_contents(FILES . 'template' . DS . 'home' . DS . 'LR' . DS . 'verification_result.php');
        if ($user && $user->salt === $salt) {
            $msg = str_replace('{{class}}', 'text-success', $msg);
            $msg = str_replace('{{accueil}}', 'Congratulation!! Your email is verified!', $msg);
            $msg = str_replace('{{message}}', 'You can now logged in and enjoy our services. If you have some questions, please contact our <a href="mailto:contact@kngell.com"><sapn>client administrator.</a></sapn>', $msg);
            $user->confirmEmail($email);
        } else {
            $msg = str_replace('{{class}}', 'text-warning ', $msg);
            $msg = str_replace('{{accueil}}', 'An error ocurred!', $msg);
            $msg = str_replace('{{message}}', 'Please try later our contact our <a href="mailto:admin@kngell.com"><span>admin.</span></a>.', $msg);
        }
        $this->view_instance->verification_result = $msg;
        $this->view_instance->render('users' . DS . 'emailVerified');
    }

    //=======================================================================
    //Change Password
    //=======================================================================

    public function changePassword()
    {
        if ($this->request->exists('post')) {
            $data = $this->request->get();
            if ($data['csrftoken'] && $this->token->validateToken($data['csrftoken'], $data['frm_name'])) {
                $user = AuthManager::currentUser();
                $user->curpass = $data['curpass'];
                $user->newpass = $data['newpass'];
                $user->cnewpass = $data['cnewpass'];
                $user->setConfirm($data['newpass']);
                $user->assign($data);
                $user->validator($data, Form_rules::change_pass_admin_user());
                if ($user->validationPasses()) {
                    if (password_verify($user->curpass, $user->password)) {
                        $user->id = $user->userID;
                        $user->password = password_hash($user->newpass, PASSWORD_DEFAULT);
                        unset($user->curpass, $user->newpass, $user->cnewpass);
                        if ($user->save('userID')) {
                            $user->notify($user->userID, 'admin', 'Password changed');
                            $this->jsonResponse(['result' => 'success', 'msg' => FH::showMessage('success', 'Password is change successsfully!!')]);
                        } else {
                            $this->jsonResponse(['result' => 'error', 'msg' => FH::showMessage('danger', 'Something goes wrong!! <br>Please contact admin')]);
                        }
                    } else {
                        $user->addErrMessage('curpass', 'Your Current password is incorrect');
                        $this->jsonResponse(['result' => 'error-field', 'msg' => $user->getErrorMessages()]);
                    }
                } else {
                    $this->jsonResponse(['result' => 'error-field', 'msg' => $user->getErrorMessages()]);
                }
            }
        }
    }

    public function resetpass()
    {
        // $this->view_instance->set_pageTitle('Reset password');
        $this->view_instance->render('users' . DS . 'resetpass');
    }

    public function ajaxresetpass()
    {
        //dd($email, $token);
        if ($this->request->exists('post')) {
            $data = $this->request->get();
            if ($data['csrftoken'] && $this->token->validateToken($data['csrftoken'], $data['frm_name'])) {
                $this->model_instance->assign($data);
                $this->model_instance->setConfirm($data['password']);
                $this->model_instance->cpassword = $data['cpassword'];
                $this->model_instance->validator($data, Form_rules::resetPass());
                if ($this->model_instance->validationPasses()) {
                    $url = explode('/', $data['url_data']);
                    $token = array_pop($url);
                    $email = array_pop($url);
                    $user = $this->model_instance->getDetails($email, 'email');
                    if (!$user->count() > 0) {
                        $this->jsonResponse(['result' => 'error', 'msg' => FH::showMessage('warning', 'Votre email n\'existe pas dans notre system!<br>Merci de vous enregistrer')]);
                    } elseif ($token == $user->token) {
                        $user->token = '';
                        $user->id = $user->userID;
                        $datetime1 = new DateTime('now');
                        $datetime2 = new Datetime($user->token_expire);
                        if ($datetime1 < $datetime2) {
                            $user->password = password_hash($data['password'], PASSWORD_DEFAULT);
                            if ($user->save()) {
                                $this->jsonResponse(['result' => 'success', 'msg' => FH::showMessage('success text-center', 'le mot de pass a été changé avec success!<br> Vous pouvez continuer votre navigation')]);
                            } else {
                                $this->jsonResponse(['result' => 'error', 'msg' => FH::showMessage('danger', 'une erreur a été rencontrée sur le serveur!')]);
                            }
                        } else {
                            $user->save();
                            $this->jsonResponse(['result' => 'error', 'msg' => FH::showMessage('warning', 'le délai a expiré. Veuillez recommencer!')]);
                        }
                    } else {
                        $this->jsonResponse(['result' => 'error', 'msg' => FH::showMessage('danger text-center', 'Votre lien n\'est pas valide!<br> Veuillez redemander un autre lien!')]);
                    }
                } else {
                    $newKeys = ['password' => 'password_ch'];
                    $this->jsonResponse(['result' => 'error-field', 'msg' => $this->response->transform_keys($this->model_instance['users']->getErrorMessages(), $newKeys)]);
                }
            } else {
            }
        }

        //dd($this);
    }

    public function logout($members = '')
    {
        if ($user = AuthManager::currentUser()) {
            if (AuthManager::currentUser()->logout()) {
                if ($members) {
                    // Rooter::redirect('');
                }
                if ($this->session->exists(REDIRECT)) {
                    $this->session->delete(REDIRECT);
                    $this->jsonResponse(['result' => 'success', 'msg' => 'redirect']);
                }
                $this->jsonResponse(['result' => 'success', 'msg' => 'no_redirect']);
            }
        }
        $this->jsonResponse(['result' => 'error', 'msg' => 'Something goes wrong']);
    }
}