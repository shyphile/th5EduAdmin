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
	
	public function klass(){
		return $this->belongsTo('klass');
	}
}