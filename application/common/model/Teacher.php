<?php
namespace app\common\model;
use think\Model;
/**
* 
*/
class Teacher extends Model
{
	public function getSexAttr($value){
		$sexType=[
		0=>'man',
		1=>'women',
		];
		if(isset($sexType[$value])){
			return $sexType[$value];
		}
		return '男';
	}

	public static function login($username,$password){

		$map=array('username'=>$username);
		$Teacher=self::get($map);

		if(!is_null($Teacher) && $Teacher->checkPassword($password)){
			session('teacherId',$Teacher->id);
			return true;
		}
		return false;
	}

	private function checkPassword($password){
		if($this->getData('password')===self::encryptPassword($password)){
			return true;
		}
		return false;
	}
	private static function encryptPassword($password){
		return $password;
		return sha1(md5($password).'xh');
	}

	public static function logOut(){
		session('teacherId',null);
		return true;
	}

	public static function isLogin(){
		$teacherId=session('teacherId');
		if(isset($teacherId)){
			return true;
		}
		return false;
	}
}