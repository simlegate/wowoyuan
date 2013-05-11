<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>    
     <title>登录 - 窝窝园</title>
      <script>
	    var _PUBLIC_  = '__PUBLIC__';
	    var SITE_URL  = '<?php echo SITE_URL;?>'; 
    </script>
     <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />  
     <script src="__PUBLIC__/javascript/jquery-1.4.2.min.js"></script>
     <script type="text/javascript" src="__PUBLIC__/javascript/jq.js"></script> 
     <script src="__PUBLIC__/javascript/wo_mailAutoComplete.js"></script>
     <link rel="shortcut icon" href="__PUBLIC__/images/wo.png" type="image/x-icon" />
     <link rel="stylesheet" type="text/css" href="__PUBLIC__/style/button.css"/>
     <meta name="title" content="我没有在窝窝园，就在窝窝园的路上......" />
     <meta name="description" content="成都大学，什么是窝窝园？在这里我们可以轻松的了解身在同一个大学的他或她在想什么，做什么，还可以不花心思的知道身边的他们缺什么不缺什么。这就是窝窝园"/>
     <meta name="keywords" content="成都大学 ,成都,wowoyuan,窝窝园,大学,社交,闪存,微贴,商场" />
    </head>    
    <body>
        <div>
            <form name="LoginForm" method="post" action="__URL__/checkLogin" >
                <div id="ind_1">
                   
                             <div>   <a href="<?php echo SITE_URL;?>Reg" class="btn btn-black" id="regis">注册</a></div>
                       
                            
                             <div class="indexlogo"><img src="__PUBLIC__/images/wowo.jpg" alt="login"  /></div>                     
                </div>
                <div id="ind_2">
                      <div id="ind_2_in">
                                <div class="uname">
                                      <label id="uname_label">E-mail</label>
                                      <input type="text" class="so_text" name="username" id="uname_text"  onfocus="fos(this,true)" onblur="blr(this,true)"/>
                                     
                               </div>
                       
                                <div class="password">
                                      <label id="pass_label">Password</label>
                                      <input type="password" class="so_text" name="password" id="pass_text" onfocus="fos(this)" onblur="blr(this)"/>
                                     
                               </div>                    
                            <div class="forget"><a href="<?php echo SITE_URL;?>forgetpw" class="forgetpw">忘记密码?</a>
                            <input name="submit" class="btn btn-blue"  id="login" value="登录" type="submit" /></div>
                            <div class="f_tiyan"><a href="<?php echo SITE_URL;?>experience" class="">新手上路，领先体验窝窝园？</a></div>                            
                     </div>
                </div>
            </form>
        </div>
        <script type="text/javascript"> 

        function fos(o,t){
    		var o = $(o);
    		if(!!t){
    			o=o.parent().parent();
    		}else{
    			o=o.parent();
    		}
       		o.addClass("hide");
       		$('*.login_msg').remove();
        }
        function blr(o,t){
    		var e = $(o);
    		if(!!t){
    			e=e.parent().parent();
    		}else{
        		e=e.parent();
    		}
        	if($.trim(o.value)==''){
        		e.removeClass("hide");
        	}else{
        		e.addClass("hide");
        	}
        }
       	$('#login').click(function(){
       		if ($('#uname_text').val() == "")
               {        			
       			
       			if($('*.login_msg').length){
       				return false;
       			}else{
       			$('#ind_2_in').append('<div class="login_msg">亲,你还没有填写登录邮箱哦!</div>');
                   return false;
               }
               }
               if ($('#pass_text').val() == "")
               {
               	if($('*.login_msg').length){
       				return false;
       			}else{
               	$('#ind_2_in').append('<div class="login_msg">亲,你忘记填写密码了!</div>');               
               	 return false;
              }
               }
           });   
       	var y = $("#uname_text");
       	y.mailAutoComplete();
       	y.triggerHandler("focus");
       	y.triggerHandler("blur");
        </script>
    </body>
</html>