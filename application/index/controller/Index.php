<?php
namespace app\index\controller;

use think\facade\Session;

class Index
{
    public function index()
    {
        $a = Session::get('apple');
        var_dump($a);
    }

    public function hello($name = 'ThinkPHP5')
    {
        return 'hello,' . $name;
    }
}
