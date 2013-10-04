{config_load file="4s.config"}

<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ja" lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="Content-Script-Type" content="text/javascript" />
<meta http-equiv="Content-Style-Type" content="text/css" />
<meta http-equiv="Content-Language" content="ja" />
<title>登録情報変更 | {#site_name#}</title> <!-- タイトルを入力（SEO対策で重要） -->
<meta name="Keywords" content={#meta_keywords#} /> <!-- キーワードを入力（コンマ区切り） -->
<meta name="Description" content={#meta_description#} /> <!-- 説明文を入力 -->
<meta name="revisit_after" content="7 days" />
<meta name="robots" content="ALL" />
<meta http-equiv="pragma" content="no-cache" />
<meta http-equiv="imagetoolbar" content="no" />
<meta name="Author" content=meta_author /> 
<meta name="Copyright" content={#meta_copyright#} /> <!-- 会社名を入力 -->
<link rev="made" href={#link_mail#} /> <!-- メールアドレスを入力 -->
<link href={#http_host#}{#toppage_url#} title={#site_name#} rel="start"  />  <!-- スタートページを入力 -->
<link rel="stylesheet" href="commons/import.css" type="text/css" />
<link rel="stylesheet" href="commons/header_02.css" type="text/css" />
<link rel="stylesheet" href="commons/style.css" type="text/css" />
<script type="text/javascript" src="commons/pagetop.js"></script>
</head>

<body>

<div id="wrapper">
<div id="container">

<a name="pagetop" id="pagetop"></a> <!-- 会社名などを入力（表示はされません） -->

<div id="header">
<h1><a href={#http_host#}{#toppage_url#}>{#site_name#}</a></h1> <!-- タイトルを入力（表示はされません） -->
<ul id="navigator_sub">

<li><a href={#http_host#}{#header2_url#}>{#header2_jname#}</a></li> <!-- リンク先とサブメニュータイトルを入力 -->
</ul>
<div id="navigator">
<ul>
<li><a href={#http_host#}{#navi00_url#}>{#navi00_jname#} <span>{#navi00_ename#}</span></a></li> <!-- リンク先とメニュータイトル（日本語と英語）を入力 -->
<li><a href={#http_host#}{#navi01_url#}>{#navi01_jname#} <span>{#navi01_ename#}</span></a></li> <!-- リンク先とメニュータイトル（日本語と英語）を入力 -->
<li><a href={#http_host#}{#navi02_url#}>{#navi02_jname#} <span>{#navi02_ename#}</span></a></li> <!-- リンク先とメニュータイトル（日本語と英語）を入力 -->
<li class="active"><a href={#http_host#}{#navi03_url#}>{#navi03_jname#} <span>{#navi03_ename#}</span></a></li> <!-- リンク先とメニュータイトル（日本語と英語）を入力 -->
<li><a href={#http_host#}{#navi04_url#}>{#navi04_jname#} <span>{#navi04_ename#}</span></a></li> <!-- リンク先とメニュータイトル（日本語と英語）を入力 -->
<li><a href={#http_host#}{#navi05_url#}>{#navi05_jname#} <span>{#navi05_ename#}</span></a></li> <!-- リンク先とメニュータイトル（日本語と英語）を入力 -->
</ul>
</div>
<div id="mainImage2">
<img src="images/title03.jpg" alt="イメージ画像" width="880" height="120" /> <!-- イメージ画像を指定 -->
</div>
</div>

<div id="contents_wrapper">
<div id="contents_wrapper_top">トップ</div> <!-- （会社名）トップと入力（表示はされません） -->
<div id="contents_wrapper_body">

<!-- ↓パンくずリンクここから↓ -->
<ul id="breadCrumb_top">
<li><a href={#http_host#}{#navi00_url#}>{#navi00_jname#}</a></li> <!-- パンくずリンク先を入力（リンクはa要素で） -->
<li><a href={#http_host#}{#navi03_url#}>{#navi03_jname#}</a></li> <!-- パンくずを入力（アクティヴはspan要素で） -->
<li><span>登録情報変更</span></li>
</ul>
<!-- ↑パンくずリンクここまで↑ -->

<div id="contents">
<div id="snippet">
<!-- コンテンツここから -->

<h2>登録情報変更</h2>

<p>
変更したい項目を入力して[確認画面へ]ボタンを押してください。<br />
パスワードは変更する場合のみ入力してください。<br />
<span class="require">*</span>マークの項目は必須入力です。
</p>

<div class="error">
{$error_message|default:""}
</div>

<div class="form">
<p>
<form action="index.php?func=account_setting" method="post">
<table border="0" cellspacing="1" cellpadding="0" class="form"> 

<tr>
<th><label for="last_name">名前&nbsp<span class="require">*</span></label></th>
<td>姓：<input type="text" id="last_name" name="last_name" value="{$last_name|default:""}" size="10" />
&nbsp&nbsp名：<input type="text" id="first_name" name="first_name" value="{$first_name|default:""}" size="10" />
</td>
</tr>

<tr>
<th><label for="password">パスワード</label></th>
<td><input type="password" id="password" name="password" value="{$password|default:""}" size="30" /></td>
</tr>

<tr>
<th><label for="password_confirm">パスワード（確認）</label></th>
<td><input type="password" id="password_confirm" name="password_confirm" value="{$password_confirm|default:""}" size="30" /></td>
</tr>

<tr>
<th><label for="phone_mail">携帯メールアドレス&nbsp<span class="require">*</span></label></th>
<td><input type="text" id="phone_mail" name="phone_mail" value="{$phone_mail|default:""}" size="30" /></td>
</tr>

<tr>
<th><label for="phone_mail_confirm">携帯メールアドレス（確認）&nbsp<span class="require">*</span></label></th>
<td><input type="text" id="phone_mail_confirm" name="phone_mail_confirm" value="{$phone_mail_confirm|default:""}" size="30" /></td>
</tr>

<tr>
<th><label for="phone_number">携帯電話番号</label></th>
<td><input type="text" id="phone_number" name="phone_number" value="{$phone_number|default:""}" size="30" /></td>
</tr>

<tr>
<th><label for="other_mail1">その他メールアドレス１</label></th>
<td><input type="text" id="other_mail1" name="other_mail1" value="{$other_mail1|default:""}" size="30" /></td>
</tr>

<tr>
<th><label for="other_mail2">その他メールアドレス２</label></th>
<td><input type="text" id="other_mail2" name="other_mail2" value="{$other_mail2|default:""}" size="30" /></td>
</tr>

<tr>
<th><label for="other_mail3">その他メールアドレス３</label></th>
<td><input type="text" id="other_mail3" name="other_mail3" value="{$other_mail3|default:""}" size="30" /></td>
</tr>

<tr>
<th><label for="flink_mail1">fリンクメールアドレス１</label></th>
<td><input type="text" id="flink_mail1" name="flink_mail1" value="{$flink_mail1|default:""}" size="30" /></td>
</tr>

<tr>
<th><label for="flink_mail2">fリンクメールアドレス２</label></th>
<td><input type="text" id="flink_mail2" name="flink_mail2" value="{$flink_mail2|default:""}" size="30" /></td>
</tr>

<tr>
<th><label for="flink_mail3">fリンクメールアドレス３</label></th>
<td><input type="text" id="flink_mail3" name="flink_mail3" value="{$flink_mail3|default:""}" size="30" /></td>
</tr>

<tr>
<th><label for="flink_mail4">fリンクメールアドレス４</label></th>
<td><input type="text" id="flink_mail4" name="flink_mail4" value="{$flink_mail4|default:""}" size="30" /></td>
</tr>

<tr>
<th><label for="flink_mail5">fリンクメールアドレス５</label></th>
<td><input type="text" id="flink_mail5" name="flink_mail5" value="{$flink_mail5|default:""}" size="30" /></td>
</tr>

<tr>
<th><label for="twitter">twitter ID</label></th>
<td><input type="text" id="twitter_id" name="twitter_id" value="{$twitter_id|default:""}" size="30" /></td>
</tr>


<tr>
<td colspan="2">
<div align="center">
<input type="submit" name="confirm_button" value="確認画面へ">
</div>
</td>
</tr>

</table>
</form>
</p>
</div>
<!-- コンテンツここまで -->
</div>
</div>

<div id="sidebar">
<div class="section">
<h3>サイドメニュー</h3> <!-- メニュータイトルを入力 -->
<ul class="sidemenu">
<li class="last"><a href={#http_host#}{#navi03_url#}>利用者ページに戻る</a></li> <!-- リンク先とサイドメニュータイトルを入力（サイドメニューのラストはclass指定を忘れずに -->
</ul>
</div>

</div>

<!-- ↓パンくずリンクここから↓ -->
<div id="breadCrumb_bottom_wrapper">
<ul id="breadCrumb_bottom">
<li><a href={#http_host#}{#navi00_url#}>{#navi00_jname#}</a></li> <!-- パンくずリンク先を入力（リンクはa要素で） -->
<li><a href={#http_host#}{#navi03_url#}>{#navi03_jname#}</a></li> <!-- パンくずを入力（アクティヴはspan要素で） -->
<li><span>登録情報変更</span></li>
</ul>
</div>
<!-- ↑パンくずリンクここまで↑ -->

</div>
<div id="contents_wrapper_bottom">
<p><a href="#pagetop" id="gopagetop" name="gopagetop" onclick="jumpToPageTop();return false;" onkeypress="jumpToPageTop();return false;"><img src="images/pagetop.gif" alt="ページの先頭へ戻る" width="104" height="12" /></a></p>
</div>

</div>

<div id="copyright">
<span>{#footer_copyright#}<br /> <!-- （会社名など）を入力 -->
</span>
</div>

</div>
</div>
</body>
</html>
