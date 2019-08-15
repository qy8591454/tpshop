<?php
namespace app\admin\controller;
use Request;
use Db;
class Goodsattr extends Common
{
    public function list()
    {
        $id = Request::get('id');
        $this->assign('goods_id',$id);
        return $this->fetch();
    }
    public function show()
    {
        $arr=Db::query("select id,name from attrcategory ");
        $goods_id=Request::get('goods_id');
        $goods_arr=Db::query("select attr_cate from goods where id=$goods_id");
        $json=['code'=>'0','status'=>'ok','data'=>$arr];
        if (!empty($goods_arr)) {
            $json['default']=$goods_arr;
        }
        return json($json);
    }
    public function add()
    {
        $id=Request::post('arr');
        $attr_cate=Request::post('attr_cate');
        $goods_id=Request::post('goods_id');
        
        Db::startTrans();
        try {
            $arr1=Db::query("update goods set attr_cate=$attr_cate where id='$goods_id'");
                Db::query("delete from goods_attr where goods_id in ($goods_id)");
            // 提交事务
            Db::commit();
        } catch (\Exception $e) {
            // 回滚事务
            Db::rollback();
        }



        if ($id!=NULL) {
            foreach ($id as $key => $value) {
                //var_dump($key);
                var_dump($value);
                $arr=explode('-', $value);
                $arr1=$arr[0];
                $arr2=$arr[1];
                Db::query("insert into goods_attr(goods_id,attr_id,attr_details_id) value('$goods_id','$arr1','$arr2')");
            }
        }
    }
    public function getattr()
    {
        $id=Request::get('id');
        $arr=Db::query("select a.id,a.name,ad.id as ad_id,ad.name as ad_name from attribute as a join attrdetails as ad on a.id=ad.attr_id where a.attrcate_id='$id'");
        $new=[];
        foreach ($arr as $key => $value) {
            $new[$value['name']][]=$value;
        }
        //var_dump($new);
        $json=['code'=>'0','status'=>'ok','data'=>$new];

        $goods_id=Request::get('goods_id');
        $arr1=Db::query("select attr_details_id from goods_attr where goods_id=$goods_id");

        $json['default']=$arr1;
        

        return json($json);
    }


}