<?php
namespace app\admin\controller;
use Request;
use Db;
class Attrcategory extends Common
{
    public function list()
    {
        return $this->fetch();
    }


    public function show()
    {   
        $arr=Db::query("select id,name from attrcategory ");
        $json=['code'=>'0','status'=>'ok','data'=>$arr];
        echo json_encode($arr);
        die;
    }


    public function addAction(){
        $data=Request::post();
        $name=$data['name'];
        $arr=Db::query("select * from attrcategory where name='$name'");
        if (empty($arr)) {
            $arr=Db::query("insert into attrcategory (`name`) values ('$name')");
            $arr=['code'=>'0','status'=>'ok','data'=>'添加成功'];
            echo $json=json_encode($arr);
            die;
        }else{
            $arr=['code'=>'1','status'=>'error','data'=>'已有该分类属性'];
            echo json_encode($arr);
            die;
        }
    }
    public function delete(){
        $data=Request::post();
        $validate = new \app\admin\validate\Delete;
        if (!$validate->check($data)) {
            $arr=['code'=>'302','status'=>'error','data'=>$validate->getError()];
            echo $json=json_encode($arr);
            die;
        }
        $id=$data['id'];
        $arr=Db::query("delete from attrcategory where id=$id");
        $arr=['code'=>'0','status'=>'ok','data'=>'删除成功'];
        echo $json=json_encode($arr);
        die;


    }
    public function updateaction(){
        $data=Request::post();
        $id=$data['id'];
        $name=$data['name'];
        $arr=Db::query("select * from attrcategory where id=$id");
        if (empty($arr)) {
            $arr=Db::quer("update set attrcategory `name`='$name' where id='$id'");
            $arr=['code'=>'0','status'=>'ok','data'=>'修改成功'];
            echo $json=json_encode($arr);
            die;
        }else{
            if ($arr[0]['id']==$id) {
                $arr=Db::query("update attrcategory  set `name`='$name' where id='$id'");
                $arr=['code'=>'0','status'=>'ok','data'=>'修改成功'];
                echo $json=json_encode($arr);
                die;
            }else{
                $arr=['code'=>'1','status'=>'error','data'=>'修改失败'];
                echo $json=json_encode($arr);
                die;
            }
        }
    }
}