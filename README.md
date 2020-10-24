# 通过IP地址，获取城市信息
## composer require xieshunv/jasmine

    <?php
        use xieshunv\jasmine\Convertip;
        class test
        {
            public function __construct()
            {
            }
            
            /**
             * @param $ip
             */
            public function getCityInfo($ip)
            {
                $conver = Convertip::getInstance();
                $ipInfo = $conver::getCityByIp($ip);
                        
                var_dump($ipInfo);
            }
        }
        
        
            
        
        

            


 