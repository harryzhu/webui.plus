<?php
use Phalcon\Mvc\View;
use Phalcon\Mvc\Controller;

class SigninController extends Controller
{
    /**
     * Show form to register a new user
     */
    public function indexAction()
    {
        $this->view->setRenderLevel(
            View::LEVEL_ACTION_VIEW
        );
    }

    
    public function loginAction()
    {

        $this->view->disable();

        $post = $this->request->getPost();

        $frmusername  = $post['name'];
        $frmpassword  = genLoginPassword($post['password']);
        

        $user = new Users();
        $me = $user->LoginCheck($frmusername,$frmpassword);

        if ($me) {
            if ($me != false) {

            $this->_registerSession($me);
            $this->_registerCookies($me);
        }
            $this->response->redirect("/my/");
        } else {
            $this->response->redirect("/signin/");
        }

    }

    private function _registerSession($user)
    {
        $this->session->set(
            'me',
            [
                'id'   => $user->id,
                'username' => $user->username,
                'email' => $user->email,
                'apikey' => $user->apikey,
                'is_admin' => $user->is_admin,
                'is_member' => $user->is_member,
            ]
        );
        global $_G;
        $_G['me']=$this->session->get('me');
    }

    private function _registerCookies($user){
        $this->cookies->set("me",serialize($user), time() + 7 * 86400);
        $this->cookies->send();
    }

    public function logoutAction()
    {

        $this->view->disable();

        $this->session->destroy();
        $this->response->redirect('index/index');
    }

}
