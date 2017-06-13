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
		$name=Request::instance()->get('name');
		$Klass=new Klass;
		if(!empty($name)){
			$Klass->where('name','like','%'.$name.'%');
		}
		$Klass->order('id desc');
		$pageSize=8;
		$klasses=$Klass->paginate($pageSize,false,[
			"query"=>[
			'name'=>$name,
			],
			]);
		$this->assign('klasses',$klasses);
		return $this->fetch();
	}

	public function add(){
		$time=time()-strtotime("2017-01-01");
		$klassinfo='k'.$time;

		$teachers=Teacher::all();
		$this->assign('teachers',$teachers);

		$this->assign('klassinfo',$klassinfo);
		return $this->fetch();
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
		$id=Request::instance()->param('id/d');
		if(is_null($id)||0===$id){
			return $this->error('id号:'.$id.'不存在');
		}
		$Klass=Klass::get($id);
		if(is_null($Klass)){
			return $this->error('找不到这个班级');
		}
		if(false===$Klass->delete()){
			return $this->error('删除失败'.$Klass->getError());
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
		return $this->fetch();
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

}