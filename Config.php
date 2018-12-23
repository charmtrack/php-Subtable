<?php
class Config{  
    public $dsn;  
    public $user;  
    public $password;  
    public $dbname; //分库分表后得到的数据库名
    public $table; //分库分表后得到的表名
    private static $config;//mysql配置数组
    private static $configFile = 'mysql.php'; //配置文件路径 
    
    public function __construct($dbname, $table, $id = 0){  
        if (is_null(static::$config)) {  
            $config = include(static::$configFile);  
            static::$config = $config;  
        }  
    
        $config = static::$config;  
        if (isset($config['shared']) && isset($config['shared'][$dbname])) {  
            $dbconfig = $config['shared'][$dbname];  
            $id = is_numeric($id) ? (int)$id : crc32($id);  
            $database_id = ($id / $dbconfig['database_split'][0]) % $dbconfig['database_split'][1];  
            $table_id = ($id / $dbconfig['table_split'][0]) % $dbconfig['table_split'][1];  
    
            foreach ($dbconfig['host'] as $key => $conf) {  
                list($from, $to) = explode('-', $key);  
                //if ($from <= $database_id && $database_id <= $to) {  
                    $the_config = $conf;  
                //}  
            }  
    
            $this->dbname = $dbname . '_' . $database_id;  
            $this->table = $table . '_' . $table_id;  
        } else {  
            $this->dbname = $dbname;  
            $this->table = $table;  
            $the_config = $config['db'][$dbname];  
        }  
        $c = $the_config;  
        if (isset($c['unix_socket']) && $c['unix_socket']) {  
            $this->dsn = sprintf('mysql:dbname=%s;unix_socket=%s', $this->dbname, $c['unix_socket']);  
        } else {  
            $this->dsn = sprintf('mysql:dbname=%s;host=%s;port=%s', $this->dbname, $c['host'], $c['port']);  
        }  
        $this->user = $c['user'];  
        $this->password = $c['password'];  
    }  
    
}
