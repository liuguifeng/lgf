<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>管理中心</title>
<meta name="robots" content="noindex, nofollow">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="/Public/Admin/Styles/general.css" rel="stylesheet" type="text/css" />
<link href="/Public/Admin/Styles/main.css" rel="stylesheet" type="text/css" />
</head>
<body>
<h1>
    <span class="action-span1"><a href="#">演呗 管理中心</a> </span>
    <span id="search_id" class="action-span1"></span>
    <div style="clear:both"></div>
</h1>
<div class="list-div">
    <table cellspacing='1' cellpadding='3'>
        <tr>
            <th colspan="4" class="group-title">会员统计信息</th>
        </tr>
        <tr>
            <td width="20%">会员总数:</td>
            <td width="30%"><strong style="color:red"><?php echo ($data); ?></strong></td>
            <td width="20%">今日注册用户:</td>
            <td width="30%"><strong><?php echo ($today); ?></strong></td>
        </tr>
    </table>
</div>
<!-- end order statistics -->
<br />
<!-- start goods statistics -->
<div class="list-div">
</div>
<!-- end order statistics -->
<br />
<!-- start system information -->
<div class="list-div">
    <table cellspacing='1' cellpadding='3'>
        <tr>
            <th colspan="4" class="group-title">系统信息</th>
        </tr>
        <tr>
            <td width="20%">服务器操作系统:</td>
            <td width="30%">WINNT (127.0.0.1)</td>
            <td width="20%">Web 服务器:</td>
            <td width="30%">Apache/2.2.22 (Win32) PHP/5.3.8</td>
        </tr>
        <tr>
            <td>PHP 版本:</td>
            <td>5.3.8</td>
            <td>MySQL 版本:</td>
            <td>5.5.24</td>
        </tr>
        <tr>
            <td>安全模式:</td>
            <td>否</td>
            <td>安全模式GID:</td>
            <td>否</td>
        </tr>
        <tr>
            <td>Socket 支持:</td>
            <td>是</td>
            <td>时区设置:</td>
            <td>PRC</td>
        </tr>
        <tr>
            <td>GD 版本:</td>
            <td>GD2 ( JPEG GIF PNG)</td>
            <td>Zlib 支持:</td>
            <td>是</td>
        </tr>
        <tr>
            <td>IP 库版本:</td>
            <td>20071024</td>
            <td>视频上传的最大大小:</td>
            <td>200M</td>
        </tr>
    </table>
</div>