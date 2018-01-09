<?php

/**
 * SessionController
 *
 * Allows to authenticate users
 */
class SessionController extends CommonController
{
    public function initialize()
    {
        $this->view->setTemplateAfter('session');
        $this->tag->setTitle('Вход');
    }

    public function indexAction()
    {

    }

    /**
     * Register an authenticated user into session data
     *
     * @param Users $user
     */
    private function _registerSession(Users $user)
    {
        $this->session->set('auth', array(
            'id' => $user->id,
            'type' => $user->type,
            'org' => $user->org
        ));
    }

    /**
     * This action authenticate and logs an user into the application
     *
     */
    public function startAction()
    {
        if ($this->request->isPost()) {
            $email = $this->request->getPost('email');
            $password = $this->request->getPost('password');

            $user = Users::findFirst(array(
                "email = :email: AND password = :password:",
                'bind' => array('email' => $email, 'password' => sha1($password))
            ));

            if ($user != false) {
                $this->_registerSession($user);

                $this->view->user = $user;

                switch ($user->type) {
                    case Users::ADMIN: // ЧСИ
                        $controller = '/employees';
                        $action = '/index';
                        break;
                    case Users::COADMIN: // ПЧСИ
                    case Users::EMPLOYEE: // Служител
                    case Users::CLERK: // Деловодител
                        $controller = '/addresses';
                        $action = '/index';
                        break;
                    case Users::SUMMON: // Призовкар
                        $controller = '/app';
                        $action = '/index';
                        break;
                    default:
                        $controller = '/index';
                        $action = '/index';
                }

                return $this->response->redirect($controller.$action);
            }

            $this->flash->error('Грешен имейл или парола');
        }

        return $this->dispatcher->forward(
            [
                "controller" => 'session',
                "action"     => 'index'
            ]
        );
    }

    /**
     * Finishes the active session redirecting to the index
     *
     * @return unknown
     */
    public function endAction()
    {
        $this->session->remove('auth');

        return $this->dispatcher->forward(
            [
                "controller" => "session",
                "action"     => "index",
            ]
        );
    }
}
