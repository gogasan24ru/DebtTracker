<?php
class Model_Reports extends Model
{
	public function getJournal(){
	        global $db;
                check_auth();
		return $db->getAll("SELECT * FROM logs");
	}

	public function getMyPays(){
                global $db;
                check_auth();

		return $db->getAll("SELECT * FROM payments WHERE owner=?s",$_SESSION['selfid']);

	}
	public function get_participants()
	{
		global $db;
		session_start();
//		require 'application/config.php';
//		$db = new SafeMySQL(array('user' => $DBUSER,'pass' => $DBPASS, 'db' => $DB,'charset' => 'utf8'));
		check_auth();
		$data['avaliableParticipants']=Array();
		$users = $db->getAll("SELECT id,realname FROM users");

	        function self_filter($var){return($var['id']!=$_SESSION['selfid']);} ///$_SESSION['selfid']
		$users = array_filter($users,"self_filter");
		//var_dump($users);
		$data['avaliableParticipants']=$users;

		return $data;
	}

	public function getMyCredits(){
		global $db;
                check_auth();
		if (isset($_GET['id']))
		{
			$credits = $db->getAll("SELECT * FROM debts WHERE debtor = ?s AND creditor = ?s",$_SESSION['selfid'],$_GET['id']);
		}
		else
		{
			$credits = $db->getAll("SELECT * FROM debts WHERE debtor = ?s",$_SESSION['selfid']);
		}
		foreach ($credits as $key => $rec)
		{
			$payment = $db->getAll("SELECT description, date FROM payments WHERE id = ?s", $rec['source'])[0];
			$credits[$key]['description']=$payment['description'];
			$credits[$key]['date']=$payment['date'];
			$user = $db->getAll("SELECT realname FROM users WHERE id = ?s", $rec['creditor'])[0];
                        $credits[$key]['creditor']=$user['realname'];
                        $user = $db->getAll("SELECT realname FROM users WHERE id = ?s", $rec['debtor'])[0];
                        $credits[$key]['debtor']=$user['realname'];
		}

		$data['title']="Мои долги";
		$data['comment']="";
		$data['payload']=$credits;

		return $data;
	}
	public function getMyDebts(){
                global $db;
                check_auth();
                if (isset($_GET['id']))
                {
                        $credits = $db->getAll("SELECT * FROM debts WHERE creditor = ?s AND debtor = ?s",$_SESSION['selfid'],$_GET['id']);
                }
                else
                {
   		        $credits = $db->getAll("SELECT * FROM debts WHERE creditor = ?s",$_SESSION['selfid']);
		}
                foreach ($credits as $key => $rec)
                {
                        $payment = $db->getAll("SELECT description, date FROM payments WHERE id = ?s", $rec['source'])[0];
                        $credits[$key]['description']=$payment['description'];
                        $credits[$key]['date']=$payment['date'];
                        $user = $db->getAll("SELECT realname FROM users WHERE id = ?s", $rec['creditor'])[0];
			$credits[$key]['creditor']=$user['realname'];
			$user = $db->getAll("SELECT realname FROM users WHERE id = ?s", $rec['debtor'])[0];
			$credits[$key]['debtor']=$user['realname'];
                }

                $data['title']="Мои кредиты";
                $data['comment']="Мне должны:";
                $data['payload']=$credits;

                return $data;

	}

        public function getPayment($id)
        {
                global $db;
                check_auth();

		$data['payment'] = $db->getAll("SELECT * FROM payments WHERE id = ?s",$id)[0];
		 $host = 'https://'.$_SERVER['HTTP_HOST'].'/';
		$data['payment']['attachment'] = str_replace("/var/www/html/",$host,$data['payment']['attachment']);
		$pids=explode(',',$data['payment']['participants']);
		$pdata=Array();
		foreach($pids as $pid)
		{
			$p=$db->getAll("SELECT realname FROM users WHERE id = ?s",$pid)[0];
			$d=$db->getAll("SELECT * FROM debts WHERE source = ?s AND debtor = ?s",$data['payment']['id'],$pid)[0];
			//var_dump($d);
			$pdata[]=Array( 'realname' => $p['realname'], 'ispaid' => $d['ispaid'], 'summ' => $d['summ']);
		}
		$data['payment']['participants']=$pdata;

		$data['owner'] = $db->getAll("SELECT realname FROM users WHERE id = ?s",$data['payment']['owner'])[0];
		$data['debts'] = $db->getAll("SELECT * FROM debts WHERE source = ?s",$data['payment']['id']);
		
		return $data;
	}

	public function getSummary($notme)
	{
		if (isset($notme)){Route::Error("Not implemented function"); return; die(header('Location:/error/notImplemented'));};

		global $db;
                //session_start();
                check_auth();
                $data['avaliableParticipants']=Array();
                $users = $db->getAll("SELECT id,realname FROM users");
		$me=$users[$_SESSION['selfid']];
		$id=$_SESSION['selfid'];
                function self_filter($var){return($var['id']!=$_SESSION['selfid']);} ///$_SESSION['selfid']
                $users = array_filter($users,"self_filter");

		//var_dump($users);

		foreach ($users as $key=>$user)
		{
			$users[$key]['deptToMe']=0;
			$deptsToMe = $db->getAll("SELECT summ FROM debts WHERE creditor=?s AND debtor=?s AND ispaid = 0",$id,$user['id']);
			//if(isset($deptsToMe['summ']))
			//$users[$key]['deptToMe']+=$deptsToMe['summ'];
			foreach ($deptsToMe as $dtm){
				$users[$key]['deptToMe']+=$dtm['summ'];
			}
			$users[$key]['creditToMe']=0;
			$creditsToMe = $db->getAll("SELECT summ FROM debts WHERE creditor=?s AND debtor=?s AND ispaid = 0",$user['id'],$id);
			//if(isset($creditToMe['summ']))
			//$users[$key]['creditToMe']+=$creditToMe['summ'];
                        foreach ($creditsToMe as $ctm){
                                $users[$key]['creditToMe']+=$ctm['summ'];
                        }

		}
		//var_dump($users);

		$data['avaliableParticipants']=$users;

		$totalDept=0;
		$depts = $db->getAll("SELECT summ FROM debts WHERE creditor=?s AND ispaid=0",$id);
		foreach ($depts as $dept){$totalDept+=$dept['summ'];}
		$data['totalDept']=$totalDept+0;

                $totalCredit=0;
                $credits = $db->getAll("SELECT summ FROM debts WHERE debtor=?s AND ispaid=0",$id);
                foreach ($credits as $credit){$totalCredit+=$credit['summ'];}
		$data['totalCredit']=$totalCredit+0; //var_dump($credits);

		$totalPay = $db->getAll("Select amount FROM payments WHERE owner=?s", $_SESSION['selfid']);
		$totalPayAmmount=0;
		foreach($totalPay as $payment)
		{
			$totalPayAmmount+=$payment['amount'];
		}
		$data['totalPay']=$totalPayAmmount;

	return $data;
	}

}


?>

