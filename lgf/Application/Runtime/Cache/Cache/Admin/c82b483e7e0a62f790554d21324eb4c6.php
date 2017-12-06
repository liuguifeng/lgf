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

<!-- 搜索 -->
<div class="form-div search_form_div">
    <form method="GET" name="search_form">
		<p>
			视频名称：
	   		<input type="text" name="movie_name" size="30" value="<?php echo I('get.movie_name'); ?>" />
		</p>
		<p>
			主分类的id：
	   		<input type="text" name="cat_id" size="30" value="<?php echo I('get.cat_id'); ?>" />
		</p>
		<p>
			类型的id：
	   		<input type="text" name="brand_id" size="30" value="<?php echo I('get.type_id'); ?>" />
		</p>
		<p>
			是否最新：
			<input type="radio" value="-1" name="is_new" <?php if(I('get.is_new', -1) == -1) echo 'checked="checked"'; ?> /> 全部 
			<input type="radio" value="1" name="is_new" <?php if(I('get.is_new', -1) == '1') echo 'checked="checked"'; ?> /> 是 
			<input type="radio" value="0" name="is_new" <?php if(I('get.is_new', -1) == '0') echo 'checked="checked"'; ?> /> 否 
		</p>
		<p>
			是否精选：
			<input type="radio" value="-1" name="is_best" <?php if(I('get.is_best', -1) == -1) echo 'checked="checked"'; ?> /> 全部 
			<input type="radio" value="1" name="is_best" <?php if(I('get.is_best', -1) == '1') echo 'checked="checked"'; ?> /> 是 
			<input type="radio" value="0" name="is_best" <?php if(I('get.is_best', -1) == '0') echo 'checked="checked"'; ?> /> 否 
		</p>
		<p>
			添加时间：
	   		从 <input id="addtimefrom" type="text" name="addtimefrom" size="15" value="<?php echo I('get.addtimefrom'); ?>" /> 
		    到 <input id="addtimeto" type="text" name="addtimeto" size="15" value="<?php echo I('get.addtimeto'); ?>" />
		</p>
		<p><input type="submit" value=" 搜索 " class="button" /></p>
    </form>
</div>
<!-- 列表 -->
<div class="list-div" id="listDiv">
	<table cellpadding="3" cellspacing="1">
    	<tr>
            <th >视频名称</th>
            <th >logo</th>
            <th >送选机构</th>
            <th >演员</th>
            <th >主题</th>
            <th >最热</th>
            <th >最新</th>
            <th >是否推荐</th>
            <th >是否发布</th>
            <th >排序</th>
            <th width="150">操作</th>
        </tr>
		<?php foreach ($data as $k => $v): ?>            
			<tr class="tron">
				<td><?php echo $v['movie_name']; ?></td>
				<td><?php showImage($v['pic'],50); ?></td>
                                <td><?php echo $v['seo_keyword']; ?></td>
                                <td><?php echo $v['actor']; ?></td>
                                <td><?php echo $v['seo_description']; ?></td>
				<td><?php echo $v['is_hot']==1?'是':'否'; ?></td>
				<td><?php echo $v['is_new']==1?'是':'否'; ?></td>
				<td><?php echo $v['is_best']==1?'是':'否'; ?></td>
				<td><?php echo $v['status']==1?'发布':'未发布'; ?></td>
                                <td><?php echo $v['listorder'];?></td>
		        <td align="center" >
                            <a href="<?php echo U('status',array('id'=>$v['id'],'status'=>$v['status']?0:1,'p'=>I('get.p'))); ?>" title="发布状态"><?php echo (status($v["status"])); ?></a> |
                            <a href="<?php echo U('edit?id='.$v['id'].'&p='.I('get.p')); ?>" title="编辑">编辑</a> |
                            <a href="<?php echo U('recycle?id='.$v['id'].'&p='.I('get.p')); ?>" onclick="return confirm('确定要放入回收站吗？');" title="放入回收站">放入回收站</a> 
		        </td>
	        </tr>
        <?php endforeach; ?> 
		<?php if(preg_match('/\d/', $page)): ?>  
        <tr><td align="right" nowrap="true" colspan="11" height="30"><?php echo $page; ?></td></tr> 
        <?php endif; ?> 
	</table>
</div>
<script>
$('#addtimefrom').datepicker(); $('#addtimeto').datepicker(); 
</script>
<div id="footer">演呗</div>
</body>
</html>
<script type="text/javascript" charset="utf-8" src="/Public/Admin/js/train.js"></script>