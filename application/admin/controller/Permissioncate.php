<?php
namespace app\admin\controller;
use gmars\rbac\Rbac;
use Request;
use Db;
class PermissionCate extends Common
{	
	public function list()
	{
		return $this->fetch();
	}
	public function show()
	{
		$rbac = new Rbac();
		$arr=$rbac->getPermissionCategory([['status', '=', 1]]);
		echo json_encode($arr);
	}
	public function addAction()
	{
		// $name=Request::post('name');
		// $description=Request::post('description');
		$data=Request::post();
		$validate = new \app\admin\validate\Permissioncate;
		if(!$validate->check($data)){
			$arr=['code'=>'1','status'=>'error','data'=>$validate->getError()];
		    echo json_encode($arr);
		    die;
		}		
		$rbac = new Rbac();
		$getarr=$rbac->getPermissionCategory([['name', '=', $data['name']]]);
		if (empty($getarr)) {
			$rbac->savePermissionCategory([
		    'name' => $data['name'],
		    'description' => $data['description'],
		    'status' => 1
		]);
		$arr=['code'=>'1','status'=>'ok','data'=>'添加成功'];
		echo json_encode($arr);
		}else{
			$arr=['code'=>'0','status'=>'error','data'=>'分类已存在'];
			echo json_encode($arr);
			die;
		}		
	}
	public function del()
	{
		$id=Request::post('id');
		$rbac = new Rbac();
		$rbac->delPermissionCategory($id);
		$arr=['code'=>'0','status'=>'ok','data'=>'分类已删除'];
		echo json_encode($arr);
	}
	public function delMore()
	{
		$id=Request::post('id');
		if (empty($id)) {
			$arr=['code'=>'0','status'=>'error','data'=>'不能为空'];
		    echo json_encode($arr);
		    die;
		}
		$arr=explode(',', $id);
		array_shift($arr);
		$rbac = new Rbac();
		$rbac->delPermissionCategory($arr);
		$arr=['code'=>'0','status'=>'ok','data'=>'分类已删除'];
		echo json_encode($arr);
	}
	public function updateaction(){
		$data=Request::post();
		$validate = new \app\admin\validate\Permissioncate;
		if(!$validate->check($data)){
			$arr=['code'=>'1','status'=>'error','data'=>$validate->getError()];
		    echo json_encode($arr);
		    die;
		}
		$rbac = new Rbac();
		$getarr=$rbac->getPermissionCategory([['name', '=', $data['name']]]);
		if (empty($getarr)) {
			Db::table('permission_category')->update($data);
		     $arr=['code'=>'1','status'=>'ok','data'=>'修改成功'];
		     echo json_encode($arr);
		}else{
			$arr=['code'=>'0','status'=>'error','data'=>'分类已存在'];
			echo json_encode($arr);
			die;
		}
	}

}