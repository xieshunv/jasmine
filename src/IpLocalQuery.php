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

use xieshunv\jasmine\IpException;

class IpLocalQuery
{
    private static $instance = NULL;

    public $encoding = 'UTF-8';

    protected $ip;
    protected $file;
    private $offset;
    private $fp;
    private $index;

    /**
     * 静态工厂方法，返还此类的唯一实例
     */
    public static function create()
    {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * 防止用户克隆实例
     */
    public function __clone()
    {
        die('Clone is not allowed.' . E_USER_ERROR);
    }

    private function __construct()
    {
        $this->openDataBase(__DIR__ . '/../data/ip_data.dat');
    }

    public function __destruct()
    {
        if ($this->fp) {
            fclose($this->fp);
        }
    }

    /**
     * @return int
     */
    protected function ipCheck()
    {
        return preg_match('/^((?:(?:25[0-5]|2[0-4]\d|((1\d{2})|([1-9]?\d)))\.){3}(?:25[0-5]|2[0-4]\d|((1\d{2})|([1-9]?\d))))$/u', $this->ip);
    }

    public function query($ip)
    {
        $this->ip = $ip;
        if (!$this->ipCheck()) {
            throw new IpException('Illegal IP:' . $this->ip);
        }
        $nip = gethostbyname($this->ip);
        $ipdot = explode('.', $nip);

        $nip2 = pack('N', ip2long($nip));

        $tmp_offset = (int)$ipdot[0] * 4;
        $start = unpack('Vlen', $this->index[$tmp_offset] . $this->index[$tmp_offset + 1] . $this->index[$tmp_offset + 2] . $this->index[$tmp_offset + 3]);

        $index_offset = $index_length = NULL;
        $max_comp_len = $this->offset['len'] - 1024 - 4;
        for ($start = $start['len'] * 8 + 1024; $start < $max_comp_len; $start += 8) {
            if ($this->index{$start} . $this->index{$start + 1} . $this->index{$start + 2} . $this->index{$start + 3} >= $nip2) {
                $index_offset = unpack('Vlen', $this->index{$start + 4} . $this->index{$start + 5} . $this->index{$start + 6} . "\x0");
                $index_length = unpack('Clen', $this->index{$start + 7});

                break;
            }
        }

        if ($index_offset === NULL) {
            return 'N/A';
        }

        fseek($this->fp, $this->offset['len'] + $index_offset['len'] - 1024);

        $data = explode("\t", fread($this->fp, $index_length['len']));

        return [
            'ip' => $this->ip,
            'country' => $this->array_get($data, 0, ''),
            'area' => '',
            'region' => $this->array_get($data, 0, '') !== $this->array_get($data, 1, '') ? $this->array_get($data, 1, '') : '',
            'city' => $this->array_get($data, 2, ''),
            'county' => $this->array_get($data, 3, ''),
            'isp' => '',
        ];

    }

    private function openDataBase($file)
    {
        if (!file_exists($file) || !is_readable($file)) {
            throw new IpException($file . ' does not exist, or is not readable');
        }
        $this->file = $file;
        $this->fp = fopen($file, 'rb');
        $this->offset = unpack('Nlen', fread($this->fp, 4));
        if ($this->offset['len'] < 4) {
            throw new IpException('Invalid Database File!');
        }
        $this->index = fread($this->fp, $this->offset['len'] - 4);
    }

    private function array_get($array, $key, $default = null)
    {
        if (is_null($key)) {
            return $array;
        }

        if (isset($array[$key])) {
            return $array[$key];
        }

        foreach (explode('.', $key) as $segment) {
            if (!is_array($array) || !array_key_exists($segment, $array)) {
                return value($default);
            }

            $array = $array[$segment];
        }
        return $array;
    }
}