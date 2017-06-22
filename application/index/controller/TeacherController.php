<?php
namespace app\index\controller;
use think\Request;
use app\common\model\Teacher;
/**
* 
*/
class TeacherController extends IndexController
{
	public function index(){
		//查询方法
		$pageSize=6;
		$name=Request::instance()->get('name');
		$teachers=Teacher::query($name,$pageSize);

		//展示辅导员信息
		$this->assign('teachers',$teachers);
		return $this->fetch();
	}

	public function delete(){
		$id=$this->isIdExists();
		$result=Teacher::deleteTeacherbyId($id);
		if($result==='hasmore'){
			return $this->error('删除失败,该辅导员已经绑定班级');
		}
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

	public function add(){
		$Teacher=Teacher::getOneTeacher();
		$this->assign('Teacher',$Teacher);	
		return $this->fetch('addORedit');
	}

	public function edit(){
		$id=$this->isIdExists();
		$Teacher=Teacher::getOneTeacher($id);
		$this->assign('Teacher',$Teacher);
		return $this->fetch('addORedit');
	}

	public function save(){
		$result=$this->saveTeacher();
		if($result){
			return $this->success('添加成功',url('index'));
		}
		return $this->error('添加失败');
	}

	
	public function update(){
		$id=$this->isIdExists();
		$Teacher=Teacher::getOneTeacher($id);
		$result=$this->saveTeacher($Teacher);
		if($result){
			return $this->success('修改成功',url('index'));
		}
		return $this->error('修改失败'.$Teacher->getError());
	}

	private function saveTeacher($Teacher=null){
		$postData=Request::instance()->post();
		return Teacher::saveTeacher($Teacher,$postData);
	}
}