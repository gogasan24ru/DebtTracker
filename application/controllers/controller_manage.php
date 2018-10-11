<?php
class Controller_Manage extends Controller
{
	function __construct()
	{
		$this->model = new Model_Manage();
		$this->view = new View();
	}

	function action_selfDebtReclaiming(){$this->model->selfDebtReclaiming();}

	function action_removePay(){
		$this->model->removePay($_GET['id']);
		header("Location: ".$_SERVER['HTTP_REFERER']);
	}

	function action_accountName()
	{
		$this->model->setNewName($_POST['realname']);
		header('Location:/manage/account');
	}

        function action_accountPassword()
        {
		if($_POST['new2']===$_POST['new']){
		
	                if($this->model->setNewPassword($_POST['current'],$_POST['new2']))
			{
	        	        header('Location:/manage/account');
			} else  Route::Error("Password not updated",400);
		}else Route::Error("Password not confurmed",400);
        }



	function action_index()
	{
		$this->view->generate('manage_view.php', 'template_view.php');
	}

        function action_account()
        {
		$this->view->generate('manage_account_view.php', 'template_view.php',$this->model->getAccountInfo());
	}

	function action_reclaim()
	{
		if(isset($_GET['id'])){
			$this->model->reclaimDebt($_GET['id'],$_GET['comment']);
			die(header("Location:/manage/reclaim"));
		}
		$this->view->generate('manage_reclaim_view.php', 'template_view.php',$this->model->getDebts());
	}

	function action_addnew()
	{
		$data = $this->model->get_participants();
		$this->view->generate('manage_add_view.php', 'template_view.php',$data);
	}

	function action_add()
        {
		if(!is_numeric($_POST['price']))die(header('Location:/error/invalidInput'));
		//var_dump($_FILES);
		//die();
		if(($_FILES['attachment']['size']!=0)){
			$md5=hash_file('md5', $_FILES['attachment']['tmp_name']);
			$path="/var/www/html/attachments/".$md5.'.'.end(explode('.',basename($_FILES['attachment']['name'])));
			if (move_uploaded_file($_FILES['attachment']['tmp_name'], $path)) {
				//ok
				$data = $this->model->addPayment($_POST,$path);
			}
			else
			{
				header('Location:/error/fileUpload');
				return;	
			}
		}else
		$data = $this->model->addPayment($_POST);
		header('Location:/reports/summary');
                //$this->view->generate('manage_add_view.php', 'template_view.php');
        }

}
?>
