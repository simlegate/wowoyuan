<?php

class PublicAction extends Action {
    private $site;
	public function _initialize()
    {     	
      $site = array ();
			$sdata = M ( 'system' )->select ();
			foreach ( $sdata as $key => $val ) {
				$site [$val ['name']] = $val ['contents'];
			}
			$this->site = $site;   
    }

	function index() {
		
		$this->tohome();
	    $isLogged = Cookie::get('isLogged');
		if($isLogged==1){
			header('location:'.SITE_URL.'url=home');	
		}
		else $this->display ();		
	}



//手机检验用户登录
 function phone_login(){
 

 $conference = {
    "Conference": "Future Marketing",
    "Date": "2012-6-1",
    "Address": "Beijing",
    "Members": 
    [
        {
            "Name": "Bob",
            "Age": 32,
            "Company": "IBM",
            "Engineer": true
        },
        {
            "Name": "John",
            "Age": 20,
            "Company": "Oracle",
            "Engineer": false
        },
        {
            "Name": "Henry",
            "Age": 45,
            "Company": "Microsoft",
            "Engineer": false
        }
    ]
}
 }
















	
	//检验用户登录
	 function checkLogin() {
		
		$email = $_POST ["username"];
		
		$userpass =md5(md5($_POST ["password"])+md5($email));
		
		$uModel = D ( 'User' );
		//  此处find()和find(1);
		//  where中应该用= 而不是==
		$user = $uModel->where ( 'mailadres=\''.$email.'\'' )->field ( 'user_id,mailadres,password,userlock' )->find ();
		
		if (empty($user)) {
			$this->error('用户不存在!');
			}
			// 加入用户被锁定
			else if($userpass!=$user['password']){
			   $this->assign('jumpUrl',SITE_URL."url=login");
			   $this->assign('waitSecond',3);
				$this->error('密码错误!');
			
			}
			 else if ($user ['userlock'] == 1) {
			  $this->assign('jumpUrl',SITE_URL."url=login");
			  $this->assign('waitSecond',3);
			  $this->error('你的帐号已经被锁定,请联系管理员!');
			  
			} else {
		        Cookie::set('uid',$user['user_id'],60*60*24);
		        Cookie::set('isLogged',1,60*60*24);				
			    // 记录用户登录信息,,IP ,登录时			
				$this->logindt ( $user ['user_id'] );
				header('location:'.SITE_URL.'url=home');							
			}
		}
	
	
	//  记录用户登录信息  $uid为用户id
	private function logindt($uid) {
		$insert ['user_id'] = $uid;
		$insert ['login_ip'] = real_ip ();
		$insert ['login_time'] = time ();
		D ( 'Logindt' )->add ( $insert );
	
	}
	

	public function validateRegister(){
		$email = $_POST['email'];
		$name  = $_POST['name'];
		if($email){
		$result = D()->query('SELECT user_id FROM wo_user WHERE mailadres =\''.$email.'\'');
		
		if(!empty($result)){ 
		
		echo 1;
		}
		}
		if ($name) {
			$result = D()->query('SELECT user_id FROM wo_user WHERE username =\''.$name.'\'');
		if(!empty($result)) {
		
		echo 1;
		}
		}
		
	}
	
	// 生成验证码
	public function verify(){
		import("ORG.Util.Image");
	    Image::buildImageVerify();		
	}

	public function validateVerify(){
          if(md5($_POST['verify']) == $_SESSION['verify']){
          	echo 1;
          }
          else echo 0;
	}
	

	
public function doRegister(){
	    
		$email      = $_POST['email'];
		$name       = $_POST['nickname'];
		$pas        = md5(md5($_POST['password'])+md5($email));
		$repas      = $_POST['repassword'];
		$grade      = $_POST['grade'];
		$academy    = $_POST['academy'];
		$profession = $_POST['profession']; 
		if($repas!=$_POST['password']){
			echo 6;
			}
		else {
			$data = D()->execute('INSERT INTO wo_user(mailadres,username,password,grade,academy,profession,regtime) VALUES(\''.$email.'\',\''.$name.'\',\''.$pas.'\','.$grade.',\''.$academy.'\',\''.$profession.'\','.time().')');
		if ($data==0){
		     echo 2;
		}
		else{
			$uid = D()->query('SELECT user_id FROM wo_user WHERE mailadres =\''.$email.'\'');
			if (!empty($uid)){				
			 Cookie::set('uid',$uid[0]['user_id'],60*60*24);
		     Cookie::set('isLogged',1,60*60*24);
		     // 记录注册状态,防止访问修改头像页面
		     Session::set('register_id',1);
			echo 1;
			}
			else echo 0;
		}
		}
		
	}
	//  用户注册
	public function register(){	
		$this->tohome();
		$this->forbidRegi();	
		$data= D('User')->gettop5();
		//dump($data);
		$this->assign("data",$data);
		$this->display();
	}
	public function upload_head(){
	     $this->tohome();	
	     $this->forbidRegi();
	     if (Session::get('register_id')==1){
		$data= D('User')->gettop5();
		//dump($data);
	
		$this->assign("data",$data);
	    $this->display();
	     }
	     else {
	     	Session::set('register_id','');	     	
	     	$this->error('访问的页面不存在!');
	     	
	     }
	}
	
	
	private function tohome() {

	
	if($this->site['site_closed']==1) {
	        $this->assign('close',1);
	    	$this->assign('jumpUrl',SITE_URL."url=login");
	    	$this->error('对不起,网站已关闭,请稍后再试!');
	    }
	
	}
	
	// 注册方式
	private function forbidRegi(){
	  //  注册方式  1关闭注册  2邀请注册 3 公开注册(默认)
	if ($this->site['register_type']==1){
		$this->assign('jumpUrl',SITE_URL."url=login");
		$this->error('对不起,停止注册,请稍后再试!');
	}
	
	
	}
    
    
	//  退出   
	public function loginout() {
		Cookie::delete('isLogged');
		Cookie::delete('uid');
		$this->assign('jumpUrl',SITE_URL."url=login");
		$this->success('成功退出!');
	
	}
//  获得微社分类列表  
   private  function _getMenu(){
   	$i = 0;
   	$menu = array();
   	
   	//$data = D()->query('SELECT class_first_name,class_second_name,s.class_first_id  FROM wo_goods_class_first as f , wo_goods_class_second  as s WHERE f.class_first_id = s.class_first_id');
   	$first = D()->query('SELECT forum_class_first_name,forum_class_first_id FROM wo_forum_class_first');
   	$second = D()->query('SELECT forum_class_second_name,forum_class_first_id FROM wo_forum_class_second');
 
  
  foreach ($first as $key =>$value ){
 	foreach ($second as $keys => $values){
 		if($value['forum_class_first_id'] ==$values['forum_class_first_id']){
 		$menu[$value['forum_class_first_name']][$i++] = $values['forum_class_second_name'];
 		}
 		else $i=0;
 	}
 	
 }

return $menu;

 }
 
 // 通过AJAX获得商品分类
  function getClassByAjax(){
  	$type  =  $_GET['type'];
    $first =  $_GET['id']; 
 	if(empty($first)&&empty($type)) $this->error('参数错误!');
 	if($type==1)
 	{$data = D()->query('SELECT class_second_name FROM wo_goods_class_second WHERE class_first_id = '.$first);
 	}
 	else {
 	$data = D()->query('SELECT forum_class_second_name FROM wo_forum_class_second WHERE forum_class_first_id = '.$first);
 	}
 	
 	foreach($data as $value)
 	foreach ($value as $values)
 	//echo $data[0]['class_second_name'];
 	{
 	$return.=$values.',';
 	}
 	
 echo $return;
}
 
//  找回密码  
	public function sendMail() {
		
		$this->display();
	}
	
public function doSendMail() {
		
		if ( !isValidEmail($_POST['email']) )
			$this->error('邮箱格式错误');
		
		$user =	M('user')->where('mailadres ="'.$_POST['email'].'"')->find();
        if(!$user) {
        	$this->error("该邮箱没有注册");
        }else {
            $code = base64_encode( $user["user_id"] . "." . md5($user["user_id"] . '+' . $user["password"]) );
            $url = SITE_URL.'Public/resetpw/code/'.$code;
            $body = <<<EOD
<strong>{$user["username"]}，你好: </strong><br/>

您只需通过点击下面的链接重置您的密码: <br/>

<a href="$url">$url</a><br/>

如果通过点击以上链接无法访问，请将该网址复制并粘贴至新的浏览器窗口中。(目前只支持chrome浏览器)<br/>

如果你错误地收到了此电子邮件，你无需执行任何操作来取消帐号！此帐号将不会启动。
EOD;
			
			global $ts;
			$email_sent = A('Mail')->send_email($user['mailadres'], "重置窝窝园密码", $body);
			
            if ($email_sent) {
	            $this->assign('jumpUrl', SITE_URL."url=login");
	            $this->success("已把密码发送到你的邮箱".$_POST['email']."中，请注意查收");
            }else {
            	$this->error('抱歉: 邮件发送失败，请稍好重试');
            }
		}
	}
	
	public function resetpw() {

		$code = explode('.', base64_decode($_GET['code']));
        $user = M('user')->where('`user_id`=' . $code[0])->find(); 
        if ( $code[1] == md5($code[0].'+'.$user["password"]) ) {
	        $this->assign('email',$user["mailadres"]);
	        $this->assign('code', $_GET['code']);
	        $this->display();
        }else {
        	$this->error("抱歉: 链接错误");
        }
	}
	
public function doresetpw() {
		if($_POST["password"] != $_POST["repassword"]) {
               $this->error('两次密码不一致！');
        }else{
        
				$code = explode('.', base64_decode($_POST['code']));
				
		        $user = M('user')->where('`user_id`=' . $code[0])->find();
        
		        if ( $code[1] == md5($code[0] . '+' . $user["password"]) ) {
			        $user['password'] = md5(md5($_POST['password'])+md5($user['mailadres']));
			        $res = M('user')->save($user);
			       // echo $res;
			        if ($res) {
			        	//echo 1;
			        	$this->assign('jumpUrl', SITE_URL."url=login");
			        	$this->success('保存成功');
			        }else {
			        	//echo 2;
			        	$this->error('抱歉: 保存失败，请稍后重试');
			        }
		        }else {
		        	$this->error("抱歉: 非法操作！");
		        	//echo 3;
		        }
        }
	}
	

	
}