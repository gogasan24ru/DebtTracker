<?php
class Controller_Auth extends Controller
{
	function __construct()
	{
		$this->model = new Model_Auth();
		$this->view = new View();
	}

	function action_logout()
        {
		$this->model->logout();
        }


	function action_login()
	{
		$this->view->generate('auth_login_view.php', 'template_view.php');
	}

	function action_loginForm()
        {
                //$this->view->generate('auth_login_view.php', 'template_view.php');
		$username=$_POST['username'];
		$password=$_POST['password'];
		if($this->model->authSuccess($username,$password)){
			header('Location:/reports/summary');
		} else {
			header('Location:/error/login');
		}
        }

}
?>
