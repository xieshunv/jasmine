<?php
/**
 * ==============================================
 * 通过IP地址，获取城市信息 测试文件
 * ----------------------------------------------
 * PHP version 7 灵析
 * ==============================================
 * @category：  PHP
 * @author：    xieshunv <xieshun@lingxi360.cn>
 * @copyright： @2020 http://www.lingxi360.cn/
 * @version：   v1.0.0
 * @date:       2020-10-24 12:43
 */

//设置时区
date_default_timezone_set("Asia/Shanghai");
//设置字符编码为utf-8
header("Content-Type: text/html;charset=utf-8");
//打开所有错误
error_reporting(E_ALL);
ini_set("display_errors","On");

require_once './vendor/autoload.php';

use xieshunv\jasmine\Convertip;

$conver = Convertip::getInstance();
$ipInfo = $conver::getCityByIp("106.56.47.180");

var_dump($ipInfo);