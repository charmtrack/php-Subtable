<?php
require_once 'Config.php';//引入配置信息
class Model{  
    public $config;		//数据库配置
    public $connection;	//pdo
    protected $dbnamePrefix; //库前缀如cloude_50 前缀为cloude 
    protected $tablePrefix;  //表前缀
    protected $dbname;	//分库分表后对应的库
    protected $table; 	//分库分表后对应的库表
    
    public function __construct($id){  
        $this->config = new Config($this->dbnamePrefix, $this->tablePrefix, $id); 					//根据id找到对应库和表
        $this->connection = new Pdo($this->config->dsn, $this->config->user, $this->config->password);//实例化pdo  
        $this->connection->exec("set names utf8");   
		$this->dbname = $this->config->dbname;
		$this->table = $this->config->table;  
    }  
    
    public function update(array $data, array $where = array()){  
    
    }  
    
    public function select(array $condition){ 
		$sqlwhere='';
		if(!empty($condition)){   
			foreach ($condition as $field => $value) {  
				$where[] = '`'.$field.'`='."'".addslashes($value)."'";  
			}  
            $sqlwhere .= ' '.implode(' and ', $where);   
        }
		$sql="select * from ".$this->dbname.'.'.$this->table;
		if($sqlwhere){
			$sql.=" where $sqlwhere";
		}
		$res=$this->connection->query($sql);
		$data['data']=$res->fetchAll(PDO::FETCH_ASSOC);
		$data['info']=array("dsn"=>$this->config->dsn,"dbname"=>$this->dbname,"table"=>$this->table,"sql"=>$sql);
		return $data;   
    }  
    public function insert(array $arrData) {
		$name = $values = '';
		$flag = $flagV = 1;
		$true = is_array( current($arrData) );//判断是否一次插入多条数据
		if($true) {
			//构建插入多条数据的sql语句
			foreach($arrData as $arr) {
				$values .= $flag ? '(' : ',(';
				foreach($arr as $key => $value) {
					if($flagV) {
						if($flag) $name .= "$key";
						$values .= "'$value'";
						$flagV = 0;
					} else {
						if($flag) $name .= ",$key";
						$values .= ",'$value'";
					}
				}
				$values .= ') ';
				$flag = 0;
				$flagV = 1;
			}
		} else {
			//构建插入单条数据的sql语句
			foreach($arrData as $key => $value) {
				if($flagV) {
					$name = "$key";
					$values = "('$value'";
					$flagV = 0;
				} else {
					$name .= ",$key";
					$values .= ",'$value'";
				}
			}
			$values .= ") ";
		}
		 
		$sql = "insert into ".$this->dbname.'.'.$this->table." ($name) values $values";
		if( ($rs = $this->connection->exec($sql) ) > 0 ) {
			return array("dsn"=>$this->config->dsn,"dbname"=>$this->dbname,"table"=>$this->table,"sql"=>$sql);
		}
		return false;
	}
    public function query($sql){  
        return $this->connection->query($sql);  
    }  
}
