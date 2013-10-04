{config_load file="4s.config"}

<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ja" lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="Content-Script-Type" content="text/javascript" />
<meta http-equiv="Content-Language" content="ja" />
<title>登録内容確認 | {#site_name#}</title>
</head>

<body>

<h2>登録内容確認</h2>

<p>
以下の内容で登録してよろしいですか？
</p>

<p>

現在の状態<br />
{$status_name|default:""}

<br /><br /><br />

現在の居場所<br />
{$location_name|default:""}

<br /><br /><br />

通学可否<br />
{$school_name|default:""}

<br /><br /><br />

コメント<br />
{$comment|default:""}

<br /><br />

<form action="index.php?func=reply_survey_k" method="post">
<input type="hidden" name="survey_id" value="{$survey_id|default:""}">
<input type="hidden" name="safety_status" value="{$safety_status|default:""}">
<input type="hidden" name="location" value="{$location|default:""}">
<input type="hidden" name="attend_school" value="{$attend_school|default:""}">
<input type="hidden" name="comment" value="{$comment|default:""}">
<input type="hidden" name="key" value="{$key|default:""}">
<input type="submit" name="send_button" value="登録">
<input type="submit" name="back_button" value="戻る">
</form>

</p>

</body>
</html>
