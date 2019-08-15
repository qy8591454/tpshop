<?php
namespace app\admin\controller;
use gmars\rbac\Rbac;
use Request;
use Db;
class Product extends Common
{	
	public function list()
	{
		return $this->fetch();
	}
	public function show()
	{
		
		$arr=Db::query("select * from product");
		$json=['code'=>'0','status'=>'ok','data'=>$arr];
        return json($json);
	}
	
	public function addAction()
	{
	   $name=$_POST['name'];
       $description=$_POST['description'];
       $price=$_POST['price'];
       $status=$_POST['status'];
        if (isset($_FILES['myfile']) && !empty($_FILES['myfile'])) {
            //定义文件路径
            $upload_path = "static/images/";
            //获取文件后缀
            $string = strrev($_FILES['myfile']['name']);
             $array = explode('.',$string);
             $ex = strrev($array[0]);
             //拼接日期随机数重命名
            $rename = date("Y").date("m").date("d").date("H").date("i").date("s").rand(100, 999).".".$ex;
            //移动文件到指定目录
            $add=Db::query("insert into `product` (`name`,`logo`,`description`,`price`,`status`)values('$name','$rename','$description','$price','$rename','$status')");
            if (move_uploaded_file($_FILES['myfile']['tmp_name'], $upload_path.$rename)) {
                $arr=['code'=>'0','status'=>'ok','data'=>'添加成功'];
                echo json_encode($arr);
            } else {
                $arr=['code'=>'1','status'=>'error','data'=>'添加失败'];
                echo json_encode($arr);
            }
        }
	}
	

}