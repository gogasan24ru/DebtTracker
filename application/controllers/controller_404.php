<?php
class Controller_error extends Controller_404
{}
class Controller_404 extends Controller
{
	function action_index()
	{
		//var_dump($_GET);die();
		$this->view->generate('404_view.php', 'template_view.php');
	}

	function action_custom()
        {
                //var_dump($_GET);die();
                $this->view->generate('404_view.php', 'template_view.php',$_GET['m']);
        }


	function action_login()
	{
		 $this->view->generate('404_view.php', 'template_view.php',"Неверное имя пользователя или пароль.");
	}

	function action_fileUpload()
        {
                 $this->view->generate('404_view.php', 'template_view.php',"File uploading error.");
        }

}
?>
