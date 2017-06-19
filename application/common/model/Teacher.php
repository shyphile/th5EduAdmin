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
		0=>'公',
		1=>'母',
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

	public function getCreateTimeAttr($value){
		return date('Y年m月d日',$value);
	}

	public function getUpdateTimeAttr($value){
		return date('Y年m月d日',$value);
	}

	public static function deleteTeacherbyId($id){
		$Teacher=self::get($id);
		if(!is_null($Teacher)){
			if($Teacher->delete()){
				return true;
			}
		}
		return false;
	}

	public static function getOneTeacher($id=null){
		if(is_null($id) || $id===0){
			$time=time()-strtotime("2017-01-01");
			$defaultData='T'.$time;

		//重构 使只用一个html兼容add和edit
			$Teacher=new Teacher;
			$Teacher->id=0;
			$Teacher['name']=$defaultData;
			$Teacher->sex= 1;
			$Teacher->username= $defaultData;
			$Teacher->password= $defaultData;
			$Teacher->email= $defaultData.'@qq.com';
		}
		else{
			$Teacher=self::get($id);
			if(is_null($Teacher)){
				return $this->getError('未找到对应id的记录');
			}
		}
		return $Teacher;
	}

	public static function saveTeacher($Teacher=null,$postData)
	{
		if(is_null($Teacher))
		{
			$Teacher=new Teacher();
			$Teacher->username=$postData['username'];
		}
		$Teacher['name']=$postData['name'];
		$Teacher->sex=$postData['sex'];
		$Teacher->password=$postData['password'];
		$Teacher->email=$postData['email'];
		if($Teacher->validate(true)->save($Teacher->getData())){
			return true;
		}
		return false;
	}

	public static function query($name,$pageSize){
		$Teacher=new Teacher;
		if(!empty($name)){
			$Teacher->where('name','like','%'.$name.'%');
		}
		$Teacher->order('id desc');
		$teachers=$Teacher->paginate($pageSize,false,[
			'query'=>[
			'name'=>$name,
			],
			]);
		return $teachers;
	}
}


