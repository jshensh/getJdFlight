<?php
namespace app\console;

use think\console\Command;
use think\console\Input;
use think\console\input\Option;
use think\console\Output;
use app\common\controller\Flight;
use app\index\model\Flight as FlightModel;
use app\common\controller\Common;

class Cron extends Command
{
    protected function configure()
    {
        // 指令配置
        $this
            ->setName('cron')
            ->setDescription('Crontab');
    }

    protected function execute(Input $input, Output $output)
    {
        $t = time();
        $output->writeln("Running.");
        $flight1 = false;
        for ($i = 0; $i < 3 && !$flight1; $i++) {
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
        }
        if ($flight1) {
            $goFlight = [];
            foreach ($flight1 as $key => $value) {
                if (count($value["bingoClassInfoList"])) {
                    $goFlight[$key] = [
                        "arrDate" => $value["arrDate"],
                        "flightNo" => $value["flightNo"],
                        "arrTime" => $value["arrTime"],
                        "uniqueKey" => $value["bingoClassInfoList"][0]["uniqueKey"],
                        "price" => $value["bingoClassInfoList"][0]["price"],
                        "sourceId" => $value["bingoClassInfoList"][0]["sourceId"]
                    ];
                }
            }
            $sortedGoFlight = Common::mergeSort($goFlight, function($left, $right) {
                return $left["price"] < $right["price"];
            });

            $flight2 = false;
            for ($i = 0; $i < 3 && !$flight2; $i++) {
                $flight2 = Flight::get([
                        "depCity" => "上海",
                        "arrCity" => "深圳",
                        "depDate" => "2017-05-01",
                        "arrDate" => "2017-04-29",
                        "queryModule" => "3",
                        "lineType" => "RT",
                        "queryType" => "listquery",
                        "uniqueKey" => $sortedGoFlight[0]["uniqueKey"],
                        "sourceId" => $sortedGoFlight[0]["sourceId"],
                        "arrTime" => $sortedGoFlight[0]["arrTime"]
                    ], [
                        "query.depCity" => "深圳",
                        "query.arrCity" => "上海",
                        "query.goTime" => "undefined",
                        "query.backTime" => "undefined",
                        "query.depDate" => "2017-04-29",
                        "query.arrDate" => "2017-05-01",
                        "query.classNo" => " ",
                        "query.oneBox" => ""
                    ],
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
            }

            if ($flight2) {
                $backFlight = [];
                foreach ($flight2 as $key => $value) {
                    if (count($value["bingoClassInfoList"])) {
                        $backFlight[$key] = [
                            "arrDate" => $value["arrDate"],
                            "flightNo" => $value["flightNo"],
                            "arrTime" => $value["arrTime"],
                            "uniqueKey" => $value["bingoClassInfoList"][0]["uniqueKey"],
                            "price" => $value["bingoClassInfoList"][0]["price"],
                            "sourceId" => $value["bingoClassInfoList"][0]["sourceId"]
                        ];
                    }
                }
                $sortedBackFlight = Common::mergeSort($backFlight, function($left, $right) {
                    return $left["price"] < $right["price"];
                });

                // dump($sortedGoFlight);
                // dump($sortedBackFlight);

                $flightModel = new FlightModel;
                $list = [];
                foreach (array_merge($sortedGoFlight, $sortedBackFlight) as $value) {
                    $list[] = [
                        "arr_date" => $value["arrDate"],
                        "flight_no" => $value["flightNo"],
                        "price" => $value["price"],
                        "create_at" => $t
                    ];
                }
                if ($flightModel->saveAll($list)) {
                    $output->writeln("Done!");
                } else {
                    $output->writeln("Error!");
                }
            } else {
                $output->writeln("Get Flight 2 Error!");
            }
        } else {
            $output->writeln("Get Flight 1 Error!");
        }
    }
}


/*
Filter:
if (subValue.sourceId == '138669') {
    '国航旗舰店';
} else if (subValue.sourceId == '86385') {
    '首航旗舰店';
} else if (subValue.sourceId == '106706') {
    '深航旗舰店';
} else if (subValue.sourceId == '616106') {
    '海航航旗舰店';
} else if (subValue.sourceId == '616066') {
    '东航旗舰店';
} else if (subValue.sourceId == '615927') {
    '南航旗舰店';
} else if (subValue.sourceId == '188126' && subValue.leastClassInfoFlag == 'jdzy') {
    '京东自营';
} else if (subValue.sourceId == '588586' || subValue.sourceId == '621906') {
    '京东自营';
} else if (subValue.leastClassInfoFlag == '1') {
    '京东特享';
} else if (subValue.classLimit != null && subValue.classLimit.mp != null) {
    'N人飞享';
} else {
    '商家优选';
}*/