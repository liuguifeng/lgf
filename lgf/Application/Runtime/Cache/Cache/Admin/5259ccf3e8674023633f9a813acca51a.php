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

<style>
ul#pics_ul li{list-style-type:none;float:left;margin:5px;height:180px;}
</style>
<div class="tab-div">
    <div id="tabbar-div">
        <p>
            <span class="tab-front">基本信息</span>
            <span class="tab-back">视频描述</span>
        </p>
    </div>
    <div id="tabbody-div">
	    <form name="main_form" method="POST" action="/index.php/Admin/Movie/edit/id/79/p/1.html" enctype="multipart/form-data">
	    	<input type="hidden" name="id" value="<?php echo ($data["id"]); ?>" />
                <input type="hidden" name="old_mic" value="<?php echo ($data["mic"]); ?>" />
                <input type="hidden" name="old_pic" value="<?php echo ($data["pic"]); ?>" />
	        <!-- 基本信息 -->
	    	<table class="table_content" cellspacing="1" cellpadding="3" width="100%">
	            <tr>
	                <td class="label">视频名称：</td>
	                <td>
	                    <input size="60" type="text" name="movie_name" value="<?php echo ($data["movie_name"]); ?>" />
	                    <span class="required">*</span>
	                </td>
	            </tr>
                    <tr>
	                <td class="label">送选机构：</td>
	                <td>
	                    <input size="60" type="text" name="seo_keyword" value="<?php echo ($data["seo_keyword"]); ?>" />
                            <span class="required">*</span>
	                </td>
	            </tr>
                    <tr>
                        <td class="label">演员：</td>
                        <td>
                            <input  type="text" name="actor" value="<?php echo ($data["actor"]); ?>" />
                            <span class="required">*</span>
                        </td>
                    </tr>
                    <tr>
                        <td class="label">主题：</td>
	                <td>
	                    <select name="seo_description">
                                <option value="">选择类型</option>
                                <?php if(is_array($typedata)): $i = 0; $__LIST__ = $typedata;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><option value="<?php echo ($vo["type_id"]); ?>" <?php if($vo['type_id']==$data['type_id']):?> selected <?php endif; ?>><?php echo ($vo["type_name"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
                                
                            </select>
                            <span class="required">*</span>
	                </td>
                    </tr>
	            <tr>
	                <td class="label">视频分类：</td>
	                <td>
	                    <select name="category_id">
			    			<option value="">选择分类</option>
			    			<?php foreach ($catData as $k => $v): if($v['id'] == $data['category_id']) $select = 'selected="selected"'; else $select = ''; ?>
                                                <option <?php echo ($select); ?> value="<?php echo ($v["id"]); ?>"><?php echo str_repeat('-',$v['level'] * 8); echo ($v["cat_name"]); ?></option>
			    			<?php endforeach; ?>
			    		</select>
			    		<span class="required">*</span>
	                </td>
	            </tr>
	            <tr>
	                <td class="label">扩展分类：</td>
	                <td>
	                	<input onclick="$(this).parent().append($(this).next('select').clone());" type="button" value="添加" />
	                	<?php if($data['ext_category_id']):?>
                                <?php foreach ($data['ext_category_id'] as $k1 => $v1): ?>
                                <select name="ext_category_id[]">
                                        <option value="">选择分类</option>
                                        <?php foreach ($catData as $k => $v): if($v['id']==$v1) $select = 'selected="selected"'; else $select = ''; ?>
                                        <option <?php echo ($select); ?> value="<?php echo ($v["id"]); ?>"><?php echo str_repeat('-',$v['level'] * 8); echo ($v["cat_name"]); ?></option>
                                        <?php endforeach; ?>
                                </select>
                                <?php endforeach; ?>
                                <?php else:?>
                                <select name="ext_category_id[]">
                                        <option value="">选择分类</option>
                                        <?php foreach ($catData as $k => $v): ?>
                                         <option value="<?php echo ($v["id"]); ?>"><?php echo str_repeat('-',$v['level'] * 8); echo ($v["cat_name"]); ?></option>
                                         <?php endforeach; ?>
                                <?php endif;?>
	                </td>
	            </tr>
	            <tr>
	                <td class="label">logo原图：</td>
	                <td>
	                	<?php showImage($data['pic'],100); ?><br />
	                	<input type="file" name="pic" /> 
	                </td>
	            </tr>
                    <tr>
	                <td class="label">上传视频：</td>
	                <td>
                           
                            <div id="flash_uploader" style="width: 500px; height: 330px;">Your browser doesn't have Flash installed.</div>
                            <div id="aaaa"></div>
                            <div id="bbbb"></div>
	                </td>
	            </tr>
	            <tr>
	                <td class="label">是否最热：</td>
	                <td>
	                	<input type="radio" name="is_hot" value="1" <?php if($data['is_hot'] == 1) echo 'checked="checked"'; ?> />是 
	                	<input type="radio" name="is_hot" value="0" <?php if($data['is_hot'] == 0) echo 'checked="checked"'; ?> />否 
	                </td>
	            </tr>
	            <tr>
	                <td class="label">是否最新：</td>
	                <td>
	                	<input type="radio" name="is_new" value="1" <?php if($data['is_new'] == 1) echo 'checked="checked"'; ?> />是 
	                	<input type="radio" name="is_new" value="0" <?php if($data['is_new'] == 0) echo 'checked="checked"'; ?> />否 
	                </td>
	            </tr>
	            <tr>
	                <td class="label">是否推荐：</td>
	                <td>
	                	<input type="radio" name="is_best" value="1" <?php if($data['is_best'] == 1) echo 'checked="checked"'; ?> />是 
	                	<input type="radio" name="is_best" value="0" <?php if($data['is_best'] == 0) echo 'checked="checked"'; ?> />否 
	                </td>
	            </tr>
	            <tr>
	                <td class="label">是否发布：</td>
	                <td>
	                	<input type="radio" name="is_on_sale" value="1" <?php if($data['status'] == 1) echo 'checked="checked"'; ?> />上架 
	                	<input type="radio" name="is_on_sale" value="0" <?php if($data['status'] == 0) echo 'checked="checked"'; ?> />下架 
	                </td>
	            </tr>
                    <tr>
	                <td class="label">排序数字：</td>
	                <td>
	                    <input size="5" type="text" name="listorder" value="<?php echo ($data["listorder"]); ?>" />
	                </td>
	            </tr>

	        </table>
	        <!-- 描述 -->
	    	<table class="table_content" cellspacing="1" cellpadding="3" width="100%" style="display:none;">
	            <tr>
	            	<td>
	                	<textarea id="goods_desc" name="description"><?php echo ($data["description"]); ?></textarea>
	                </td>
	            </tr>
	    	</table>
	    	
	    	<table cellspacing="1" cellpadding="3" width="100%">
	    		<tr>
	                <td align="center">
	                    <input type="submit" class="button" value=" 确定 " />
	                    <input type="reset" class="button" value=" 重置 " />
	                </td>
	            </tr>
	    	</table>
	    </form>
	</div>
</div>
<script>
$(function() {
        // Setup flash version
        $("#flash_uploader").pluploadQueue({
        // General settings
        runtimes : 'html5,flash,silverlight,html4',//指定上传方式
        url : '/index.php/Admin/Movie/movieUpload',//后台url
        chunk_size : '500mb',//分片大小
        unique_names : true,//文件名是否唯一
        multiple:true,
        filters : {
            max_file_size : '500mb', //最大只能上传500mb的文件
            mime_types: [
            {title : "file", extensions : "mp4"}//限制上传类型
            ],
            prevent_duplicates:true //是否选取重复的文件
        },
        //压缩设置
        resize: {
            crop: false, //是否裁剪图片
        },
        init:{
        FileUploaded:function(up,file,info)
        {
            var response = info.response;
            console.info(response);//调试信息 打印自己看
            //console.info(up);//调试信息 打印自己看

           // console.info(file);//调试信息 打印自己看

           // console.info(info);//调试信息 打印自己看
            if (info.status) {
            $('#aaaa').append("文件路径:<input type='text' value="+response+"/></br>");
            $('#bbbb').append("<input type='hidden' name='mic' value="+response+"/></br>");
            //这块代码很关键 用于当上传完一个文件后 继续显示添加文件和开始上传按钮
            if(up.total.uploaded==up.files.length)
            {
                $(".plupload_buttons").css("display","inline");
                $(".plupload_upload_status").css("display","inline");
                $(".pluploaded_start").addClass("plupload_disabled");
            }

            up.disableBrowse(false);
        }},

        FilesAdded: function (up, files) {
        //文件上传数量限制
        $.each(up.files, function (i, file) {
        //console.info(up.files.length);
        if (up.files.length >1) {
            up.splice(1, up.files.length-1);
            // up.stop();
            alert('只能上传一个文件');

            return false;
        }


        });
        },
        UploadComplete:function(up,files)
        {

            up.refresh();
        },

        QueueChanged:function(up)
        {
            $(".plupload_start").removeClass("plupload_disabled");
        }
        },
        // Flash settings
        flash_swf_url : '/Public/plupload/js/Moxie.swf',
        silverlight_xap_url : '/Public/plupload/js/Moxie.xap'
        });
});
</script>
<script>
// 点击按钮切换table
$("div#tabbar-div p span").click(function(){
	// 获取点击的是第几个按钮
	var i = $(this).index();
	// 显示第i个table
	$(".table_content").eq(i).show();
	// 隐藏其他的table
	$(".table_content").eq(i).siblings(".table_content").hide();
	// 把原来选中的取消选中状态
	$(".tab-front").removeClass("tab-front").addClass("tab-back");
	// 切换点击的按钮的样式为选中状态
	$(this).removeClass("tab-back").addClass("tab-front");
});

$("#promote_start_time").datepicker(); 
$("#promote_end_time").datepicker(); 
UE.getEditor('goods_desc', {
	"initialFrameWidth" : "100%",   // 宽
	"initialFrameHeight" : 400,      // 高
	"maximumWords" : 10000            // 最大可以输入的字符数量
});

// 当选择类型时执行AJAX取出类型的属性
$("select[name=type_id]").change(function(){
	// 获取选中的类型的id
	var type_id = $(this).val();
	if(type_id != "")
	{
		$.ajax({
			type : "GET",
			// 大U生成的地址默认带后缀，如：/index.php/Admin/Goods/ajaxGetAttr.html/type_id/+type_id
			// 第三个参数就是去掉.html后缀否则TP会报错
			url : "<?php echo U('ajaxGetAttr', '', FALSE); ?>/type_id/"+type_id,
			dataType : "json",
			success : function(data)
			{
				var html = "";
				// 循环服务器返回的属性的JSON数据
				$(data).each(function(k,v){
					html += "<p>";
					html += v.attr_name + " : ";
					// 根据属性的类型生成不同的表单元素：
					// 1. 如果属性是可选的那么就有一个+号
					// 2. 如果属性有可选值就是一个下拉框
					// 3. 如果属性是唯一的就生成一个文本框
					if(v.attr_type == 1)
						html += " <a onclick='addnew(this);' href='javascript:void(0);'>[+]</a> ";
					// 判断是否有可选值
					if(v.attr_option_values == "")
						html += "<input type='text' name='ga["+v.id+"][]' />";
					else
					{
						// 先把可选值转化成数组
						var _attr = v.attr_option_values.split(",");
						html += "<select name='ga["+v.id+"][]'>";
						html += "<option value=''>请选择</option>";
						// 循环每个可选值构造option
						for(var i=0; i<_attr.length; i++)
						{
							html += "<option value='"+_attr[i]+"'>"+_attr[i]+"</option>";
						}
						html += "</select>";
					}
					if(v.attr_type == 1)
						html += " 属性价格：￥ <input size='8' name='attr_price["+v.id+"][]' type='text' /> 元";
					html += "</p>";
				});
				$("#attr_container").html(html);
			}
		});	
	}
	else
		$("#attr_container").html("");
});

// 删除图片
$(".delimage").click(function(){
	if(confirm("确定要删除吗？"))
	{
		// 获取图片的ID
		var pic_id = $(this).attr("pic_id");
		// 取出图片所在的LI标签
		var li = $(this).parent();
		$.ajax({
			type : "GET",
			url : "<?php echo U('ajaxDelImg', '', FALSE); ?>/pic_id/"+pic_id,
			success : function(data)
			{
				// ajax请求成功之后再把图片人页面上删除
				li.remove();
			}
		});
		
	}
});
// 判断如果现在没有属性就直接触发AJAX事件获取属性的数据
<?php if(empty($gaData)): ?>
$("select[name=type_id]").trigger("change");
<?php endif; ?>
</script>















<div id="footer">演呗</div>
</body>
</html>
<script type="text/javascript" charset="utf-8" src="/Public/Admin/js/train.js"></script>