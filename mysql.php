<?php
$default = array(  
    'unix_socket' => null,  
    'host' => '127.0.0.1',  
    'port' => '3306',  
    'user' => 'root',  
    'password' => 'root',  
);    
$config = array(  
    // 不进行分库分表的数据库  
    'db' => array(  
        'hadoop' => $default,  
    ),  
    // 分库分表  
    'shared' => array(  
        'cloude' => array(  
            'host' => array(  
                /** 
                 * 编号为 0 到 4 的库使用的链接配置 
                 */ 
                '0-4' => $default,  
                /** 
                 * 编号为 5 到 9 的库使用的链接配置 
                 */ 
                '5-9' => $default,   
    
            ),  
    
            // 分库分表规则  
            /** 
             * 下面的配置对应10库10表
             * 如果根据 uid 进行分表，假设 uid 为 224，对应的库表为： 
             *  (224 / 1) % 10 = 4 为编号为 4 的库 
             *  (224 / 10) % 10 = 1 为编号为 2 的表 
             */ 
            'database_split' => array(1, 10),  
            'table_split' => array(10, 10),  
        ),  
    ),  
);  
return $config;
