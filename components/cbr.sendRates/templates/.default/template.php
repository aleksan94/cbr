<?
if($arResult['isSend']) {
	ShowMessage(array("TYPE" => "OK", "MESSAGE" => "Сообщение успешно отправлено"));
}
?>
<form method="GET">
	<input type="hidden" name="do" value="send_mail">
	<input type="email" name="email" placeholder="Введите Email" required="">
	<input type="submit" name="submit">	
</form>