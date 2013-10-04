{config_load file="4s.config"}

<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ja" lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="Content-Script-Type" content="text/javascript" />
<meta http-equiv="Content-Style-Type" content="text/css" />
<meta http-equiv="Content-Language" content="ja" />
<title>管理者ログアウト | {#site_name#}</title> <!-- タイトルを入力（SEO対策で重要） -->
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
<li><span>{#navi04_jname#}</span</li><!-- パンくずを入力（アクティヴはspan要素で） -->
</ul>
<!-- ↑パンくずリンクここまで↑ -->

<div id="contents">
<div id="snippet">
<!-- コンテンツここから -->

<h2>管理者ログアウト</h2>
<p>ログアウトが完了しました。</p>
<br /><br /><br />

<!-- コンテンツここまで -->
</div>
</div>

<!-- ↓パンくずリンクここから↓ -->
<div id="breadCrumb_bottom_wrapper">
<ul id="breadCrumb_bottom">
<li><a href={#http_host#}{#navi00_url#}>{#navi00_jname#}</a></li> <!-- パンくずリンク先を入力（リンクはa要素で） -->
<li><span>{#navi04_jname#}</span</li><!-- パンくずを入力（アクティヴはspan要素で） -->
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
