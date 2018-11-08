<?php

namespace app\admin\model;

use think\Model;

class Diary extends Model
{
    // 设置当前模型对应的完整数据表名称
    protected $table = 'xm_Diary';

    //自定义初始化
    protected function initialize()
    {
        //需要调用`Model`的`initialize`方法
        parent::initialize();
        //TODO:自定义的初始化
    }

    /**
     * 查询所有动态信息
     * @param $where
     * @return array
     */
    public function moments()
    {
        $data = Diary::where('status','eq',1)->field('userId,userName,diary,photo,image,type,addTime')->order('addTime desc')->select();
        return $data;
    }

    /**
     * 查询个人动态信息
     * @param $where
     * @return array
     */
    public function userMoment($where)
    {
        $data = Diary::where($where)->field('id,userId,userName,diary,photo,image,type,addTime,status')->order('addTime desc')->select();
        return $data;
    }

    /**
     * 删除用户动态信息
     * @param $where
     * @return array
     */
    public function comDel($id)
    {
        $data = Diary::destroy($id);
        return $data;
    }

}
