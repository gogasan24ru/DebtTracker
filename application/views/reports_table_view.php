<h1><?php echo $data['title']; ?></h1>
<h3><?php echo $data['comment']; ?></h3>
<p>

<script type="text/javascript">
var hidden=true;
function toggleHidePaid(){
	hidden=!hidden;
var elems=document.getElementsByClassName("paid");
for (var i=0;i<elems.length;i++){//inline-block
	if(hidden){elems[i].style.display="table-row";}else{elems[i].style.display="none";}
	//alert(i);
}
}
</script>
<a href="#" onclick="toggleHidePaid()">Show/Hide paid</a>

<?php //var_dump($data); 


if (count($data['payload']))
{
	echo '
	<table><thead>
	<tr><td>Покупка</td><td>Должник</td><td>Кредитор</td><td>Размер</td><td>Аннулировано</td><td>Дата ннулирования</td><td>Дата создания</td><td>comment</td>
</tr>
	</thead><tbody>
	';
	$pl=$data['payload'];
	foreach ($pl as $p){
		echo '
		<tr class="'.(($p['ispaid']==="1")?"paid":"").'">
		<td><a href="/reports/payment?id='.$p['source'].'">'.$p['description'].'</a></td>
		<td>'.$p['debtor'].'</td>
		<td>'.$p['creditor'].'</td>
                <td>'.$p['summ'].'</td>
                <td>'.$p['ispaid'].'</td>
                <td>'.$p['payday'].'</td>
                <td>'.$p['date'].'</td>
		<td>'.$p['comment'].'</td>
                </tr>
		';
	}
	echo '</tbody></table>';
}
else {echo "no data"; }



?>
</p>
