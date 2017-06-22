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
		$pageSize=8;
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

		 // 获取当前课程
		$id = Request::instance()->post('id/d');
		if (is_null($Course = Course::get($id))) {
			return $this->error('不存在ID为' . $id . '的记录');
		}

        // 更新课程名
		$Course->name = Request::instance()->post('name');
		if (is_null($Course->validate(true)->save())) {
			return $this->error('课程信息更新发生错误：' . $Course->getError());
		}

		$map=['course_id'=>$id];
		//var_dump($Course->KlassCourses()->where($map));
		if (false === $Course->KlassCourses()->where($map)->delete()) {
			//return $this->error('删除班级课程关联信息发生错误' . $Course->KlassCourses()->getError());
		}

		$klassIds = Request::instance()->post('klass_id/a');
		if (!is_null($klassIds)) {
			if (!$Course->Klasses()->saveAll($klassIds)) {
				return $this->error('课程-班级信息保存错误：' . $Course->Klasses()->getError());
			}
		}
		return $this->success('更新成功', url('index'));
	}

}