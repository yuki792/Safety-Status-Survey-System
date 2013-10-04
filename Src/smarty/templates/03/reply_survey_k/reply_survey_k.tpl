{config_load file="4s.config"}

<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ja" lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="Content-Script-Type" content="text/javascript" />
<meta http-equiv="Content-Language" content="ja" />
<title>安否回答 | {#site_name#}</title>
</head>

<body>

<h2>安否回答</h2>

<p>
以下の項目を入力して[確認画面へ]ボタンを押してください。<br />
*マークの項目は必須入力です。
</p>

{$error_message|default:""}

<p>
<form action="index.php?func=reply_survey_k" method="post">

現在の状態 (*)<br />
<label><input type="radio" id="safety_status" name="safety_status" value="1" />無事</label><br />
<label><input type="radio" id="safety_status" name="safety_status" value="2" />軽傷</label><br />
<label><input type="radio" id="safety_status" name="safety_status" value="3" />重傷</label><br />

<br /><br /><br />


現在の居場所 (*)<br />
<label><input type="radio" id="location" name="location" value="1" />自宅</label><br />
<label><input type="radio" id="location" name="location" value="2" />友人・親類宅</label><br />
<label><input type="radio" id="location" name="location" value="3" />避難所</label><br />
<label><input type="radio" id="location" name="location" value="4" />その他</label><br />

<br /><br /><br />


通学可否 (*)<br />

<label><input type="radio" id="attend_school" name="attend_school" value="1" />可能</label><br />
<label><input type="radio" id="attend_school" name="attend_school" value="2" />不可能</label><br />

<br /><br /><br />


<label for="comment">コメント</label><br />
<textarea id="comment" name="comment" rows="3" cols="20">{$comment|default:""}</textarea><br />

<br /><br />

<input type="hidden" name="survey_id" value="{$survey_id|default:""}">
<input type="hidden" name="key" value="{$key|default:""}">
<input type="submit" name="confirm_button" value="確認画面へ">

</form>
</p>

</body>
</html>
