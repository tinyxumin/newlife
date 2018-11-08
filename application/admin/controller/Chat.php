<?php
    namespace app\admin\controller;

    use think\Controller;


    class Chat extends Controller
    {
        private $user;

        public function _initialize()
        {
            parent::_initialize();
            $sessionId = session('token');
            $cookieId  = session('token');
            if(empty($sessionId) || $sessionId != $cookieId){
                $this->redirect('admin/login/index');die;
            }
            $this->userInfo = $this->user->userLogin(['userId'=>cookie('userId')]);
        }

        public function index()
        {
            return view('chat/chat',['userInfo' => $this->userInfo]);
        }
    }