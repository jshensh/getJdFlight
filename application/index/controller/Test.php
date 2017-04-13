<?php
namespace app\index\controller;

use think\Controller;
use think\Db;
use app\common\controller\Flight;
use app\index\model\FlightInfo as FlightInfoModel;

class Test extends Controller
{
    public function index()
    {
        $flight1 = Flight::get([
                "depCity" => "深圳",
                "arrCity" => "上海",
                "depDate" => "2017-04-29",
                "arrDate" => "2017-05-01",
                "queryModule" => "2",
                "lineType" => "RT",
                "queryType" => "listquery"
            ], [],
            function($flight) {
                if ($flight["sourceId"] === '188126' && $flight["leastClassInfoFlag"] === 'jdzy') {
                    return true;
                } else if ($flight["sourceId"] == '588586' || $flight["sourceId"] == '621906') {
                    return true;
                } else if ($flight["leastClassInfoFlag"] == '1') {
                    return true;
                }
                return false;
            });
        foreach ($flight1 as $key => $value) {
            unset($flight1[$key]["bingoClassInfoList"]);
            unset($flight1[$key]["bingoLeastClassInfo"]);
        }
        dump($flight1);
    }

    public function dbtest() {
        dump(FlightInfoModel::get(['flight_no' => '流年']));
    }
}
