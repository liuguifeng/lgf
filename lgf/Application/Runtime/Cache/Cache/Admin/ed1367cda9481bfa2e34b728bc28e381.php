<?php if (!defined('THINK_PATH')) exit();?><html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>演呗管理中心</title>
<meta name="robots" content="noindex, nofollow">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="/Public/Admin/Styles/general.css" rel="stylesheet" type="text/css" />
<link href="/Public/Admin/Styles/main.css" rel="stylesheet" type="text/css" />
<link href="/Public/datepicker/jquery-ui-1.9.2.custom.min.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="/Public/plupload/js/jquery.plupload.queue/css/jquery.plupload.queue.css" type="text/css" media="screen" />
<script type="text/javascript" charset="utf-8" src="/Public/datepicker/jquery-1.8.3.min.js"></script>
<script type="text/javascript" charset="utf-8" src="/Public/datepicker/jquery-ui-1.9.2.custom.min.js"></script>
<script type="text/javascript" charset="utf-8" src="/Public/datepicker/datepicker_zh-cn.js"></script>
<script type="text/javascript" charset="utf-8" src="/Public/ueditor/ueditor.config.js"></script>
<script type="text/javascript" charset="utf-8" src="/Public/ueditor/ueditor.all.min.js"> </script>
<script type="text/javascript" charset="utf-8" src="/Public/ueditor/lang/zh-cn/zh-cn.js"></script>
<script type="text/javascript" charset="utf-8" src="/Public/plupload/js/plupload.full.min.js"></script>
<script type="text/javascript" charset="utf-8" src="/Public/plupload/js/i18n/zh_CN.js"></script>
<script type="text/javascript" charset="utf-8" src="/Public/plupload/js/jquery.plupload.queue/jquery.plupload.queue.js"></script>
</head>
<body>
<h1>
    <span class="action-span"><a href="<?php echo $_page_btn_link; ?>"><?php echo $_page_btn_name;?></a></span>
    <span class="action-span1"><a href="#">管理中心</a></span>
    <span id="search_id" class="action-span1"> - <?php echo $_page_title;?> </span>
    <div style="clear:both"></div>
</h1>

<div class="main-div">
    <form name="main_form" method="POST" action="/index.php/Admin/Advert/add.html" enctype="multipart/form-data">
        <table cellspacing="1" cellpadding="3" width="100%">
            <tr>
                <td class="label">广告图：</td>
                <td>
                    <input type="file" name="advert" /> 
                </td>
            </tr>
            <tr>
                <td class="label">广告链接：</td>
                <td>
                    <input  type="text" name="url" value="" />
                </td>
            </tr>
            <tr>
                <td class="label">是否启用：</td>
                <td>
                    <input type="radio" name="status" value="1" checked="checked" />启用
                    <input type="radio" name="status" value="0"  />禁用 
                </td>
            </tr>
            <tr>
                <td colspan="99" align="center">
                    <input type="submit" class="button" value=" 确定 " />
                    <input type="reset" class="button" value=" 重置 " />
                </td>
            </tr>
        </table>
    </form>
</div>
<script>
</script>
<div id="footer">演呗</div>
</body>
</html>
<script type="text/javascript" charset="utf-8" src="/Public/Admin/js/train.js"></script>