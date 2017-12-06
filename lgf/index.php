<?php
header("content-type:text/html;charset=utf-8");
//shop入口文件
//开启调试模式
define('APP_DEBUG', true);
//给系统资源文件路径定义常量
define('APP_PATH','./Application/');
define('RUNTIME_PATH','./Application/Runtime/Cache/');
//引入框架的接口文件thinkphp.php
include ("./ThinkPHP/ThinkPHP.php"); 