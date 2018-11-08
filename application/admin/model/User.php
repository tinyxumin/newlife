<?php

namespace app\admin\model;

use think\Model;

class User extends Model
{
    // 设置当前模型对应的完整数据表名称
    protected $table = 'xm_user';

    //自定义初始化
    protected function initialize()
    {
        //需要调用`Model`的`initialize`方法
        parent::initialize();
        //TODO:自定义的初始化
    }

    /**
     * 查询用户信息
     * @param $where
     * @return array
     */
    public function userLogin($where)
    {
        $data = User::where($where)->field('userId,address,userName,phone,age,birthday,addTime,email,sex,image')->find();
        return $data;
    }


    /**
     * 查询用户信息
     * @param $where
     * @return array
     */
    public function userInfo()
    {
        $data = User::field('userId,address,userName,phone,age,birthday,addTime,email,sex')->select();
        return $data;
    }

    /**
     * 删除用户信息
     * @param $where
     * @return array
     */
    public function userDel($id)
    {
        $data = User::where('userId','=',$id)->delete();
        return $data;
    }

}
