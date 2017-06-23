<?php
namespace app\index\controller;
use think\Request;
use app\common\model\Teacher;
use think\Controller;
/**
* 
*/
class LoginController extends Controller
{
	public function index(){
		return $this->fetch();
	}
	public function login(){
		$postData=Request::instance()->post();

		if(Teacher::login($postData['username'],$postData['password'])){
			return $this->success('登入成功',url('Teacher/index'));
		}
		else{
			return $this->error('用户名或密码不对',url('index'));
		}
	}

	public function logOut(){
		if(Teacher::logOut()){
			return $this->success('登出成功',url('index'));
		}
		return $this->error('登出失败',url('index'));
	}

	public function getLoginUser(){
		return Teacher::getLoginUser();
	}
}