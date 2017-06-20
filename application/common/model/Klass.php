<?php
namespace app\common\model;
use think\Model;
/**
* 
*/
class Klass extends Model
{
	public function Teacher(){
		return $this->belongsTo('Teacher');
	}

	public function getCreateTimeAttr($value){
		return date('Y年m月d日',$value);
	}

	public function getUpdateTimeAttr($value){
		return date('Y年m月d日',$value);
	}


	public static function deleteKlassbyId($id){
		$Klass=self::get($id);
		if(!is_null($Klass)){
			if(empty($Klass->checkBind)){
				if($Klass->delete()){
					return true;
				}
			}
			else{
				return 'hasmore';
			}
		}
		return false;
	}

	public static function getOneKlass($id=null){
		if(is_null($id) || $id===0){
			$time=time()-strtotime("2017-01-01");
			$defaultData='T'.$time;

		//重构 使只用一个html兼容add和edit
			$Klass=new Klass;
			$Klass->id=0;
			$Klass['name']=$defaultData;
			$Klass->sex= 1;
			$Klass->username= $defaultData;
			$Klass->password= $defaultData;
			$Klass->email= $defaultData.'@qq.com';
		}
		else{
			$Klass=self::get($id);
			if(is_null($Klass)){
				return $this->getError('未找到对应id的记录');
			}
		}
		return $Klass;
	}

	public static function saveKlass($Klass=null,$postData)
	{
		if(is_null($Klass))
		{
			$Klass=new Klass();
			$Klass->username=$postData['username'];
		}
		$Klass['name']=$postData['name'];
		$Klass->sex=$postData['sex'];
		$Klass->password=$postData['password'];
		$Klass->email=$postData['email'];
		if($Klass->validate(true)->save($Klass->getData())){
			return true;
		}
		return false;
	}

	public static function query($name,$pageSize){
		$Klass=new Klass;
		if(!empty($name)){
			$Klass->where('name','like','%'.$name.'%');
		}
		$Klass->order('id desc');
		$Klasss=$Klass->paginate($pageSize,false,[
			'query'=>[
			'name'=>$name,
			],
			]);
		return $Klasss;
	}

	public function checkBind(){
		return $this->hasMany('Student');
	}

	
}