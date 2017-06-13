<?php
namespace app\common\model;
use think\Model;
/**
* 
*/
class Klass extends Model
{
	private $Teacher;
	public function getTeacher()
	{
		if(is_null($this->Teacher))
		{
			$teacherId=$this->teacher_id;
			if(is_null($this->Teacher=Teacher::get($teacherId))){
				throw new \Exception("编号是{$teacherId}的辅导员不存在", 1);	
			}		
		}
		return $this->Teacher;
	}

	public function Teacher(){
		$teacherId=$this->teacher_id;
		return Teacher::get($teacherId);
	}
	
}