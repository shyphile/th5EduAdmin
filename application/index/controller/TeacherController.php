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
		$teachers=Teacher::paginate();
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
		$result=$Teacher->save();
		if($result){
			$this->success('添加成功,id为'.$Teacher->id,url('index'));
		}
	}
}