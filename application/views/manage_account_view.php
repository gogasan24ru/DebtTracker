<h1>Настройки учетной записи</h1>
<p>
<h3>Видимое имя:</h3>
<form method="POST" action="/manage/accountName" >
<input type="text" value="<?php echo $data['realname']; ?>" name="realname">
<button type="submit">Изменить</button> (потребуется переавторизация)</form>




<br>
Логин:	<?php echo $data['username']; ?>

<br>

<h3>Изменить пароль:</h3>
<form method="POST" action="/manage/accountPassword" >
Текущий: <input type="password" value="" name="current"><br>
Новый: <input type="password" value="" name="new"><br>
Подтвердить: <input type="password" value="" name="new2"><br>

<button type="submit">Изменить</button></form>


<!--
<hr>
<a href="/reports/mypays">Мои покупки</a>
--!>
</p>
