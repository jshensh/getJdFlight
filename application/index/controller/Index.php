<?php
namespace app\index\controller;

use think\Controller;
use app\index\model\Flight as FlightModel;

class Index extends Controller
{
    public function index()
    {
        $this->assign('chartData', json_encode(FlightModel::all()));
        return $this->fetch();
    }
}
