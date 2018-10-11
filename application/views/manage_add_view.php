<h1>Добавление платежа</h1>
<p>
<form enctype="multipart/form-data" method="POST" action="/manage/add">
<table>
<tr><td>Наименование: </td><td><input name="description" type="text" placeholder="наименование" required></td></tr>
<tr><td>Стоимость: </td><td><input name="price"  placeholder="" required></td></tr>

<tr><td>Участники: 
</td><td>

<script type="text/javascript">
var checked=false;
var k=0;
function toggle() {
	checked=!checked;
	  cb = document.getElementsByClassName("participantsCB");
    	for (var i = 0; i < cb.length; i++) 
    	{
    		cb[i].checked = checked;


    	}
 }
</script>

<a href="javascript:toggle();"  id="selAll">Select all</a><br>


<?php
$avaliableParticipants=$data['avaliableParticipants'];
foreach ($avaliableParticipants as $p)
{
	echo '<input class="participantsCB" name="participants['.$p['id'].']" id="p'.$p['id'].'" type="checkbox" placeholder="">
<label for="p'.$p['id'].'">'.$p['realname'].'</label><br>

';
}
?>
</td></tr>

<tr><td>Комментарий: </td><td><textarea name="commentary"></textarea></td></tr>
<tr><td>Приложение: </td><td><input name="attachment" type="file" placeholder=""></td></tr>
<tr><td><button type="submit">Send</button> </td><td></td></tr>
</table>

</form>


</p>
