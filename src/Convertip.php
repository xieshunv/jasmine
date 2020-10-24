<?php
/**
 * ==============================================
 * 通过IP地址，获取城市信息
 * ----------------------------------------------
 * PHP version 7 灵析
 * ==============================================
 * @category：  PHP
 * @author：    xieshunv <xieshun@lingxi360.cn>
 * @copyright： @2020 http://www.lingxi360.cn/
 * @version：   v1.0.0
 * @date:       2020-10-24 12:43
 */

namespace xieshunv\jasmine;

use xieshunv\jasmine\IpLocalQuery;

class Convertip
{
    /**
     * @var 保存全局实例
     */
    private static $instance;

    /**
     * 私有化构造函数，防止外界实例化对象
     * Convertip constructor.
     */
    private function __construct()
    {
    }

    /**
     * 私有化克隆函数，防止外界克隆对象
     */
    private function __clone()
    {
        die('Clone is not allowed.' . E_USER_ERROR);
    }

    /**
     * 单例访问统一入口
     * @return
     */
    public static function getInstance()
    {
        if (!(self::$instance instanceof self)) {
            self::$instance = new self();
        }
        return self::$instance;
    }


    /**
     * @param string $ip
     * @return mixed
     */
    public static function getCityByIp($ip = "")
    {
        if (empty($ip)) {
            $ip = self::getClientIp();
        }

        $query = IpLocalQuery::create();
        return $query->query($ip);
    }

    /**
     * 获取用户真实IP
     * @return array|false|mixed|string
     */
    private static function getClientIp()
    {
        if (getenv("HTTP_CLIENT_IP") && strcasecmp(getenv("HTTP_CLIENT_IP"), "unknown"))
            $ip = getenv("HTTP_CLIENT_IP");
        else if (getenv("HTTP_X_FORWARDED_FOR") && strcasecmp(getenv("HTTP_X_FORWARDED_FOR"), "unknown"))
            $ip = getenv("HTTP_X_FORWARDED_FOR");
        else if (getenv("REMOTE_ADDR") && strcasecmp(getenv("REMOTE_ADDR"), "unknown"))
            $ip = getenv("REMOTE_ADDR");
        else if (isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], "unknown"))
            $ip = $_SERVER['REMOTE_ADDR'];
        else
            $ip = "127.0.0.1";
        return $ip;
    }

    public function __destruct()
    {
    }
}
