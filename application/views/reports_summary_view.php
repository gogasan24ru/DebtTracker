<h1>Общий отчет</h1>
<p>

<h3><?php if(isset($data['notme']))echo 'Детализация '.$data['notme'];?></h3>


Суммарный кредит мне: <?php echo $data['totalCredit']; ?> <a href="/reports/creditToMe">(детализация)</a>
<br>
Суммарная задолженность мне: <?php echo $data['totalDept']; ?> <a href="/reports/debtsToMe">(детализация)</a>
<br>
Суммарные закупки: <?php echo $data['totalPay']; ?> <a href="/reports/mypays">(детализация)</a>
<hr>



<table>
<thead>
<tr><td>Видимое имя</td><td>Мой кредит</td><td>Моя задолженность</td></tr>
</thead>
<tbody>
<?php
$users= $data['avaliableParticipants'];//$data['avaliableParticipants'];
//var_dump($users);	

foreach ($users as $user)
{
echo '<tr>
	<td><a href="/reports/participant?id='.$user['id'].'">'.$user['realname'].'</a></td>
	<td><a href="/reports/debtsToMe?id='.$user['id'].'">'.$user['deptToMe'].'</a></td>
	<td><a href="/reports/creditToMe?id='.$user['id'].'">'.$user['creditToMe'].'</a></td>
</tr>';

}

?>
</tbody>
</table>
</p>
