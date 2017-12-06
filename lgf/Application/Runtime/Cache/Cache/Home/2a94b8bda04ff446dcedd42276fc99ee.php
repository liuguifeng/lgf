<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta content="telephone=no" name="format-detection">
<meta name="baidu-site-verification" content="QpfdfPmoBr" />
<title>演呗</title>
<meta name="keywords" content="演呗,少儿星光大道"/>
<meta name="description" content="演呗,少儿星光大道"/>
<link rel="stylesheet" type="text/css" href="/Public/Home/style/index_1.css">

</head>
<body>
<div class="content pr">
    <img src="/Public/Home/images/index_3.png" class="c-1"/>
    <img src="" class="c-5"/>
    <a href=""><img src="/Public/Home/images/i_02.png" class="c-2 ios"></a>
    <a href="http://zhushou.360.cn/detail/index/soft_id/3898394?recrefer=SE_D_%E6%BC%94%E5%91%97"><img src="/Public/Home/images/button_android.png" class="c-2"></a>
    <a href="http://www.anzhi.com/pkg/9b5f_com.ancheng.anchengproject.html"><img src="/Public/Home/images/button_android.png" class="c-2"></a>
    <p class="footer-nav"></p>
    <img src="/Public/Home/images/index_1.png" id='i-b' class="i-b pa"/>
    <img src="/Public/Home/images/index_2.png" id='i-s' class="i-s pa"/>
</div>
</body>
</html>
<script type="text/javascript">
(function(){
var tab_tit  = document.getElementById('think_page_trace_tab_tit').getElementsByTagName('span');
var tab_cont = document.getElementById('think_page_trace_tab_cont').getElementsByTagName('div');
var open     = document.getElementById('think_page_trace_open');
var close    = document.getElementById('think_page_trace_close').childNodes[0];
var trace    = document.getElementById('think_page_trace_tab');
var cookie   = document.cookie.match(/thinkphp_show_page_trace=(\d\|\d)/);
var history  = (cookie && typeof cookie[1] != 'undefined' && cookie[1].split('|')) || [0,0];
open.onclick = function(){
	trace.style.display = 'block';
	this.style.display = 'none';
	close.parentNode.style.display = 'block';
	history[0] = 1;
	document.cookie = 'thinkphp_show_page_trace='+history.join('|')
}
close.onclick = function(){
	trace.style.display = 'none';
this.parentNode.style.display = 'none';
	open.style.display = 'block';
	history[0] = 0;
	document.cookie = 'thinkphp_show_page_trace='+history.join('|')
}
for(var i = 0; i < tab_tit.length; i++){
	tab_tit[i].onclick = (function(i){
		return function(){
			for(var j = 0; j < tab_cont.length; j++){
				tab_cont[j].style.display = 'none';
				tab_tit[j].style.color = '#999';
			}
			tab_cont[i].style.display = 'block';
			tab_tit[i].style.color = '#000';
			history[1] = i;
			document.cookie = 'thinkphp_show_page_trace='+history.join('|')
		}
	})(i)
}
parseInt(history[0]) && open.click();
(tab_tit[history[1]] || tab_tit[0]).click();
})();
</script>