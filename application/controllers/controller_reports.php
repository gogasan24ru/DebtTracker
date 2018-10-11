<?php
class Controller_Reports extends Controller
{
	function __construct()
	{
		$this->model = new Model_Reports();
		$this->view = new View();
	}

	function action_journal(){
                $this->view->generate('reports_journal_view.php', 'template_view.php',$this->model->getJournal());
	}


	function action_myPays()
	{
		$this->view->generate('reports_myPays_view.php', 'template_view.php',$this->model->getMyPays());
	}

        function action_debtsToMe()
        {
                $this->view->generate('reports_table_view.php', 'template_view.php',$this->model->getMyDebts());
        }

        function action_creditToMe()
        {
		$this->view->generate('reports_table_view.php', 'template_view.php',$this->model->getMyCredits());
        }

	function action_participant()//https://enot.gogasan.tk/reports/participant?id=2
        {
                Route::Error("Function not implemented",500); die();
        }


	function action_undefined()
        {
		Route::Error("Attachment is undefined",404); die();
        }


	function action_payment()
	{
		if(!isset($_GET['id'])){Route::Error("Payment not selected"); die();}
		$paymentID=$_GET['id'];
		$this->view->generate('reports_payment_view.php', 'template_view.php',$this->model->getPayment($paymentID));
	}

	function action_summary()
	{	if(isset($_GET['id']))
		{
			Route::Error("Not implemented"); die();
			 $this->view->generate('reports_summary_view.php', 'template_view.php',$this->model->getSummary($_GET['id']));
		}
		else
		{
			$this->view->generate('reports_summary_view.php', 'template_view.php',$this->model->getSummary());
		}
	}
}
?>
