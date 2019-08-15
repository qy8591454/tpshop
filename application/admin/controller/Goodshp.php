<?php
namespace app\admin\controller;
use Request;
use Db;
class Goodshp extends Common
{
    public function list()
    {
        $id = Request::get('id');
        $cate_id = Request::get('cate_id');
        $this->assign('c_id',$cate_id);
        $this->assign('g_id',$id);
        return $this->fetch();
    }

    public function add()
    {
        $g_id = Request::get('g_id');
        $c_id = Request::get('c_id');
        $this->assign('c_id',$c_id);
        $this->assign('g_id',$g_id);
        return $this->fetch();
    }

    public function addAction()
    {
        $data=Request::post();
        $g_id=$data['g_id'];
        $new_arr=$data['new_arr'];
        $price=$data['price'];
        $number=$data['number'];
        $attr_id=implode("-", $new_arr);
        $arr=Db::query("select * from goodshp where goods_attr_id='$attr_id'");
        if (empty($arr)) {
            Db::query("insert into goodshp(goods_id,goods_attr_id,price,number) values ($g_id,'$attr_id',$price,$number)");
            $json=['code'=>'1','status'=>'ok','data'=>'添加成功!'];
        }else{
            $json=['code'=>'0','status'=>'error','data'=>'货品名字不能重复！'];
        }
        return json($json);
    }

    public function show()
    {
        $goods_id=Request::get('goods_id');
        $goodshp=Db::query("select * from goodshp where goods_id='$goods_id'");
        for ($i=0; $i <count($goodshp) ; $i++) { 
            $new_arr=[];
            $attr_id=$goodshp[$i]['goods_attr_id'];
            $all_attr_id=explode("-", $attr_id);
            for ($j=0; $j < count($all_attr_id); $j++) { 
                $details_id=$all_attr_id[$j];
                $details=Db::query("select * from attrdetails where id='$details_id'");
                $new_arr[]=$details[0]['name'];
            }
            $str=implode("-", $new_arr);
            $goodshp[$i]['attr_name']=$str;
            // echo "$str";
        }
        $json=['code'=>'0','status'=>'ok','data'=>$goodshp];
        return $json;
    }
    public function show1(){
        $g_id=1;
        // echo "$g_id";
        // die;
        // var_dump($data);
        $arr=Db::query("select id,name from attrcategory");
        // echo("select attr_cate from goods where g_id='$g_id'");
        // die;
        $arr1=Db::query("select attr_cate from goods where id='$g_id'");

        $json=['code'=>'0','status'=>'ok','data'=>$arr];
        if (!empty($arr1)) {
            $json['default']=$arr1;
            // var_dump($json);            
        }
        echo json_encode($json);
        die;
    }
    public function show2(){
        $data=Request::post();
        $g_id=$data['g_id'];
        // $attrcate_id=$data['attrcate_id'];
        $arr=Db::query("select * from goods_attr where goods_id='$g_id'");
        // var_dump($arr);
        // die;
        $newarr=[];
        foreach ($arr as $key => $value) {
            $attr_id=$value['attr_id'];
            $attr_details_id=$value['attr_details_id'];
            $arr1=DB::query("select name from attribute where id='$attr_id'");
            $arr2=DB::query("select * from attrdetails where id='$attr_details_id'");
            // var_dump($arr2);
            $newarr[$arr1[0]['name']][]=$arr2;//三维数组
        }
        // var_dump($newarr);
        // die;
        $json=['code'=>'0','status'=>'ok','data'=>$newarr];
        echo json_encode($json);
        die;
    }
public function attrShow(){
        $data=Request::post();
        $goods_id=$data['goods_id'];
        $arr=DB::query("select attribute.name as aname,attrdetails.id as atid,attrdetails.name as atname from goods_attr inner join attribute on goods_attr.attr_id=attribute.id inner join attrdetails on goods_attr.attr_details_id=attrdetails.id where goods_attr.goods_id='$goods_id'");
        $newarr=[];
        foreach ($arr as $key => $value){
            $newarr[$value['aname']][]=$value;
        }
        $arr=['code'=>'0','status'=>'ok','data'=>$newarr];
        return json($arr);
    }
public function shop_goods_add(){
        $data=Request::post();
        $goods_id=$data['goods_id'];
        $price=$data['price'];
        $number=$data['number'];
        $attr_id=$data['attr_id'];
        $arr=explode(',',$attr_id);
        array_shift($arr);
        $a=implode(',',$arr);
        $add = Db::query("insert into `goodshp` (`goods_id`,`goods_attr_id`,`price`,`number`)values('$goods_id','$a','$price','$number')");
        $res=['code'=>'0','status'=>'ok','data'=>'添加成功'];
        echo json_encode($res);
    }

}