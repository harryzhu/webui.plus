<?php
use Phalcon\Mvc\View;
use Phalcon\Mvc\Controller;

class SignupController extends Controller
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

    /**
     * Register new user and show message
     */
    public function registerAction()
    {

        $post = $this->request->getPost();

        // Store and check for errors
        $user        = new Users();
        $user->username  = $post['name'];
        $user->password  = genLoginPassword($post['password']);
        $user->email = $post['email'];
        $user->apikey = getAPIKey($user->username);
        // Store and check for errors
        $success = $user->save();

        // passing the result to the view
        $this->view->success = $success;

        if ($success) {
           $this->response->redirect("/signin/");
        } else {
            $this->response->redirect("/signup/");
        }

        // passing a message to the view
        $this->view->disable();
    }
}
