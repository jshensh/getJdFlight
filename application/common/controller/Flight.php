<?php
namespace app\common\controller;

use app\common\controller\CustomCurl;
use app\common\controller\Common;

class Flight
{
    private function doQuery($queryData, $refererQueryData)
    {
        $refererData = [
            "_charset_" => "utf-8",
            "query.depCity" => $queryData["depCity"],
            "query.arrCity" => $queryData["arrCity"],
            "query.depDate" => $queryData["depDate"],
            "query.arrDate" => $queryData["arrDate"],
            "query.queryModule" => $queryData["queryModule"],
            "query.queryType" => $queryData["queryType"],
            "query.lineType" => $queryData["lineType"]
        ];
        $curlSet = CustomCurl::init('https://jipiao.jd.com/search/queryFlight.action?' . http_build_query($queryData))
                    ->set('referer', 'https://jipiao.jd.com/ticketquery/flightSearch.action?' . http_build_query(array_merge($refererData, $refererQueryData)))
                    ->setHeader('X-Requested-With', 'XMLHttpRequest');
        $curlObj = $curlSet->exec();
        if ($curlObj) {
            $jsonObj = json_decode($curlObj->getBody(), 1);
            if ($jsonObj) {
                return $jsonObj;
            }
        }
        return false;
    }
    
    private function queryFlight($queryData, $refererQueryData)
    {
        $flight = [];
        $data = $this->doQuery($queryData, $refererQueryData);
        if ((int)$data["code"] === 200) {
            $data = $data["data"];
            $flight[] = $data;
            if ($queryData["depDate"] === $data["queryDate"]) {
                $queryData["queryuuid"] = $data["queryuuid"];
                for ($i = 0; $i < 15 && !$data["isFinished"]; $i++) {
                    sleep($data["interval"] / 1000);
                    $data = $this->doQuery($queryData, $refererQueryData);
                    if ((int)$data["code"] === 200) {
                        $data = $data["data"];
                        $flight[] = $data;
                    } else {
                        break;
                    }
                }
                unset($data);
                return $flight;
            }
        }
        return false;
    }

    private function getFlight($queryData, $refererQueryData, $filter)
    {
        $data = $this->queryFlight($queryData, $refererQueryData);
        if (!$data) {
            return false;
        }
        $flights = [];
        foreach ($data as $value) {
            if (is_array($value["flights"])) {
                foreach ($value["flights"] as $value) {
                    foreach ($value["bingoClassInfoList"] as $k => $flight) {
                        if (!$filter($flight)) {
                            unset($value["bingoClassInfoList"][$k]);
                        }
                    }
                    $value["bingoClassInfoList"] = array_values(
                        Common::mergeSort(
                            $value["bingoClassInfoList"],
                            function($left, $right) {
                                return $left["price"] < $right["price"];
                            }
                        )
                    );
                    $flights[$value["flightNo"]] = $value;
                }
            }
        }
        unset($data);
        return $flights;
    }

    private function __construct() {}

    public static function get($queryData, $refererQueryData, $filter)
    {
        $tmp = new self;
        return $tmp->getFlight($queryData, $refererQueryData, $filter);
    }
}
