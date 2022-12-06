<?php

namespace app\admin\controller;

use app\common\controller\Backend;

/**
 * 
 *
 * @icon fa fa-circle-o
 */
class Tagsmonthsum extends Backend
{

    /**
     * Tagsmonthsum模型对象
     * @var \app\admin\model\Tagsmonthsum
     */
    protected $model = null;
    protected $noNeedRight=['*'];
    
    public function _initialize()
    {
        parent::_initialize();
        $this->model = new \app\admin\model\Tagsmonthsum;

    }



    /**
     * 默认生成的控制器所继承的父类中有index/add/edit/del/multi五个基础方法、destroy/restore/recyclebin三个回收站方法
     * 因此在当前控制器中可不用编写增删改查的代码,除非需要自己控制这部分逻辑
     * 需要将application/admin/library/traits/Backend.php中对应的方法复制到当前控制器,然后进行修改
     */

 /**
     * 查看
     */
    public function index()
    {
 
        $ctdate=$this->request->get('ctdate');
        $unit=$this->request->get('unit');


        if ($this->request->isAjax()) {
 
            list($where, $sort, $order, $offset, $limit) = $this->buildparams(null);
            // print_r("sort=");
            // print_r($sort);
            // print_r("  order=");
            // print_r($order);
            // print_r("  offset=");
            // print_r($offset);
            // print_r("  limit=");
            // print_r($limit);


            if($unit=='plant'){
                $where=" ctdate='".$ctdate."'  ";
            }else{
                $where=" ctdate='".$ctdate."' and UAPno='".$unit."' ";
            }

            // $sql=  " select PLC_ID, Workcenter, PLC_Name,Parts, Num,CT,SapCT,DiffCT,DiffPer,abs(DiffPer) from fa_tagsyearsum where ".$where."  order by ABS(DiffPer) limit ".$limit." offset ".$offset;
            // print_r($sql);
            // $q = Db::query( $sql );
            $sort='DiffPerPos';
            $order='DESC';
            $list = $this->model
                ->field('id, PLC_ID,  PLC_Name,UAPno,CTDATE,PARTS, Num,CT,SapCT,DiffCT,DiffPer,abs(DiffPer) as DiffPerPos ' )
                ->where($where)
                ->order($sort,$order)
                ->limit($offset, $limit)
                ->paginate($limit); 
            
            $result = array("total" => $list->total(), "rows" => $list->items() );

            return json($result);
        }
              
        return $this->view->fetch();
    }
}
