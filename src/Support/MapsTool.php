<?php
/**
 * Description: 地图相关.
 * Author: momo
 * Date: 2019/12/13
 * Copyright: momo
 */
namespace MoCommon\Support;

class MapsTool
{
    /**
     * 根据地址获取经纬度
     * @param $address 地址信息
     * @param string $city 地址所在城市
     * @return array  经纬度
     */
    public function getLatLng($address,$city="")
    {
        $result = ["lat" => "", "lng" => ""];
        $ak = 'ydv6qbmcXPsGA74gK5270Sfd';//您的百度地图ak，可以去百度开发者中心去免费申请
        $url = "http://api.map.baidu.com/geocoder/v2/?callback=renderOption&output=json&address=" . urlencode($address) . "&city=" . $city . "&ak=" . $ak;
        $data = file_get_contents($url);
        $data = str_replace('renderOption&&renderOption(', '', $data);
        $data = str_replace(')', '', $data);
        $data = json_decode($data, true);
        if (!empty($data) && $data['status'] == 0) {
            $result['lat'] = $data['result']['location']['lat'];
            $result['lng'] = $data['result']['location']['lng'];
        }
        return $result;//返回经纬度结果
    }

    /**
     * 微信经纬度转换为百度坐标
     * @param $latitude 微信api获取到的纬度
     * @param $longitude 微信api获取到的经度
     * @return array  百度的经纬度
     */
    public static function changeCoords($latitude,$longitude)
    {
        $url = "http://api.map.baidu.com/geoconv/v1/?coords=" . $longitude . "," . $latitude . "&from=1&to=5&ak=ydv6qbmcXPsGA74gK5270Sfd";
        $result = json_decode(file_get_contents($url), true);
        $latitudeNew = $result["result"][0]["y"];
        $longitudeNew = $result["result"][0]["x"];
        $returnData = [
            "latitude" => $latitudeNew,
            "longitude" => $longitudeNew
        ];
        return $returnData;
    }

    /**
     * 计算两点之间的距离
     * @param $lat1 纬度1
     * @param $lng1 经度1
     * @param $lat2 纬度2
     * @param $lng2 经度2
     * @param int $len_type 1m；2km;
     * @param int $decimal
     * @return float
     */
    public static function getDistance($lat1, $lng1, $lat2, $lng2, $len_type = 1, $decimal = 2)
    {
        $radLat1 = $lat1 * PI() / 180.0;   //PI()圆周率
        $radLat2 = $lat2 * PI() / 180.0;
        $a = $radLat1 - $radLat2;
        $b = ($lng1 * PI() / 180.0) - ($lng2 * PI() / 180.0);
        $s = 2 * asin(sqrt(pow(sin($a / 2), 2) + cos($radLat1) * cos($radLat2) * pow(sin($b / 2), 2)));
        $s = $s * 6378.137;
        $s = round($s * 1000);
        if ($len_type-- > 1) {
            $s /= 1000;
        }
        return round($s, $decimal);
    }

}
