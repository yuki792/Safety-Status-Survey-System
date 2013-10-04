{config_load file="4s.config"}

<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ja" lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="Content-Script-Type" content="text/javascript" />
<meta http-equiv="Content-Style-Type" content="text/css" />
<meta http-equiv="Content-Language" content="ja" />
<title>送信内容確認 | {#site_name#}</title> <!-- タイトルを入力（SEO対策で重要） -->
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
<link rel="stylesheet" href="./commons/import.css" type="text/css" />
<link rel="stylesheet" href="./commons/header_02.css" type="text/css" />
<link rel="stylesheet" href="./commons/style.css" type="text/css" />
<script type="text/javascript" src="./commons/pagetop.js"></script>
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
<li><a href={#http_host#}{#navi03_url#}>{#navi03_jname#} <span>{#navi03_ename#}</span></a></li> <!-- リンク先とメニュータイトル（日本語と英語）を入力 -->
<li class="active"><a href={#http_host#}{#navi04_url#}>{#navi04_jname#} <span>{#navi04_ename#}</span></a></li> <!-- リンク先とメニュータイトル（日本語と英語）を入力 -->
<li><a href={#http_host#}{#navi05_url#}>{#navi05_jname#} <span>{#navi05_ename#}</span></a></li> <!-- リンク先とメニュータイトル（日本語と英語）を入力 -->
</ul>
</div>
<div id="mainImage2">
<img src="./images/title04.jpg" alt="イメージ画像" width="880" height="120" /> <!-- イメージ画像を指定 -->
</div>
</div>

<div id="contents_wrapper">
<div id="contents_wrapper_top">トップ</div> <!-- （会社名）トップと入力（表示はされません） -->
<div id="contents_wrapper_body">

<!-- ↓パンくずリンクここから↓ -->
<ul id="breadCrumb_top">
<li><a href={#http_host#}{#navi00_url#}>{#navi00_jname#}</a></li> <!-- パンくずリンク先を入力（リンクはa要素で） -->
<li><a href={#http_host#}{#navi04_url#}>{#navi04_jname#}</a></li> <!-- パンくずを入力（アクティヴはspan要素で） -->
<li><a href="index.php?func=safety_survey">安否確認</a></li>
<li><span>安否確認メール新規送信</span></li>
</ul>
<!-- ↑パンくずリンクここまで↑ -->

<div id="contents">
<div id="snippet">
<!-- コンテンツここから -->

<h2>登録内容確認</h2>

<p>
以下の内容で登録してよろしいですか？
</p>

<div class="form"> 
<p>
<table border="0" cellspacing="1" cellpadding="0" class="form"> 

<tr>
<th>タイトル</th>
<td>{$title|default:""}</td>
</tr>

<tr>
<th>送信対象メンバー</th>
<td>
{$faculty_name|default:""}<br />
{$course_name|default:""}<br />
{$member_name|default:""}<br />
</td>
</tr>

<tr>
<th>メール送信先</th>
<td>{$destination|default:""}</td>
</tr>

<tr>
<th>メール本文中に表示するコメント</th>
<td>{$comment|default:""}</td>
</tr>

<tr>
<td colspan="2">
<div align="center">
<form action='index.php?func=send_survey' method="post">
<input type="hidden" name="title" value="{$title|default:""}">
<input type="hidden" name="target_faculty" value="{$target_faculty|default:""}">
<input type="hidden" name="target_course" value="{$target_course|default:""}">
<input type="hidden" name="target_member" value="{$target_member|default:""}">
<input type="hidden" name="destination" value="{$destination|default:""}">
<input type="hidden" name="comment" value="{$comment|default:""}">
<input type="submit" name="send_button" value="登録">
<input type="submit" name="back_button" value="戻る">
</form>

</div>
</td>
</tr>

</table>

</p>
</div>


<!-- コンテンツここまで -->
</div>
</div>

<div id="sidebar">
<div class="section">
<h3>サイドメニュー</h3> <!-- メニュータイトルを入力 -->
<ul class="sidemenu">
<li class="last"><a href="index.php?func=safety_survey">安否確認ページに戻る</a></li> <!-- リンク先とサイドメニュータイトルを入力（サイドメニューのラストはclass指定を忘れずに -->
</ul>
</div>

</div>

<!-- ↓パンくずリンクここから↓ -->
<div id="breadCrumb_bottom_wrapper">
<ul id="breadCrumb_bottom">
<li><a href={#http_host#}{#navi00_url#}>{#navi00_jname#}</a></li> <!-- パンくずリンク先を入力（リンクはa要素で） -->
<li><a href={#http_host#}{#navi04_url#}>{#navi04_jname#}</a></li> <!-- パンくずを入力（アクティヴはspan要素で） -->
<li><a href="index.php?func=safety_survey">安否確認</a></li>
<li><span>安否確認メール新規送信</span></li>
</ul>
</div>
<!-- ↑パンくずリンクここまで↑ -->

</div>
<div id="contents_wrapper_bottom">
<p><a href="#pagetop" id="gopagetop" name="gopagetop" onclick="jumpToPageTop();return false;" onkeypress="jumpToPageTop();return false;"><img src="./images/pagetop.gif" alt="ページの先頭へ戻る" width="104" height="12" /></a></p>
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
