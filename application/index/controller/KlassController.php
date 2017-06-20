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
		$time=time()-strtotime("2017-01-01");
		$klassinfo='k'.$time;

		$Klass=new Klass;
		$Klass->id=0;
		$Klass->name=$klassinfo;
		$Klass->teacher_id=0;
		$this->assign('Klass',$Klass);

		$teachers=Teacher::all();
		$this->assign('teachers',$teachers);

		return $this->fetch('addORedit');
	}

	public function save(){
		$postData=Request::instance();
		$Klass=new Klass;
		$Klass->name=$postData->post('name');
		$Klass->teacher_id=$postData->post('teacher_id/d');
		if(!$Klass->validate(true)->save($Klass->getData())){
			return $this->error('添加失败'.$Klass->getError());
		}
		return $this->success('添加成功',url('index'));
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
		$id=Request::instance()->param('id/d');
		$teachers=Teacher::all();
		$this->assign('teachers',$teachers);

		if(false===$Klass=Klass::get($id)){
			return $this->error('未找到记录');
		}
		$this->assign('Klass',$Klass);
		return $this->fetch('addORedit');
	}

	public function update(){
		$id=Request::instance()->post('id/d');
		if(false===$Klass=Klass::get($id)){
			return $this->error('记录未找到');
		}
		$Klass->name=Request::instance()->post('name');
		$Klass->teacher_id=Request::instance()->post('teacher_id');
		if(!$Klass->validate()->save($Klass->getData())){
			return $this->error('更新失败'.$Klass->getError());
		}
		return $this->success('更新成功',url('index'));
	}

	private function isIdExists(){
		$id=Request::instance()->param('id/d');
		if(is_null($id) || $id===0){
			return $this->error('id号为'.$id.'的编号不存在');
		}
		return $id;
	}

}