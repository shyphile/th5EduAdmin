<?php
namespace app\common\validate;
use think\Validate;
/**
* 
*/
class Teacher extends Validate
{
	protected $rule=[
	"name"=>"require|unique:teacher|length:1,40",
	"sex"=>"in:0,1",
	'email'=>'email',
	];
}