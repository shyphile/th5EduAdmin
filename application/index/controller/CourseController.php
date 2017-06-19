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
		$courses=Course::paginate(4);
		$this->assign('courses',$courses);
		return $this->fetch();

	}

	public function add()
	{
		$time=time()-strtotime("2017-01-01");
		$courseinfo='c'.$time;

		// $klasses=Klass::all();
		// $this->assign('klasses',$klasses);
		
		$Course=new Course;
		$this->assign('Course',$Course);

		$this->assign('courseinfo',$courseinfo);
		return $this->fetch();
	}

	public function save()
	{
		$name=Request::instance()->post('name');
		if(is_null($name)){
			return $this->error('什么课程名称');
		}
		$Course=new Course;
		$Course->name=$name;
		if(!$Course->validate()->save($Course->getData())){
			return $this->error('保存失败'.$Course->getError());
		}
		//return $this->success('保存成功',url('index'));

		$Arrklass_id=Request::instance()->post('klass_id/a');
		if(is_null($Arrklass_id)){
			return $this->error('没选中班级');
		}

		if(!$Course->Klasses()->saveAll($Arrklass_id)){
			return $this->error('课程-班级信息保存错误：' . $Course->Klasses()->getError());
		}
		// $datas=array();
		// foreach ($Arrklass_id as $value) {
		// 	$data=[];
		// 	$data['klass_id']=$value;
		// 	$data['course_id']=$Course->id;
		// 	array_push($datas, $data);
		// }
		// if(!empty($datas)){
		// 	$klasscourse=new KlassCourse;
		// 	if(!$klasscourse->validate()->saveAll($datas)){
		// 		return $this->error('课程-班级信息保存错误：' . $KlassCourse->getError());
		// 	}
		// 	unset($klasscourse);
		// }
		unset($Course);
		return $this->success('操作成功', url('index'));
	}

	public function edit(){
		$id=Request::instance()->param('id/d');
		$Course=Course::get($id);
		$this->assign('Course',$Course);

		return $this->fetch();
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
		var_dump($Course->KlassCourses()->where($map));
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