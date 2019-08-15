<?php
namespace app\admin\controller;
use gmars\rbac\Rbac;
use Request;
use Db;
class Role extends Common
{
	public function list()
	{
		//存一个串
		return $this->fetch();
	}
	public function show()
	{
		$rbac = new Rbac();
		$arr=Db::query("select id,name,description from role");
		$json=['code'=>'0','status'=>'ok','data'=>$arr];
		return json($json);
	}
	public function permissionShow()
	{
		$rbac = new Rbac();
		$arr=Db::query("select p.id,p.name,p.description,p.path,p_c.name as p_c_name,p.category_id from permission as p join permission_category as p_c on p.category_id=p_c.id");


		$newarr=[];
		foreach ($arr as $key => $value) {
			$newarr[$value['p_c_name']][]=$value;
			
		}
		// var_dump($newarr);
		// die;
		$json=['code'=>'0','status'=>'ok','data'=>$newarr];
		return json($json);
	}

	public function mypermissionShow()
	{
		$id=Request::get('id');
		$rbac = new Rbac();
		$arr=Db::query("select permission_id from role_permission where role_id='$id'");
		$json=['code'=>'0','status'=>'ok','data'=>$arr];
		return json($json);
	}

	public function updateAction(){
	    $data=Request::post();
		$rbac = new Rbac();
		$name=$data['name'];
		$id=$data['id'];
		$permission_id=$data['permission_id'];
		$up_data=$data;
		unset($up_data['__token__']);
		unset($up_data['permission_id']);

		$arr=Db::query("select * from role where name='$name'");
		if (empty($arr)||!empty($arr)&&$arr[0]['id']==$data['id']) {
			$arr=Db::table('role')->update($up_data);		    
		    //删掉，重新入库
		    $arr=Db::query("delete from role_permission where role_id='$id'");		    
		    	$pid_arr=explode(',', $permission_id);
		    	array_shift($pid_arr);
		    	foreach ($pid_arr as $key => $value) {
		    		$arr=Db::query("insert into `role_permission`(`role_id`,`permission_id`) values ('$id','$value')");
		    	}
		    	$arr=['code'=>'0','status'=>'ok','data'=>'修改成功'];
		}else{
			$arr=['code'=>'1','status'=>'error','data'=>'重名了'];		
		}
		return json($arr);
	}

	public function add()
	{
		return $this->fetch();
	}

	public function addAction()
	{
		$data=Request::post();
		$validate = new \app\admin\validate\Role;
		if(!$validate->check($data)){
			$arr=['code'=>'1','status'=>'error','data'=>$validate->getError()];
			return json($arr);
		}
		$rbac = new Rbac();
		$name=$data['name'];
		$description=$data['description'];
		$permission_id=$data['permission_id'];
		$getname=Db::query("select * from role where name='$name'");
		$arr=explode(',', $permission_id);
		array_shift($arr);
		$permission_id=implode(',', $arr);
		if (empty($getname)) {
			$rbac->createRole([
				'name' => $name,
				'description' => $description,
				'status' => 1
			],$permission_id);
			$json=['code'=>'0','status'=>'ok','data'=>$arr];
			return json($json);
		}else{
			$json=['code'=>'1','status'=>'error','data'=>'名字不能重复'];
			return json($json);

		}
	}
	
}