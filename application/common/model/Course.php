<?php
namespace app\common\model;
use think\Model;
use app\common\model\KlassCourse;
/**
* 
*/
class Course extends Model
{
	public function Klasses(){
		return $this->belongsToMany('Klass',config('database.prefix').'klass_course');
	}

	public function getIsChecked(Klass &$Klass){
		$map=[];
		$map['course_id']=(int)$this->id;
		$map['klass_id']=(int)$Klass->id;
		if(is_null(KlassCourse::get($map))){
			return false;
		}
		return true;
	}

	public function KlassCourses(){
		return $this->hasMany('KlassCourse');

	}

	public static function query($name,$pageSize){
		$Course=new Course;
		if(!empty($name)){
			$Course->where('name','like','%'.$name.'%');
		}
		$Course->order('id desc');
		$courses=$Course->paginate($pageSize,false,[
			'query'=>[
			'name'=>$name,
			],
			]);
		return $courses;
	}

	public static function getOneCourse($id=null){
		if(is_null($id) || $id===0){
			$time=time()-strtotime("2017-01-01");
			$defaultData='C'.$time;

		//重构 使只用一个html兼容add和edit
			$Course=new Course;
			$Course->id=0;
			$Course['name']=$defaultData;
		}
		else{
			$Course=self::get($id);
			if(is_null($Course)){
				return $this->getError('未找到对应id的记录');
			}
		}
		return $Course;
	}

	public static function deleteCoursebyId($id){
		$Course=self::get($id);
		if(!is_null($Course)){
			if(empty($Course->checkBind)){
				if($Course->delete()){
					return true;
				}
			}
			else{
				return 'hasmore';
			}
		}
		return false;
	}

	public function checkBind(){
		return $this->hasMany('KlassCourse');
	}

	public static function saveCourse($Course=null,$postData)
	{
		if(is_null($Course))
		{
			$Course=new Course();
		}
		$Course['name']=$postData['name'];
		if(!$Course->validate(true)->save($Course->getData())){
			return false;
		}
		if(array_key_exists('klass_id',$postData)){
			if(!$Course->Klasses()->saveAll($postData['klass_id'])){
				return false;
			}
		}
		unset($Course);
		return true;
	}

	public function getUpdateTimeAttr($value){
		return date('Y年m月d日',$value);
	}
}