<?php
namespace app\common\model;
use think\Model;

/**
* 
*/
class Student extends Model
{
	public function getSexAttr($value){
		$sexType=array(
			"0"=>"男",
			"1"=>"女",
			);
		if(isset($sexType[$value])){
			return $sexType[$value];
		}
		return $sexType[0];
	}

	public function getCreateTimeAttr($value){
		return date('Y年m月d日',$value);
	}

	// protected $type=[
	// 	"create_time"=>'datetime',
	// ];
	// protected $dateFormat='Y年m月d日';
	
	public function Klass(){
		return $this->belongsTo('Klass');
	}

	public static function query($name,$pageSize){
		$Student=new Student;
		if(!empty($name)){
			$Student->where('name','like','%'.$name.'%');
		}
		$Student->order('id desc');
		$students=$Student->paginate($pageSize,false,[
			'query'=>[
			'name'=>$name,
			],
			]);
		return $students;
	}

	public static function deleteStudentbyId($id){
		$Student=self::get($id);
		if(!is_null($Student)){
			if($Student->delete()){
				return true;
			}
		}
		return false;
	}

	public static function getOneStudent($id=null){
		if(is_null($id) || $id===0){
			$time=time()-strtotime("2017-01-01");
			$defaultData='S'.$time;

		//重构 使只用一个html兼容add和edit
			$Student=new Student;
			$Student->id=0;
			$Student['name']=$defaultData;
			$Student->num= $time;
			$Student->sex= 0;
			$Student->klass_id= 1;
			$Student->email= $defaultData.'@qq.com';
		}
		else{
			$Student=self::get($id);
			if(is_null($Student)){
				return $this->getError('未找到对应id的记录');
			}
		}
		return $Student;
	}

	public static function saveStudent($Student=null,$postData)
	{
		if(is_null($Student))
		{
			$Student=new Student();
		}
		$Student['name']=$postData['name'];
		$Student->sex=$postData['sex'];
		$Student->num=$postData['num'];
		$Student->klass_id=$postData['klass_id'];
		$Student->email=$postData['email'];
		if($Student->validate(true)->save($Student->getData())){
			return true;
		}
		return false;
	}
}