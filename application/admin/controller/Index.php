<?php
namespace app\admin\controller;
use \think\Image;
class Index extends Common
{
    public function index()
    {
        return $this->fetch();
    }
    public function test()
    {
    	$images=new Image();
    	$image = $images->open('./image.png');
        //将图片裁剪为300x300并保存为crop.png
    	$image->crop(300, 300,100,30)->save('./crop.png');
    }
    
}