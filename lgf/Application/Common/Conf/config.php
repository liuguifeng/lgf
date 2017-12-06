<?php
return array(
	//'配置项'=>'配置值'
    'DB_TYPE'=>'mysql',
    'DB_HOST'=>'localhost',
    'DB_NAME'=>'xgyy',
    'DB_USER'=>'root',
    'DB_PWD'=>'root',
    'DB_PREFIX'=>'php34_',
    'DB_CHARSET'=>'utf8',
    'DB_PORT'=>'3306',
    /*图片先关配置*/
    'IMG_maxSize' => '4',//m
    'MOV_maxSize' => '500',//m
    'IMG_exts' => array('jpg', 'pjpeg', 'bmp', 'gif', 'png', 'jpeg'),
    'MOV_exts' => array('mp4'),
    'IMG_rootPath' => './Public/Uploads/',
    'MOV_rootPath' => './Public/Movies/',
    'IMG_URL'=>'/Public/Uploads/',
    'MOV_URL'=>'/Public/Movies/',
    /* 修改I函数底层过滤时使用的函数 */
    'DEFAULT_FILTER' =>  'trim,removeXSS', // 默认参数过滤方法 用于I函数...
    'MD5_KEY'  => 'LGFHUANG',
    'MAIL_ADDRESS'=>'lgfphp@126.com',
    'MAIL_FROM'=>'lgfphp',
    'MAIL_SMTP'=>'smtp.126.com',
    'MAIL_LOGINNAME'=>'lgfphp',
    'MAIL_PASSWORD'=>'admin12345',
    'AUTOLOAD_NAMESPACE'=>array(
        'Lib'   =>  APP_PATH."Lib",
    ),
);