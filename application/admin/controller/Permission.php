<?php
namespace app\admin\controller;
use gmars\rbac\Rbac;
use Request;
use Db;
class Permission extends Common
{
	public function list()
	{
		//存一个串
		return $this->fetch();
	}
	public function show()
	{
		$rbac = new Rbac();
		$arr=Db::query("select p.id,p.name,p.description,p.path,p_c.name as p_c_name from permission as p join permission_category as p_c on p.category_id=p_c.id");
		$json=['code'=>'0','status'=>'ok','data'=>$arr];
		return json($json);
	}
	public function addAction()
	{
		$data=Request::post();
		$validate = new \app\admin\validate\Permission;
		if(!$validate->check($data)){
			$arr=['code'=>'1','status'=>'error','data'=>$validate->getError()];
			return json($arr);
		}

		$rbac = new Rbac();
		$getname=$rbac->getPermission([['name', '=', $data['name']]]);
		$getpath=$rbac->getPermission([['path', '=', $data['path']]]);
		if (empty($getname)&&empty($getpath)) {
			$arr=$rbac->createPermission([
				'name' => $data['name'],
				'description' => $data['description'],
				'status' => 1,
				'type' => 1,
				'category_id' => $data['category_id'],
				'path' => $data['path'],
			]);
			$json=['code'=>'0','status'=>'ok','data'=>$arr];
			return json($json);
		}else{
			$json=['code'=>'0','status'=>'ok','data'=>'名字或路径不能重复'];
			return json($json);

		}
	}
	public function del()
	{
		$id=Request::post('id');
		$rbac = new Rbac();
		$rbac->delPermissionCategory($id);
		$json=['code'=>'0','status'=>'ok','data'=>'分类已删除'];
		return json($json);
	}
	public function delMore()
	{
		$id=Request::post('id');
		if (empty($id)) {
			$json=['code'=>'0','status'=>'error','data'=>'不能为空'];
		    return json($json);
		    die;
		}
		$arr=explode(',', $id);
		array_shift($arr);
		$rbac = new Rbac();
		$rbac->delPermissionCategory($arr);
		$json=['code'=>'0','status'=>'ok','data'=>'分类已删除'];
		return json($json);
	}
	public function updateaction(){
		$data=Request::post();
		$validate = new \app\admin\validate\Permission;
		if(!$validate->check($data)){
			$json=['code'=>'1','status'=>'error','data'=>$validate->getError()];
		    return json($json);
		    die;
		}
		$rbac = new Rbac();
		$getarr=$rbac->getPermissionCategory([['name', '=', $data['name']]]);
		if (empty($getarr)) {
			Db::table('permission_category')->update($data);
		     $json=['code'=>'1','status'=>'ok','data'=>'修改成功'];
		     return json($json);
		}else{
			$json=['code'=>'0','status'=>'error','data'=>'分类已存在'];
			return json($json);
			die;
		}
	}

		// $data=Request::post();
		// $validate = new \app\admin\validate\Permission;
		// if(!$validate->check($data)){
		// 	$arr=['code'=>'1','status'=>'error','data'=>$validate->getError()];
		//     echo json_encode($arr);
		//     die;
		// }
	// public function del()
	// {
	// 	//先验证串
	// 	// $id=Request::get('token');
	// 	$id=Request::post('id');
	// 	$rbac = new Rbac();
	// 	$rbac->delPermission($id);
	// 	$arr=['code'=>'0','status'=>'ok','data'=>'分类已删除'];
	// 	echo json_encode($arr);;
	// }
	// public function addAction()
	// {
	// 	// $name=Request::post('name');
	// 	// $description=Request::post('description');
	// 	$data=Request::post();
	// 	$validate = new \app\admin\validate\Permission;
	// 	if(!$validate->check($data)){
	// 		$arr=['code'=>'1','status'=>'error','data'=>$validate->getError()];
	// 	    echo json_encode($arr);
	// 	    die;
	// 	}		
	// 	$rbac = new Rbac();
	// 	$getarr=$rbac->getPermissionCategory([['name', '=', $data['name']]]);
	// 	if (empty($getarr)) {
	// 	// 	$rbac->savePermissionCategory([
	// 	//     'name' => $data['name'],
	// 	//     'description' => $data['description'],
	// 	//     'status' => 1
	// 	// ]);
	// 		$rbac->createPermission([
	// 			'name' => '文章列表查询',
	// 			'description' => '文章列表查询',
	// 			'status' => 1,
	// 			'type' => 1,
	// 			'category_id' => 1,
	// 			'path' => 'article/content/list',
	// 		]);
	// 	$arr=['code'=>'1','status'=>'ok','data'=>'添加成功'];
	// 	echo json_encode($arr);
	// 	}else{
	// 		$arr=['code'=>'0','status'=>'error','data'=>'分类已存在'];
	// 		echo json_encode($arr);
	// 		die;
	// 	}		
	// }
}