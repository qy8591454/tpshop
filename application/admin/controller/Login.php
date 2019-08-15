<?php
namespace app\admin\controller;
use Session;
use Request;
use think\Controller;
use think\captcha\Captcha;
use app\admin\model\User;
class Login extends controller
{
    public function login()               //登录展示页面
    {
        return $this->fetch();
    }

    public function loginAction()       //登录判断页面
    {
    	$verify=Request::post('verify');
    	$user_name=Request::post('user_name');
    	$password=Request::post('password');
        $verify_session=Session::get('verify');
        if ($verify==$verify_session) {
        	$arr=['code'=>'0','status'=>'error','data'=>'验证码错误'];
        	echo json_encode($arr);
        	die;
        }else{
        	$data=['user_name'=>$user_name,'password'=>md5($password)];
        	$arr=User::where($data)->find();
        	if (empty($arr)) {
        		$arr=['code'=>'1','status'=>'error','data'=>'用户或者密码错误'];
        		echo json_encode($arr);
        	    die;
        	}else{
        		$arr=['code'=>'2','status'=>'ok','data'=>'正确'];
        		session::set('username',"$user_name");
        		echo json_encode($arr);
        	    die;
        	}
        }
    }

    //生成验证码，生成图片
    public function verify()
    {
    	// header("Content-type: image/png");
    	// $string = mt_rand(999,9999);
    	// Session::set('verify',$string);
    	// $im     = imagecreatefrompng("static/images/button1.png");
    	// $orange = imagecolorallocate($im, 0, 0, 0);
    	// $px     = (imagesx($im) - 7.5 * strlen($string)) / 2;
    	// imagestring($im, 3, $px, 9, $string, $orange);
    	// imagepng($im);
    	// imagedestroy($im);
    	// exit(0);

    	$captcha = new Captcha();
    	return $captcha->entry();
    }
}