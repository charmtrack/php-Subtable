<?php
require 'Config.php';  
require 'Model.php';  
class User extends Model  
{  
    protected $dbnamePrefix = 'cloude';  
    protected $tablePrefix = 'user';  
} 
//生成唯一uuid
function create_uuid($prefix = ""){    //可以指定前缀
    $str = md5(uniqid(mt_rand(), true));   
    $uuid  = substr($str,0,8) . '-';   
    $uuid .= substr($str,8,4) . '-';   
    $uuid .= substr($str,12,4) . '-';   
    $uuid .= substr($str,16,4) . '-';   
    $uuid .= substr($str,20,12);   
    return $prefix . $uuid;
}
 
$userId=create_uuid();
$user = new User($userId);
$data=array('id'=>$userId,'name'=>'大明'.$userId,'password'=>'14e1b600b1fd579f47433b88e8d85291','sex'=>'男');  
if($result=$user->insert($data)){
	echo '插入成功：','<pre/>';
	print_r($result);
}
 
$condition=array("id"=>$userId);
$list=$user->select($condition);
if($list){
	echo '查询成功：','<pre/>';
	print_r($list);
}
