<h1>Детали покупки</h1>
<p>

<table>
<thead><tr>
<td>Свойство</td><td>Значение</td>
</tr></thead>

<tbody>
<tr><td>Наименовение</td><td><?php echo $data['payment']['description']?></td></tr>
<tr><td>Владелец</td><td><?php echo $data['owner']['realname']?></td></tr>
<tr><td>Дата внесения</td><td><?php echo $data['payment']['date']?></td></tr>
<tr><td>Сумма</td><td><?php echo $data['payment']['amount']?></td></tr>
<tr><td>Участники</td><td>


<?php //echo $data['payment']['participants']
if (isset($data['payment']['participants']))
{
echo '
<table>
<thead><tr>
<td>Имя</td><td>Задолженность</td>
</tr></thead>
<tbody>
';
	foreach ($data['payment']['participants'] as $p)
	{
		echo '
		<tr><td>'.$p['realname'].'</td><td>'.(($p['ispaid']=="0") ? $p['summ'] : "Отсутствует").'</td></tr>
		';
		
	}
	echo '</tbody></table>';
}
else
{
	echo "Not listed";
}

?>



</td></tr>
<tr><td>Вложение</td><td><a href="<?php echo $data['payment']['attachment']?>" target="blank"><?php echo $data['payment']['attachment']?></a></td></tr>

</tbody>

</table>


<?php //var_dump($data);





?>

</p>
