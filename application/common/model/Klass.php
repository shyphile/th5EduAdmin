<?php
namespace app\common\model;
use think\Model;
/**
* 
*/
class Klass extends Model
{
	public function Teacher(){
		//会根据$this对象中的teacher_id字段 再去Teacher模型中找对应的辅导员.
		return $this->belongsTo('Teacher');
	}

	public function getCreateTimeAttr($value){
		return date('Y年m月d日',$value);
	}

	public function getUpdateTimeAttr($value){
		return date('Y年m月d日',$value);
	}

   //errorMsg传地址的变量 可以返回对应的错误信息
	public static function deleteKlassbyId($id,&$errorMsg=null){
		$Klass=self::get($id);
		if(!is_null($Klass)){
			var_dump($Klass->checkBind);
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
			$defaultData='K'.$time;

		//重构 使只用一个html兼容add和edit
			$Klass=new Klass;
			$Klass->id=0;
			$Klass['name']=$defaultData;
			$Klass->teacher_id=1;
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
		}
		$Klass['name']=$postData['name'];
		$Klass->teacher_id=$postData['teacher_id'];

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
		return array_merge($this->hasMany('Student'),$this->hasMany('KlassCourse'));
	}
}