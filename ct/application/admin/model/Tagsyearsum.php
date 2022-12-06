<?php

namespace app\admin\model;

use think\Model;


class Tagsyearsum extends Model
{

    

    

    // 表名
    protected $name = 'tagsyearsum';
    
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = false;

    // 定义时间戳字段名
    protected $createTime = false;
    protected $updateTime = false;
    protected $deleteTime = false;

    // 追加属性
    protected $append = [
        'DiffPer_Pos'
    ];
    
    public function getDiffPerPosAttr($value, $data)
    {
        $value = $value ? $value : (isset($data['DiffPer']) ? -$data['DiffPer'] : '');
      
        return  $value ;
    }
    







}
