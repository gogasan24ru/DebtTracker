<!DOCTYPE html>
<html lang="ru">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Money</title>
<link rel="shortcut icon" href="/icon.png" />
<link rel="stylesheet" type="text/css" href="/application/css/style.css" />
<link rel="stylesheet" type="text/css" href="/application/css/styleMobile.css" media= "only screen and (max-device width:700px)"/>
<script type="text/javascript">
var display=false;
function toggleMenu()
{
display=!display;
var elems=document.getElementsByClassName("mbtn");
for (var i=0;i<elems.length;i++){//inline-block
	if(display){elems[i].style.display="inline-block";}else{elems[i].style.display="none";}
	//alert(i);
}
}

</script>
<!-- <script src="/application/js/jquery-1.6.2.js" type="text/javascript"></script> --!>
</head>
<body>
<menu>
<div class="main">

<a class="menu_button iconed menu-icon toggle_menu" onclick="toggleMenu()" href="#"> </a>
<?php
if(check_auth(false))
{
echo '
<a class="menu_button iconed bill-icon mbtn" href="/manage/addnew">Новые расходы</a>
<a class="menu_button iconed debt-icon mbtn" href="/manage/reclaim">Вернули долг</a>
<a class="menu_button iconed info-icon mbtn" href="/reports/summary">Статус</a>
<a class="menu_button iconed settings-icon mbtn" href="/manage/account">Управление аккаунтом</a>
<a class="menu_button iconed login-icon mbtn" href="/auth/logout">Выйти</a>
<a class="menu_button iconed journal-icon mbtn" href="/reports/journal">Журнал событий</a>
Вы вошли как '.whoami().'.

';
}
else
{
echo '<a class="menu_button iconed login-icon mbtn" href="/auth/login">Вход</a>';
}
?>
</menu>
	<?php include 'application/views/'.$content_view; ?>
</div>
</body>
</html>
