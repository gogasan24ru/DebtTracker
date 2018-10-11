<h1>Работа с долгами</h1>
<p>

Доступный список:<br>
<?php
if(count($data['debts'])!=0)
{
echo '
<table>
<thead>
<tr><td>dID</td><td>Должник</td><td>Сумма</td><td>Покупка</td><td>Action</td></tr>
</thead><tbody>
';
//build table

foreach($data['debts'] as $debt)
{
//var_dump($debt);
echo '
<tr>
<td>'.$debt['id'].'</td>
<td><a href="/reports/summary?id='.$debt['debtor'].'">'.$data['users'][$debt['debtor']]['realname'].'</a></td>
<td>'.$debt['summ'].'</td>
<td><a href="/reports/payment?id='.$data['sources'][$debt['source']]['id'].'">'.$data['sources'][$debt['source']]['description'].'</a></td>
<td>';
 if($debt['ispaid']==="0") { ?>
<form method="GET" action="/manage/reclaim">
<input type="hidden" name="id" value="<?php echo ''.$debt['id'].''; ?>">
<input type="text" name="comment">
<button type="submit">Remove</button>
</form>
<!--
<a href="/manage/reclaim?id=<?php echo ''.$debt['id'].''; ?>">Remove</a>
--!>
<?php } else echo "Removed ".$debt['payday'].":".$debt['comment'];
echo '
</td>
</tr>
';

}
//var_dump($data);



echo '</tbody></table>';
}
else
echo 'Ничего нет.';

?>
</p>
