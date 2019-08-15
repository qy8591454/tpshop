<?php
namespace app\admin\controller;
use Request;
use Db;
use Cache;
use Redis;
class Goods extends Common
{
	 function get($num = 50000)  // $num为生成汉字的数量
    {
        //$b = '';
        for ($i=0; $i<$num; $i++) {
            // 使用chr()函数拼接双字节汉字，前一个chr()为高位字节，后一个为低位字节
              $a = chr(mt_rand(0xB0,0xD0)).chr(mt_rand(0xA1, 0xF0));
              $d = chr(mt_rand(0xB0,0xD0)).chr(mt_rand(0xA1, 0xF0));
              $c = chr(mt_rand(0xB0,0xD0)).chr(mt_rand(0xA1, 0xF0));
              $d=$a.$d.$c;
            // 转码
             $b= iconv('GB2312', 'UTF-8', $d);
             "<br>";
        $add = ['name' => $b,'brand_id'=>2];
        $acc=Db::name('goods')->insert($add);

        }
        //return $b;

    }


	public function list()
	{
		//存一个串
		return $this->fetch();
	}
	public function show()
	{
		$a=Cache::ZREVRANGE ('rank:2018',0,4);

		// $redis= new redis;
		// $redis->connect('127.0.0.1',6379);
		$name=input('get.name');
		$ass=Cache::get($name);
		if ($name!='') {
			if (!$ass) {
				Cache::set($name,1,36000);
			}else{
				Cache::set($name,$ass+1,36000);
 				Cache::ZINCRBY ('rank:2018',$ass,$name);
			}
		}
		if ($ass>10) {
			$a=('sel'.$name);
			$arr=Cache::get($a);
			if (!$arr) {
				$arr=Db::query("select goods.id,goods.name, attr_cate,brand.name as brand_name,goods_category.name as cate_name,goods.online_status from goods join brand on goods.brand_id=brand.id join goods_category on goods_category.id=goods.cate_id where goods.name like '%$name%'");
				Cache::set($a,$arr,36000);
			}
		}else{
			//die;
			if ($name=='') {
				$arr=Db::query("select goods.id,goods.name, attr_cate,brand.name as brand_name,goods_category.name as cate_name,goods.online_status from goods join brand on goods.brand_id=brand.id join goods_category on goods_category.id=goods.cate_id");
			}else{
				$arr=Db::query("select goods.id,goods.name, attr_cate,brand.name as brand_name,goods_category.name as cate_name,goods.online_status from goods join brand on goods.brand_id=brand.id join goods_category on goods_category.id=goods.cate_id where goods.name like '%$name%'");
			}
		}		
		$json=['code'=>'0','status'=>'ok','data'=>$arr,'aet'=>$a];
		return json($json);
	}

	public function showCate()
	{
		$arr=Db::query("select * from goods_category");
        $this->getTree($arr);
	}
	private function getTree($arr,$pid = 0, $level = 0){
        foreach ($arr as $key => $value){
            //第一次遍历,找到父节点为根节点的节点 也就是pid=0的节点
            if ($value['pid'] == $pid){
                //父节点为根节点的节点,级别为0，也就是第一级
                $flg = str_repeat('|--',$level);
                // 更新 名称值
                $value['name'] = $flg.$value['name'];
                // 输出 名称
                $name=$value['name'];
                $id=$value['id'];
                echo "<option value='$id'>$name</option>";
                //把数组放到list中
                $list[] = $value;
                //把这个节点从数组中移除,减少后续递归消耗
                unset($arr[$key]);
                //开始递归,查找父ID为该节点ID的节点,级别则为原级别+1
                $this->getTree($arr, $value['id'], $level+1);
            }
        }
    }

	public function add()
	{
		return $this->fetch();
	}
	public function goodsImg()
	{
		return $this->fetch();
	}
	public function goodsImgshow()
	{
		$arr=Db::query("select * from goods_img");
		$json=['code'=>'0','status'=>'ok','data'=>'添加成功'];
	    return json($json);
	}
	public function goodsImgadd()
	{
		var_dump($_FILES);
	}
	public function addaction()
	{
		$data=Request::post();
        unset($data['__token__']);
        $userId = Db::name('goods')->insertGetId($data);
	    $json=['code'=>'0','status'=>'ok','data'=>'添加成功'];
	    return json($json);
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

}