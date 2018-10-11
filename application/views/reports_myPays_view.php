<h1>Мои  покупки</h1>
<p>

<table class=blueTable">
<thead>
<tr><td>Наименовение</td><td>Стоимость</td><td>Дата</td><td>Участников, всего</td><td>Action</td></tr>
</thead>
<tbody>
<?php

function countCommas($src)
{
return substr_count($src, ',');
}

//$users= $data['avaliableParticipants'];//$data['avaliableParticipants'];
//var_dump($data);	
//return;
foreach ($data as $datum)
{
echo '<tr>
	<td><a href="/reports/payment?id='.$datum['id'].'">'.$datum['description'].'</a></td>

        <td>'.$datum['amount'].'</td>

        <td>'.$datum['date'].'</td>
	<td>'.(countCommas($datum['participants'])+1).'</td>
	<td><a href="/manage/removePay?id='.$datum['id'].'">Remove</a></td>
</tr>';

}

?>
</tbody>
</table>
</p>
