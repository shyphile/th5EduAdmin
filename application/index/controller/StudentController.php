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
		$pageSize=6;
		$name=Request::instance()->get('name');
		$students=Student::query($name,$pageSize);

		$this->assign('students',$students);
		return $this->fetch();
	}

	public function add(){
		$Student=Student::getOneStudent();
		$this->assign('Student',$Student);	
		return $this->fetch('addORedit');
	}

	public function save(){
		$result=$this->saveStudent();
		if($result){
			return $this->success('添加成功',url('index'));
		}
		return $this->error('添加失败');
	}

	public function edit(){
		$id=$this->isIdExists();
		$Student=Student::getOneStudent($id);
		$this->assign('Student',$Student);
		return $this->fetch('addORedit');
	}

	public function update(){
		$id=$this->isIdExists();
		$Student=Student::getOneStudent($id);
		$result=$this->saveStudent($Student);
		if($result){
			return $this->success('修改成功',url('index'));
		}
		return $this->error('修改失败'.$Student->getError());
	}

	public function delete(){
		$id=$this->isIdExists();
		$result=Student::deleteStudentbyId($id);
		// if($result==='hasmore'){
		// 	return $this->error('删除失败,该辅导员已经绑定班级');
		// }
		if(!$result){
			return $this->error('删除失败');
		}
		return $this->success('删除成功',url('index'));
	}

	private function isIdExists(){
		$id=Request::instance()->param('id/d');
		if(is_null($id) || $id===0){
			return $this->error('id号为'.$id.'的编号不存在');
		}
		return $id;
	}

	private function saveStudent($Student=null){
		$postData=Request::instance()->post();
		return Student::saveStudent($Student,$postData);
	}
}