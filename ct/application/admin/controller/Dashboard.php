<?php

namespace app\admin\controller;

use app\admin\model\Admin;
use app\admin\model\User;
use app\common\controller\Backend;
use app\common\model\Attachment;
use fast\Date;
use think\Db;

/**
 * 控制台
 *
 * @icon   fa fa-dashboard
 * @remark 用于展示当前系统中的统计数据、统计报表及重要实时数据
 */
class Dashboard extends Backend
{
    protected $noNeedRight=['*'];
    protected $model;

    /**
     * 查看
     */
    public function index()
    {
        try {
            \think\Db::execute("SET @@sql_mode='';");
        } catch (\Exception $e) {

        }
        $column = [];
        $starttime = Date::unixtime('day', -6);
        $endtime = Date::unixtime('day', 0, 'end');
        $joinlist = Db("user")->where('jointime', 'between time', [$starttime, $endtime])
            ->field('jointime, status, COUNT(*) AS nums, DATE_FORMAT(FROM_UNIXTIME(jointime), "%Y-%m-%d") AS join_date')
            ->group('join_date')
            ->select();
        for ($time = $starttime; $time <= $endtime;) {
            $column[] = date("Y-m-d", $time);
            $time += 86400;
        }
        $userlist = array_fill_keys($column, 0);
        foreach ($joinlist as $k => $v) {
            $userlist[$v['join_date']] = $v['nums'];
        }

        $dbTableList = Db::query("SHOW TABLE STATUS");
        $addonList = get_addon_list();
        $totalworkingaddon = 0;
        $totaladdon = count($addonList);
        foreach ($addonList as $index => $item) {
            if ($item['state']) {
                $totalworkingaddon += 1;
            }
        }
        $this->view->assign([
            'totaluser'         => User::count(),
            'totaladdon'        => $totaladdon,
            'totaladmin'        => Admin::count(),
            'totalcategory'     => \app\common\model\Category::count(),
            'todayusersignup'   => User::whereTime('jointime', 'today')->count(),
            'todayuserlogin'    => User::whereTime('logintime', 'today')->count(),
            'sevendau'          => User::whereTime('jointime|logintime|prevtime', '-7 days')->count(),
            'thirtydau'         => User::whereTime('jointime|logintime|prevtime', '-30 days')->count(),
            'threednu'          => User::whereTime('jointime', '-3 days')->count(),
            'sevendnu'          => User::whereTime('jointime', '-7 days')->count(),
            'dbtablenums'       => count($dbTableList),
            'dbsize'            => array_sum(array_map(function ($item) {
                return $item['Data_length'] + $item['Index_length'];
            }, $dbTableList)),
            'totalworkingaddon' => $totalworkingaddon,
            'attachmentnums'    => Attachment::count(),
            'attachmentsize'    => Attachment::sum('filesize'),
            'picturenums'       => Attachment::where('mimetype', 'like', 'image/%')->count(),
            'picturesize'       => Attachment::where('mimetype', 'like', 'image/%')->sum('filesize'),
        ]);

        $this->assignconfig('column', array_keys($userlist));
        $this->assignconfig('userdata', array_values($userlist));

        return $this->view->fetch();
    }

    public function recentmonth(){


        $lastmonth=date('Y-n-j',strtotime(date('Y')."-".(date('n')-1).'-1'));
        $ctdate=$this->request->get('ctdate',$lastmonth);

        $thisyear=date('Y',strtotime($ctdate) );
        $lastyear=  ($thisyear-1)."-12-1"   ;
        // print_r($lastyear);
        $nextyear= ($thisyear+1)."-1-1" ;
        // $lastyear="<a class='btn btn-flat btn-block btn-warning btn-addtabs' href='/ct.php/dashboard/recentmonth?ctdate=".$thisyear."' ><<".($thisyear-1)." </a>";

        $lastyear="<li class='page-item'><a class='page-link btn-addtabs' href='/ct.php/dashboard/recentmonth?ctdate=".$lastyear."' ><p class='page-month' ><<</p><p class='page-year' >".($thisyear-1)."</p></a></li>";

        // $nextyear="<a class='btn  btn-flat btn-block  btn-warning btn-addtabs' href='/ct.php/dashboard/recentmonth?ctdate=".$thisyear."' >".($thisyear+1).">> </a>";
        $nextyear="<li class='page-item'><a class='page-link btn-addtabs' href='/ct.php/dashboard/recentmonth?ctdate=".$nextyear."' ><p  class='page-month'  >>></p><p class='page-year' >".($thisyear+1)."</p> </a> </li>"; 

        $monthname=['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
        $monthlist=[];
        for($i=1;$i<13;$i++){
            $monthday=$thisyear."-".$i."-1";
            // print_r($monthday);
            if($monthday == $ctdate){
            // $monthlist[]= "<a  class='btn  btn-flat btn-block  btn-info btn-addtabs' href='/ct.php/dashboard/recentmonth?ctdate=".$monthday."'>".$monthname[$i-1]." </a>";

              $monthlist[]="<li class='page-item active'><a class='page-link  ' href='/ct.php/dashboard/recentmonth?ctdate=".$monthday."'>
                                      <p class='page-month'>".$monthname[$i-1] ."</p>
                                      <p class='page-year'>".$thisyear."</p>
                                  </a>
                              </li>";
                              $thismonth=$monthname[$i-1] ;

            }else{
            // $monthlist[]= "<a  class='btn  btn-flat btn-block btn-default btn-addtabs' href='/ct.php/dashboard/recentmonth?ctdate=".$monthday."' >".$monthname[$i-1]." </a>";   
            $monthlist[]="<li class='page-item'><a class='page-link' href='/ct.php/dashboard/recentmonth?ctdate=".$monthday."'>
                                      <p class='page-month'>".$monthname[$i-1] ."</p>
                                      <p class='page-year'>".$thisyear."</p>
                                  </a>
                              </li>";             
            }

        }

        $this->view->assign('thisyear', $thisyear);
        $this->view->assign('thismonth', $thismonth);        
        $this->view->assign('lastyear', $lastyear);
        $this->view->assign('nextyear', $nextyear);
        $this->view->assign('monthlist', $monthlist);
        // print_r($lastyear);
        // print_r($nextyear);
        // print_r($monthlist);
        
 
        $db="tagsmonthsum";
        $single=['PLC_ID'=>'WORKCENTER','Parts'=>'Parts','Num'=>'','CT'=>'','SapCT'=>'','DiffCT'=>'','DiffPer'=>'0'];
        $list = Db::query( " select PLC_ID, Parts, Num,CT,SapCT,DiffCT,DiffPer from fa_tagsmonthsum where ctdate='".$ctdate."'   order by ABS(DiffPer) DESC limit 5");
            // print_r($ctdate);
        if  ($list==null){ 
            $list=[$single,$single,$single,$single,$single  ];
        }

        $this->view->assign('ct', $list);


        $uap1 = Db::query( " select PLC_ID, Parts, Num,CT,SapCT,DiffCT,DiffPer from fa_tagsmonthsum where ctdate='".$ctdate."' and UAPno='UAP1' order by ABS(DiffPer) DESC limit 5");
        $this->view->assign('uap1', $uap1);

        $uap2 =Db::query( " select PLC_ID, Parts, Num,CT,SapCT,DiffCT,DiffPer from fa_tagsmonthsum where ctdate='".$ctdate."' and UAPno='UAP2' order by ABS(DiffPer) DESC limit 5");
 
        $this->view->assign('uap2', $uap2);

        $uap3 = Db::query( " select PLC_ID, Parts, Num,CT,SapCT,DiffCT,DiffPer from fa_tagsmonthsum where ctdate='".$ctdate."' and  UAPno='UAP3' order by ABS(DiffPer) DESC limit 5");
 
        $this->view->assign('uap3', $uap3);
         $this->view->assign('ctdate', $ctdate);  
        return $this->view->fetch();
    }


    public function recentweek(){

         
        $week= date("w");
        // print_r(date('Y-n-j'));
        $ctdate=date('Y-n-j',mktime(0,0,0,date('n'),date('j')-$week-27 ,date('Y') ) );

              // print_r($ctdate);

        $single=['PLC_ID'=>'WORKCENTER','Parts'=>'Parts','Num'=>'','CT'=>'','SapCT'=>'','DiffCT'=>'','DiffPer'=>'0'];
        $list = Db::query( " select PLC_ID, Parts, Num,CT,SapCT,DiffCT,DiffPer from fa_tags4week where ctdate='".$ctdate."'   order by ABS(DiffPer) DESC limit 5");
      
        if  ($list==null){ 
            $list=[$single,$single,$single,$single ,$single ];
        }

        $this->view->assign('ct', $list);


        $uap1 = Db::query( " select PLC_ID, Parts, Num,CT,SapCT,DiffCT,DiffPer from fa_tags4week where ctdate='".$ctdate."' and UAPno='UAP1' order by ABS(DiffPer) DESC limit 5");
        $this->view->assign('uap1', $uap1);

        $uap2 =Db::query( " select PLC_ID, Parts, Num,CT,SapCT,DiffCT,DiffPer from fa_tags4week where ctdate='".$ctdate."' and UAPno='UAP2' order by ABS(DiffPer) DESC limit 5");
 
        $this->view->assign('uap2', $uap2);

        $uap3 = Db::query( " select PLC_ID, Parts, Num,CT,SapCT,DiffCT,DiffPer from fa_tags4week where ctdate='".$ctdate."' and  UAPno='UAP3' order by ABS(DiffPer) DESC limit 5");
 
        $this->view->assign('uap3', $uap3);
        $this->view->assign('ctdate', $ctdate);  
        return $this->view->fetch();
 
    }

    public function recentseason(){
      

        $last=intval((date('n')-1)/3)*3+1;
        $lastmonth=date('Y-n-j',strtotime(date('Y')."-".$last.'-1'));
        $ctdate=$this->request->get('ctdate',$lastmonth);

        // print_r($lastmonth);
        $thisyear=date('Y',strtotime($ctdate) );
        $lastyear=  ($thisyear-1)."-10-1"   ;
        // print_r($lastyear);
        $nextyear= ($thisyear+1)."-1-1" ;
        // $lastyear="<a class='btn btn-flat btn-block btn-warning btn-addtabs' href='/ct.php/dashboard/recentmonth?ctdate=".$thisyear."' ><<".($thisyear-1)." </a>";

        $lastyear="<li class='page-item'><a class='page-link btn-addtabs' href='/ct.php/dashboard/recentseason?ctdate=".$lastyear."' ><p class='page-month' ><<</p><p class='page-year' >".($thisyear-1)."</p></a></li>";

        // $nextyear="<a class='btn  btn-flat btn-block  btn-warning btn-addtabs' href='/ct.php/dashboard/recentmonth?ctdate=".$thisyear."' >".($thisyear+1).">> </a>";
        $nextyear="<li class='page-item'><a class='page-link btn-addtabs' href='/ct.php/dashboard/recentseason?ctdate=".$nextyear."' ><p  class='page-month'  >>></p><p class='page-year' >".($thisyear+1)."</p> </a> </li>"; 

        $monthname=['Jan', 'Apr', 'Jul', 'Oct' ];
        $monthlist=[];
        for($i=1;$i<5;$i++){
            $monthday=$thisyear."-".(3*($i-1)+1)."-1";
            // print_r($monthday);
            if($monthday == $ctdate){
            // $monthlist[]= "<a  class='btn  btn-flat btn-block  btn-info btn-addtabs' href='/ct.php/dashboard/recentmonth?ctdate=".$monthday."'>".$monthname[$i-1]." </a>";

              $monthlist[]="<li class='page-item active'><a class='page-link  ' href='/ct.php/dashboard/recentseason?ctdate=".$monthday."'>
                                      <p class='page-month'>".$monthname[$i-1] ."</p>
                                      <p class='page-year'>".$thisyear."</p>
                                  </a>
                              </li>";
                              $thismonth=$monthname[$i-1] ;

            }else{
            // $monthlist[]= "<a  class='btn  btn-flat btn-block btn-default btn-addtabs' href='/ct.php/dashboard/recentmonth?ctdate=".$monthday."' >".$monthname[$i-1]." </a>";   
            $monthlist[]="<li class='page-item'><a class='page-link' href='/ct.php/dashboard/recentseason?ctdate=".$monthday."'>
                                      <p class='page-month'>".$monthname[$i-1] ."</p>
                                      <p class='page-year'>".$thisyear."</p>
                                  </a>
                              </li>";             
            }

        }

        $this->view->assign('thisyear', $thisyear);
        $this->view->assign('thismonth', $thismonth);        
        $this->view->assign('lastyear', $lastyear);
        $this->view->assign('nextyear', $nextyear);
        $this->view->assign('monthlist', $monthlist);
        // print_r($lastyear);
        // print_r($nextyear);
        // print_r($monthlist);
        
 
        $db="tagsseasonsum";
        $single=['PLC_ID'=>'WORKCENTER','Parts'=>'Parts','Num'=>'','CT'=>'','SapCT'=>'','DiffCT'=>'','DiffPer'=>'0'];
        $list = Db::query( " select PLC_ID, Parts, Num,CT,SapCT,DiffCT,DiffPer from fa_tagsseasonsum where ctdate='".$ctdate."'   order by ABS(DiffPer) DESC limit 5");
            // print_r($ctdate);
        if  ($list==null){ 
            $list=[$single,$single,$single,$single,$single  ];
        }

        $this->view->assign('ct', $list);


        $uap1 = Db::query( " select PLC_ID, Parts, Num,CT,SapCT,DiffCT,DiffPer from fa_tagsseasonsum where ctdate='".$ctdate."' and UAPno='UAP1' order by ABS(DiffPer) DESC limit 5");
        $this->view->assign('uap1', $uap1);

        $uap2 =Db::query( " select PLC_ID, Parts, Num,CT,SapCT,DiffCT,DiffPer from fa_tagsseasonsum where ctdate='".$ctdate."' and UAPno='UAP2' order by ABS(DiffPer) DESC limit 5");
 
        $this->view->assign('uap2', $uap2);

        $uap3 = Db::query( " select PLC_ID, Parts, Num,CT,SapCT,DiffCT,DiffPer from fa_tagsseasonsum where ctdate='".$ctdate."' and  UAPno='UAP3' order by ABS(DiffPer) DESC limit 5");
 
        $this->view->assign('uap3', $uap3);
        $this->view->assign('ctdate', $ctdate); 
        return $this->view->fetch();
    }


    public function recentsemester(){     

        if (date('n')>6){
            $semester=date('Y').'-7-1' ;
        }else{
            $semester=date('Y').'-1-1' ;
        }
        $ctdate=$this->request->get('ctdate',$semester);
 

        $monthlist=[];
        $monthname=[];
        for ($year=date('Y')-4;$year<= date('Y');$year++){
            $monthname[]='Jan~Jun </p><p>'.$year;
            $monthname[]='Jul~Dec </p><p>'.$year;
            $monthday[]=$year."-1-1";
            $monthday[]=$year."-7-1";
        }
 
        for($i=0;$i<10;$i++){
             
            if($monthday[$i] == $ctdate){

              $monthlist[]="<li class='page-item active'>
                            <a class='page-link' href='/ct.php/dashboard/recentsemester?ctdate=".$monthday[$i]."'>
                                <p class='page-month'>".$monthname[$i] ."</p> 
                            </a>
                            </li>";
                               

            }else{
            // $monthlist[]= "<a  class='btn  btn-flat btn-block btn-default btn-addtabs' href='/ct.php/dashboard/recentmonth?ctdate=".$monthday."' >".$monthname[$i-1]." </a>";   
            $monthlist[]="<li class='page-item'>
                            <a class='page-link' href='/ct.php/dashboard/recentsemester?ctdate=".$monthday[$i]."'>
                                <p class='page-month'>".$monthname[$i ] ."</p> 
                            </a>
                            </li>";             
            }

        }

        // $this->view->assign('thisyear', $thisyear);
        // $this->view->assign('thismonth', $thismonth);        
        // $this->view->assign('lastyear', $lastyear);
        // $this->view->assign('nextyear', $nextyear);
        $this->view->assign('monthlist', $monthlist);
        // print_r($lastyear);
        // print_r($nextyear);
        // print_r($monthlist);
        
 
        $db="tagssemester";
        $single=['PLC_ID'=>'WORKCENTER','Parts'=>'Parts','Num'=>'','CT'=>'','SapCT'=>'','DiffCT'=>'','DiffPer'=>'0'];
        $list = Db::query( " select PLC_ID, Parts, Num,CT,SapCT,DiffCT,DiffPer from fa_tagssemestersum where ctdate='".$ctdate."'   order by ABS(DiffPer) DESC limit 5");
            // print_r($ctdate);
        if  ($list==null){ 
            $list=[$single,$single,$single,$single ,$single ];
        }

        $this->view->assign('ct', $list);


        $uap1 = Db::query( " select PLC_ID, Parts, Num,CT,SapCT,DiffCT,DiffPer from fa_tagssemestersum where ctdate='".$ctdate."' and UAPno='UAP1' order by ABS(DiffPer) DESC limit 5");
        $this->view->assign('uap1', $uap1);

        $uap2 =Db::query( " select PLC_ID, Parts, Num,CT,SapCT,DiffCT,DiffPer from fa_tagssemestersum where ctdate='".$ctdate."' and UAPno='UAP2' order by ABS(DiffPer) DESC limit 5");
 
        $this->view->assign('uap2', $uap2);

        $uap3 = Db::query( " select PLC_ID, Parts, Num,CT,SapCT,DiffCT,DiffPer from fa_tagssemestersum where ctdate='".$ctdate."' and  UAPno='UAP3' order by ABS(DiffPer) DESC limit 5");
 
        $this->view->assign('uap3', $uap3);
        $this->view->assign('ctdate', $ctdate); 
        return $this->view->fetch();
    }

    public function recentyear(){

 
        $year=date('Y').'-1-1' ;
 
        $ctdate=$this->request->get('ctdate',$year);
 

        $monthlist=[];
        $monthname=[];
        for ($year=date('Y')-4;$year<= date('Y');$year++){
            $monthname[]='Jan</p><p> '.$year;
            
            $monthday[]=$year."-1-1"; 
        }
 
        for($i=0;$i<5;$i++){
             
            if($monthday[$i] == $ctdate){

              $monthlist[]="<li class='page-item active'>
                            <a class='page-link' href='/ct.php/dashboard/recentyear?ctdate=".$monthday[$i]."'>
                                <p class='page-month'>".$monthname[$i] ."</p> 
                            </a>
                            </li>";
                               

            }else{
            // $monthlist[]= "<a  class='btn  btn-flat btn-block btn-default btn-addtabs' href='/ct.php/dashboard/recentmonth?ctdate=".$monthday."' >".$monthname[$i-1]." </a>";   
            $monthlist[]="<li class='page-item'>
                            <a class='page-link' href='/ct.php/dashboard/recentyear?ctdate=".$monthday[$i]."'>
                                <p class='page-month'>".$monthname[$i ] ."</p> 
                            </a>
                            </li>";             
            }

        } 
        $this->view->assign('monthlist', $monthlist); 
        
        $single=['PLC_ID'=>'WORKCENTER','Parts'=>'Parts','Num'=>'','CT'=>'','SapCT'=>'','DiffCT'=>'','DiffPer'=>'0'];
        $list = Db::query( " select PLC_ID, Parts, Num,CT,SapCT,DiffCT,DiffPer from fa_tagsyearsum where ctdate='".$ctdate."'   order by ABS(DiffPer) DESC limit 5");
            // print_r($ctdate);
        if  ($list==null){ 
            $list=[$single,$single,$single,$single,$single  ];
        }

        $this->view->assign('ct', $list);


        $uap1 = Db::query( " select PLC_ID, Parts, Num,CT,SapCT,DiffCT,DiffPer from fa_tagsyearsum where ctdate='".$ctdate."' and UAPno='UAP1' order by ABS(DiffPer) DESC limit 5");
        $this->view->assign('uap1', $uap1);

        $uap2 =Db::query( " select PLC_ID, Parts, Num,CT,SapCT,DiffCT,DiffPer from fa_tagsyearsum where ctdate='".$ctdate."' and UAPno='UAP2' order by ABS(DiffPer) DESC limit 5");
 
        $this->view->assign('uap2', $uap2);

        $uap3 = Db::query( " select PLC_ID, Parts, Num,CT,SapCT,DiffCT,DiffPer from fa_tagsyearsum where ctdate='".$ctdate."' and  UAPno='UAP3' order by ABS(DiffPer) DESC limit 5");
 
        $this->view->assign('uap3', $uap3);
        $this->view->assign('ctdate', $ctdate);  
        return $this->view->fetch();
    }

    public function listyear(){
        $this->model =  model('tagsyearsum');   
     

        if ($this->request->isAjax()) { 
                list($where, $sort, $order, $offset, $limit) = $this->buildparams(null);
                $list = $this->model
                    ->where($where)
                    ->order($sort, $order)
                    ->limit($offset, $limit)
                    ->paginate($limit);
                $result = array("total" => $list->total(), "rows" => $list->items());

                print_r($this->model->getLastSql() );
                return json($result);
        }

        return $this->view->fetch();

        
 
    }
 
   public function listmonth(){
        $this->model =  model('tagsmonthsum');   
     

        if ($this->request->isAjax()) { 
                list($where, $sort, $order, $offset, $limit) = $this->buildparams(null);
                $list = $this->model
                    ->where($where)
                    ->order($sort, $order)
                    ->limit($offset, $limit)
                    ->paginate($limit);
                $result = array("total" => $list->total(), "rows" => $list->items());

                print_r($this->model->getLastSql() );
                return json($result);
        }

        return $this->view->fetch();
    }

   public function listsemester(){

        $this->model =  model('tagssemestersum');   
     

        if ($this->request->isAjax()) { 
                list($where, $sort, $order, $offset, $limit) = $this->buildparams(null);
                $list = $this->model
                    ->where($where)
                    ->order($sort, $order)
                    ->limit($offset, $limit)
                    ->paginate($limit);
                $result = array("total" => $list->total(), "rows" => $list->items());

                print_r($this->model->getLastSql() );
                return json($result);
        }

        return $this->view->fetch();
    }

   public function listseason(){

        $this->model =  model('tagsseasonsum');   
     

        if ($this->request->isAjax()) { 
                list($where, $sort, $order, $offset, $limit) = $this->buildparams(null);
                $list = $this->model
                    ->where($where)
                    ->order($sort, $order)
                    ->limit($offset, $limit)
                    ->paginate($limit);
                $result = array("total" => $list->total(), "rows" => $list->items());

                // print_r($this->model->getLastSql() );
                return json($result);
        }

        return $this->view->fetch();
    }

       public function list4week(){

 
        $year=date('Y').'-1-1' ; 
        $unit='plant';
        $ctdate=$this->request->get('ctdate',$year);
        $unit=$this->request->get('unit',$unit); 
 
 
        
        $single=['PLC_ID'=>'WORKCENTER','Parts'=>'Parts','Num'=>'','CT'=>'','SapCT'=>'','DiffCT'=>'','DiffPer'=>'0'];

        $sql=" select PLC_ID, Parts, Num,CT,SapCT,DiffCT,DiffPer from fa_tagsyearsum where ctdate='".$ctdate."'   order by ABS(DiffPer) DESC  ";

        if ($unit !='plant'){
            $sql=" select PLC_ID, Parts, Num,CT,SapCT,DiffCT,DiffPer from fa_tagsyearsum where ctdate='".$ctdate."' and  UAPno='".$unit ."'  order by ABS(DiffPer) DESC  ";            
        }
        $list = Db::query( $sql);
            // print_r($ctdate);
        if  ($list==null){ 
            $list=[$single,$single,$single,$single,$single  ];
        }

        $this->view->assign('ct', $list);
        $this->view->assign('ctdate', $ctdate); 
 
 
        return $this->view->fetch();
    }

}
