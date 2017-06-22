<?php
namespace app\common\validate;
use think\Validate;
/**
* 
*/
class Student extends Validate
{
	protected $rule=[
	"name"=>'require|unique:student',
	"num"=>'require|unique:student',
	"klass_id"=>"require",
	"sex"=>"in:0,1",
	'email'=>'email',
	];
	
}