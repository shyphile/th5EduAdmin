<?php
namespace app\index\controller;
use app\common\model\Klass;
use app\common\model\Teacher;
use think\Request;
/**
* 
*/
class KlassController extends IndexController
{
	public function index(){
		$pageSize=8;
		$name=Request::instance()->get('name');
		$klasses=Klass::query($name,$pageSize);
		
		$this->assign('klasses',$klasses);
		return $this->fetch();
	}

	public function add(){
		$Klass=Klass::getOneKlass();
		$this->assign('Klass',$Klass);
		return $this->fetch('addORedit');
	}

	

	public function delete(){
		$id=$this->isIdExists();
		$result=Klass::deleteKlassbyId($id);
		if($result==='hasmore'){
			return $this->error('删除失败,该班级已经绑定学生或课程表');
		}
		if(!$result){
			return $this->error('删除失败');
		}
		return $this->success('删除成功',url('index'));
	}

	public function edit(){
		$id=$this->isIdExists();
		$Klass=Klass::getOneKlass($id);
		$this->assign('Klass',$Klass);
		return $this->fetch('addORedit');
	}

	public function save(){
		$result=$this->saveKlass();
		if($result){
			return $this->success('添加成功',url('index'));
		}
		return $this->error('添加失败');
	}

	public function update(){
		$id=$this->isIdExists();
		$Klass=Klass::getOneKlass($id);
		$result=$this->saveKlass($Klass);
		if($result){
			return $this->success('修改成功',url('index'));
		}
		return $this->error('修改失败'.$Klass->getError());
	}

	private function isIdExists(){
		$id=Request::instance()->param('id/d');
		if(is_null($id) || $id===0){
			return $this->error('id号为'.$id.'的编号不存在');
		}
		return $id;
	}

	private function saveKlass($Klass=null){
		$postData=Request::instance()->post();
		return Klass::saveKlass($Klass,$postData);
	}
}