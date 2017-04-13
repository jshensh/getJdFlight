<?php
namespace app\index\controller;

use think\Controller;
use think\Db;
// use app\index\model\Flight as FlightModel;

class Index extends Controller
{
    public function index()
    {
/*         $this->assign('chartData', json_encode(FlightModel::all(function($query){
            $query->field('flight_no,dep_date,price,create_at')->order('id');
        }))); */

        $this->assign('chartData', json_encode(Db::name('flight')->field('flight_no,dep_date,price,create_at')->order('id')->select()));
        $this->assign('infoData', json_encode(Db::name('flightInfo')->field('flight_no,dep_date,dep_time')->order('dep_date,dep_time')->select()));
        return $this->fetch();
    }
}
