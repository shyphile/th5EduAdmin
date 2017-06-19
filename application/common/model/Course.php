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
		return $this->belongsToMany('klass',config('database.prefix').'klass_course');
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
}