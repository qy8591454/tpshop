<?php
namespace app\admin\controller;
use Request;
use Db;
class Goodsimg extends Common
{
	public function addAction()
    {
      $data=Request::post();
      // var_dump($data);die;
      unset($data['__token__']);
      $userId=Db::name('goods')->insertGetId($data);
      $json=['code'=>'0','status'=>'ok','data'=>'添加成功'];
      return json($json);
      
    }

	public function goodsImgshow()
	{
		$goods_id=Request::get('id');
		$this->assign('goods_id',$goods_id);
		return $this->fetch();
	}
	
	public function goodsImg()
	{
		$goods_id=Request::post('goods_id');
		$arr=Db::query("select * from goods_img where goods_id='$goods_id'");
		$json=['code'=>'0','status'=>'ok','data'=>$arr];
	    return json($json);
	}
	public function goodsImgadd()
	{		
		$goods_id=Request::post('goods_id');
		// var_dump($goods_id);
		// die;
		$files = request()->file('file');
		foreach($files as $file){
			$info = $file->validate(['size'=>1024*1024,'ext'=>'jpg,png,gif'])->move( './uloads/goodsimg');
			
			if ($info) {
				$name=$info->getFilename();
				$data=date("Ymd");
				//$path=str_replace("\\","/",$info->getSaveName());
				$path="$data/$name";
				
				$image = \think\Image::open("./uloads/goodsimg/$path");
                //按照原图的比例生成一个最大为150*150的缩略图并保存为thumb.png
                $img_big="$data/big_$name";
			    $image->thumb(150, 150)->save('./uloads/goodsimg/'.$img_big);
			    $img_middle="$data/middle_$name";
			    $image->thumb(100, 100)->save('./uloads/goodsimg/'.$img_middle);
			    $img_small="$data/small_$name";
			    $image->thumb(50, 50)->save('./uloads/goodsimg/'.$img_small);

				Db::query("insert into goods_img (`img_origin`,`goods_id`,`img_big`,`img_middle`,`img_small`)value('$path','$goods_id','$img_big','$img_middle','$img_small')");
				$json=['code'=>'0','status'=>'ok','data'=>'添加成功'];
				return json($json);
			}else{
				$json=['code'=>'1','status'=>'error','data'=>$file->getError()];
				return json($json);
			}
		 }		 
	}
	
}