<?php

namespace app\admin\validate;

use think\Validate;

class Permission extends Validate
{
    /**
     * 定义验证规则
     * 格式：'字段名'	=>	['规则1','规则2'...]
     *
     * @var array
     */	
	protected $rule = [
        'name'  => 'require|max:50|min:1|token',
        'description'   => 'require|max:200|min:1',
        'path' => 'require',
        'category_id'   => 'require',    
    ];
    
    /**
     * 定义错误信息
     * 格式：'字段名.规则名'	=>	'错误信息'
     *
     * @var array
     */	
    protected $message = [
        'name.require' => '名称必须填写',
        'name.max'     => '名称最多不能超过50个字符',
        'name.min'     => '名称最少不能低于1个字符',
        'name.token'     => '令牌无效',
        'description.require'     => '描述必须填写',
        'description.max'     => '名称最多不能超过200个字符',
        'description.min'     => '名称最少不能低于1个字符',
    ];
}
