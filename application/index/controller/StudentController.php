<?php
namespace app\index\controller;
use think\Request;
use app\common\model\Student;
/**
* 
*/
class StudentController extends IndexController
{
	public function index(){
		//查询方法
		$pageSize=8;
		$name=Request::instance()->get('name');
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

		//展示辅导员信息
		$this->assign('students',$students);
		return $this->fetch();

	}

	public function add(){

	}

	public function save(){

	}

	public function edit(){
		$id=Request::instance()->param('id/d');
		if(is_null($Student=Student::get($id))){
			return $this->error('未找到id为'.$id.'的记录');
		}
		$this->assign('Student',$Student);
		return $this->fetch();
	}

	public function update(){
		$postData=Request::instance();
		$id=$postData->post('id/d');
		$Student=Student::get($id);
	}

	public function delete(){

	}
}