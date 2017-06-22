<?php
namespace app\index\controller;
use app\common\model\Course;
use app\common\model\Klass;
use app\common\model\KlassCourse;
use think\Request;
/**
* 
*/
class CourseController extends IndexController
{
	public function index()
	{
		$pageSize=6;
		$name=Request::instance()->get('name');
		$courses=Course::query($name,$pageSize);
		
		$this->assign('courses',$courses);
		return $this->fetch();
	}

	public function add()
	{
		$Course=Course::getOneCourse();
		$this->assign('Course',$Course);
		return $this->fetch('addORedit');
	}

	public function delete(){
		$id=$this->isIdExists();
		$result=Course::deleteCoursebyId($id);
		if($result==='hasmore'){
			return $this->error('删除失败,该课程绑定了班级');
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

	public function save()
	{
		$result=$this->saveCourse();
		if($result){
			return $this->success('添加成功',url('index'));
		}
		return $this->error('添加失败');
	}

	private function saveCourse($Course=null){
		$postData=Request::instance()->post();
		return Course::saveCourse($Course,$postData);
	}

	public function edit(){
		$id=$this->isIdExists();
		$Course=Course::get($id);
		$this->assign('Course',$Course);

		return $this->fetch('addORedit');
	}

	public function update(){
		$id=$this->isIdExists();
		$Course=Course::getOneCourse($id);
		$map=['course_id'=>$id];
		Course::deleteCoursebyArr($Course,$map);
		$result=$this->saveCourse($Course);
		if($result){
			return $this->success('修改成功',url('index'));
		}
		return $this->error('修改失败'.$Course->getError());
	}
}