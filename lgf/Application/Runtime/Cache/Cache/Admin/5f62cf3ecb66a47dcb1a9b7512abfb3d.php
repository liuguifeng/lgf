<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Menu</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="/Public/Admin/Styles/general.css" rel="stylesheet" type="text/css" />

<style type="text/css">
body {
  background: #80BDCB;
}
#tabbar-div {
  background:#278296;
  padding-left:10px;
  height:21px;
  padding-top:0px;
}
#tabbar-div p {
  margin:1px 0 0 0;
}
.tab-front {
  background:#80BDCB;
  line-height:20px;
  font-weight:bold;
  padding:4px 15px 4px 18px;
  border-right:2px solid #335b64;
  cursor:hand;
  cursor:pointer;
}
.tab-back {
  color:#F4FAFB;
  line-height:20px;
  padding:4px 15px 4px 18px;
  cursor:hand;
  cursor:pointer;
}
.tab-hover {
  color:#F4FAFB;
  line-height:20px;
  padding:4px 15px 4px 18px;
  cursor:hand;
  cursor:pointer;
  background:#2F9DB5;
}
#top-div
{
  padding:3px 0 2px;
  background:#BBDDE5;
  margin:5px;
  text-align:center;
}
#main-div {
  border:1px solid #345C65;
  padding:5px;
  margin:5px;
  background:#FFF;
}
#menu-list {
  padding:0;
  margin:0;
}
#menu-list ul {
  padding:0;
  margin:0;
  list-style-type: none;
  color:#335B64;
}
#menu-list li {
  padding-left:16px;
  line-height:28px;
  cursor:hand;
  cursor:pointer;
}
#main-div a:visited, #menu-list a:link, #menu-list a:hover {
  color:#335B64
  text-decoration:none;
}
#menu-list a:active {
  color:#EB8A3D;
}
.explode {
  background:url(/Public/Admin/Images/menu_minus.gif) no-repeat 0px 3px;
  font-weight:bold;
}
.collapse {
  background:url(/Public/Admin/Images/menu_plus.gif) no-repeat 0px 3px;
  font-weight:bold;
}
.menu-item {
  background:url(/Public/Admin/Images/menu_arrow.gif) no-repeat 0px 3px;
  font-weight:normal;
}
#help-title {
  font-size:14px;
  color:#000080;
  margin:5px 0;
  padding:0px;
}
#help-content {
  margin:0;
  padding:0;
}
.tips {
  color:#CC0000;
}
.link {
  color:#000099;
}
</style>

</head>
<body>
<div id="tabbar-div">
    <p>
        <span style="float:right; padding:3px 5px;" >
            <a href="javascript:toggleCollapse();">
                <img id="toggleImg" src="/Public/Admin/Images/menu_minus.gif" width="9" height="9" border="0" alt="闭合" />
            </a>
        </span>
        <span class="tab-front" id="menu-tab">菜单</span>
    </p>
</div>
<div id="main-div">
    <div id="menu-list">
        <ul id="menu-ul">
            <?php foreach ($btn as $k =>$v): ?>
            <li class="explode" key="02_cat_and_goods" name="menu">
            <?php echo ($v["pri_name"]); ?>
                <ul>
                   <?php foreach ($v['children'] as $k1 =>$v1): ?>
                    <li class="menu-item"><a href="<?php echo U($v1['module_name'].'/'.$v1['controller_name'].'/'.$v1['action_name']); ?>" target="main-frame"><?php echo ($v1["pri_name"]); ?></a></li>
                    <?php endforeach;?>
                </ul>
            </li> 
            <?php endforeach;?>
        </ul>
    </div>
    <div id="help-div" style="display:none">
        <h1 id="help-title"></h1>
        <div id="help-content"></div>
    </div>
</div>
<script type="text/javascript" src="/Public/Admin/Js/global.js"></script>
<script type="text/javascript" src="/Public/Admin/Js/utils.js"></script>
<script type="text/javascript" src="/Public/Admin/Js/transport.js"></script>
<script language="JavaScript">
    var collapse_all = "闭合";
    var expand_all = "展开";
    var collapse = true;

    function toggleCollapse() {
        var items = document.getElementsByTagName('LI');
        for(i = 0; i < items.length; i++) {
            if(collapse) {
                if (items[i].className == "explode") {
                    toggleCollapseExpand(items[i],"collapse");
                }
            } else {
                if(items[i].className == "collapse") {
                    toggleCollapseExpand(items[i],"explode");
                    ToggleHanlder.Reset();
                }
            }
        }
        collapse = !collapse;
        document.getElementById('toggleImg').src = collapse ? '/Public/Admin/Images/menu_minus.gif' : '/Public/Admin/Images/menu_plus.gif';
        document.getElementById('toggleImg').alt = collapse ? collapse_all : expand_all;
    }

    function toggleCollapseExpand(obj, status) {
        if (obj.tagName.toLowerCase() == 'li' && obj.className != 'menu-item') {
            for (i = 0; i < obj.childNodes.length; i++) {
                if (obj.childNodes[i].tagName == "UL") {
                    if (status == null) {
                        if (obj.childNodes[1].style.display != "none") {
                            obj.childNodes[1].style.display = "none";
                            ToggleHanlder.RecordState(obj.getAttribute("key"),"collapse");
                            obj.className = "collapse";
                        } else {
                            obj.childNodes[1].style.display = "block";
                            ToggleHanlder.RecordState(obj.getAttribute("key"),"explode");
                            obj.className = "explode";
                        }
                        break;
                    } else {
                        if(status == "collapse") {
                            ToggleHanlder.RecordState(obj.getAttribute("key"),"collapse");
                            obj.className = "collapse";
                        } else {
                            ToggleHanlder.RecordState(obj.getAttribute("key"),"explode");
                            obj.className = "explode";
                            }
                            obj.childNodes[1].style.display = (status == "explode") ? "block" : "none";
                    }
                }
            }
        }
    }

    document.getElementById('menu-list').onclick = function(e) {
        var obj = Utils.srcElement(e);
        toggleCollapseExpand(obj);
    }

    document.getElementById('tabbar-div').onmouseover = function(e) {
        var obj = Utils.srcElement(e);
        if (obj.className == "tab-back") {
            obj.className = "tab-hover";
        }
    }

    document.getElementById('tabbar-div').onmouseout = function(e) {
        var obj = Utils.srcElement(e);
        if (obj.className == "tab-hover") {
            obj.className = "tab-back";
        }
    }

    document.getElementById('tabbar-div').onclick = function(e) {
        var obj = Utils.srcElement(e);
        var hlpTab = document.getElementById('help-tab');
        var mnuDiv = document.getElementById('menu-list');
        var hlpDiv = document.getElementById('help-div');

        if (obj.id == 'help-tab') {
            mnuTab.className = 'tab-back';
            hlpTab.className = 'tab-front';
            mnuDiv.style.display = "none";
            hlpDiv.style.display = "block";

            loc = parent.frames['main-frame'].location.href;
            pos1 = loc.lastIndexOf("/");
            pos2 = loc.lastIndexOf("?");
            pos3 = loc.indexOf("act=");
            pos4 = loc.indexOf("&",pos3);

            filename = loc.substring(pos1 + 1, pos2 - 4);
            act = pos4 < 0 ? loc.substring(pos3 + 4) : loc.substring(pos3 + 4,pos4);
            loadHelp(filename,act);
        }
    }

    //菜单展合状态处理器
    var ToggleHanlder = new Object();

    Object.extend(
        ToggleHanlder ,{
            SourceObject : new Object(),
            CookieName   : 'Toggle_State',
            RecordState : function(name,state){
                if(state == "collapse") {
                    this.SourceObject[name] = state;
                } else {
                    if(this.SourceObject[name]) {
                        delete(this.SourceObject[name]);
                    }
                }
                var date = new Date();
                date.setTime(date.getTime() + 99999999);
                document.setCookie(this.CookieName, this.SourceObject.toJSONString(),date.toGMTString());
            },

            Reset :function() {
                var date = new Date();
                date.setTime(date.getTime() + 99999999);
                document.setCookie(this.CookieName,"{}",date.toGMTString());
            },

            Load : function() {
                if(document.getCookie(this.CookieName) != null) {
                    this.SourceObject = eval("("+ document.getCookie(this.CookieName) +")");
                    var items = document.getElementsByTagName('LI');
                    for(var i = 0; i < items.length; i++) {
                        if( items[0].getAttribute("name") == "menu" && items[0].getAttribute("id") != '20_yun') {
                            for(var k in this.SourceObject) {
                                if(typeof(items[i]) == "object") {
                                    if(items[i].getAttribute('key') == k) {
                                        toggleCollapseExpand(items[i], this.SourceObject[k]);
                                        collapse = false;
                                    }
                                }
                            }
                        }
                    }
                }
                document.getElementById('toggleImg').src = collapse ? '/Public/Admin/Images/menu_minus.gif' : '/Public/Admin/Images/menu_plus.gif';
                document.getElementById('toggleImg').alt = collapse ? collapse_all : expand_all;
            }
        }
    );

</script>

</body>
</html>