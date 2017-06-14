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
	];
	
}