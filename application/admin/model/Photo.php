<?php

namespace app\admin\model;

use think\Model;

class Photo extends Model
{
    // 设置当前模型对应的完整数据表名称
    protected $table = 'xm_photos';

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
    public function photo($where = array())
    {
        $data = Photo::where($where)->field('image,remark,author,gid,type,size,id,addTime')->paginate(20);
        return $data;
    }

    /**
     * 删除用户信息
     * @param $where
     * @return array
     */
    public function photoDel($id)
    {
        $data = Photo::destroy($id);
        return $data;
    }

}
