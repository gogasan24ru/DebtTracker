<h1>Журнал событий</h1>
<p>
<table>
<thead>
<tr><td>ID</td><td>date</td><td>executor</td><td>message</td></tr>
</thead><tbody>
<?php
foreach($data as $rec)
{
	echo '<tr>
<td>'.$rec['id'].'</td>
<td>'.$rec['date'].'</td>
<td>'.$rec['executor'].'</td>
<td>'.$rec['message'].'</td>

</tr>
	';

}

?>


</tbody>
</table>
</p>
