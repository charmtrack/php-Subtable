<?php
ini_set('memory_limit', '-1');
$con=mysql_connect("127.0.0.1","root","root");
if($con){	
	for($i=0;$i<10;$i++){//10个库
		//删库 谨慎
		//$sql="drop database cloude_{$i};";
		//mysql_query($sql);
		$sql="create database cloude_{$i} default character set utf8 collate utf8_general_ci;";
		$do=mysql_query($sql,$con)or die(mysql_error());
		if($do){
			mysql_select_db("cloude_{$i}",$con);
			mysql_query("set name gtf8");
			for($j=0;$j<10;$j++){		//10个表
				$sql="drop table if exists user_{$j};";
				mysql_query($sql);
				$sql="create table user_{$j}
				(
					id char(36) not null primary key,
					name char(15) not null default '',
					password char(32) not null default '',
					sex char(1) not null default '男'
				)engine=InnoDB;";
				$do=mysql_query($sql,$con) or die(mysql_error());
				if($do){
					echo "create table user_{$j} successful! <br/>";
				}else{
					echo "create error!";
				}
			}
		}
    }
}else{
    echo "connect error!!!!";
}
