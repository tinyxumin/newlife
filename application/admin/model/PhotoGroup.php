<?php

namespace app\admin\model;

use think\Model;

class PhotoGroup extends Model
{
    // 设置当前模型对应的完整数据表名称
    protected $table = 'xm_photo_group';

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
    public function group()
    {
        $data = PhotoGroup::field('id,name')->select();
        return $data;
    }

    /**
     * 删除用户信息
     * @param $where
     * @return array
     */
    public function userDel($id)
    {
        $data = PhotoGroup::destroy($id);
        return $data;
    }

}
