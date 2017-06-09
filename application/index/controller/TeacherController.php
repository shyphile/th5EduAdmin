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
		$pageSize=15;
		$name=Request::instance()->get('name');
		$Teacher=new Teacher;
		if(!empty($name)){
			$Teacher->where('name','like','%'.$name.'%');
		}
		$teachers=$Teacher->paginate($pageSize,false,[
			'query'=>[
			'name'=>$name,
			],
			]);

		//展示辅导员信息
		$this->assign('teachers',$teachers);
		return $this->fetch();
	}

	public function delete(){
		$id=Request::instance()->param('id/d');
		if(is_null($id)){
			return $this->error($id.'号记录未找到!');
		}
		$Teacher=Teacher::get($id);
		if(!$Teacher->delete()){
			return $this->error('删除失败'.$Teacher->getError());
		}
		return $this->success('删除成功',url('index'));
	}

	public function add(){
		$time=time()-strtotime("2017-01-01");
		$userinfo='t'.$time;
		$this->assign('userinfo',$userinfo);
		return $this->fetch();
	}

	public function save(){
		$postData=Request::instance();
		
		$Teacher=new Teacher();
		$Teacher->name=$postData->post('name');
		$Teacher->sex=$postData->post('sex/d');
		$Teacher->username=$postData->post('username');
		$Teacher->password=$postData->post('password');
		$Teacher->email=$postData->post('email');
		$result=$Teacher-> validate(true)->save($Teacher->getData());
		if($result){
			return $this->success('添加成功,id为'.$Teacher->id,url('index'));
		}
		return $this->error('添加失败'.$Teacher->getError(),url('index'));
	}

	public function edit(){
		$id=Request::instance()->param('id/d');
		if(is_null($id)){
			return $this->error('id号为'.$id.'的记录不存在');
		}
		$Teacher=Teacher::get($id);
		if(is_null($Teacher)){
			return $this->error('未找到对应数据');
		}
		$this->assign('Teacher',$Teacher);
		return $this->fetch();
	}

	public function update(){
		$postData=Request::instance();
		$id=$postData->post('id/d');
		$Teacher=Teacher::get($id);
		$Teacher->name=$postData->post('name');
		$Teacher->sex=$postData->post('sex/d');
		$Teacher->username=$postData->post('username');
		$Teacher->password=$postData->post('password');
		$Teacher->email=$postData->post('email');
		$result=$Teacher->validate(true)->save($Teacher->getData());
		if($result){
			return $this->success('修改成功',url('index'));
		}
	}
}