<?php

/**
 * SessionController
 *
 * Allows to authenticate users
 */
class SessionController extends ControllerBase
{
    public function initialize()
    {
        $this->view->setTemplateAfter('session');
        $this->tag->setTitle('Вход');
        //parent::initialize();
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
                return $this->dispatcher->forward(
                    [
                        "controller" => "index",
                        "action"     => "index",
                    ]
                );
            }

            $this->flash->error('Грешен имейл или парола');
        }

        return $this->dispatcher->forward(
            [
                "controller" => "session",
                "action"     => "index",
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
