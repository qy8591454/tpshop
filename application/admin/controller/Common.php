<?php
namespace app\admin\controller;
use think\Controller;
use think\facade\Session;
class Common extends Controller
{
    public function __construct(){
        parent::__construct();
        $a=Session::get('username');
        $this->assign('username',$a);
        if (empty($a)){
            $this->redirect('login/login');
        }
    }
    public function commontoken(){
    	$token = $this->request->token('__token__', 'sha1');
    	$arr=['status'=>$token];
		echo json_encode($arr);
    }
}