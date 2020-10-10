<?php
/**
 * ==============================================
 * 字符处理类
 * ----------------------------------------------
 * PHP version 7 灵析
 * ==============================================
 * @category：  PHP
 * @author：    xieshunv <xieshun@lingxi360.cn>
 * @copyright： @2020 http://www.lingxi360.cn/
 * @version：   v1.0.0
 * @date:       2020-10-10 17:29
 */
namespace Xieshunv\Jasmine;

class String
{
    static function getStrLen($str = '')
    {
        if (empty($str)) {
            return 0;
        }

        return strlen($str);
    }

}