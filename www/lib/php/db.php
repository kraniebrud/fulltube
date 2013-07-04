<?php
	class db{
	private $con;
	function __construct($dbHost="localhost", $dbUser="root", $dbPassword="", $dbDatabase="fulltube"){
		$this->con = mysql_pconnect($dbHost, $dbUser, $dbPassword) or die("Couldn't find host! ".mysql_error());
		mysql_select_db($dbDatabase) or die("Couldn't select database! ".mysql_error());
	}
	function close(){
		mysql_close($this->con);
	}
	function query($query){
		$result = mysql_query($query, $this->con);
		return $result;
	}
	function assoc($query, $limit=NULL, $data=NULL){
		if(strlen($limit)>0){
			$query .= " LIMIT ".$limit;
		}
		$result = $this->query($query);
		while($row = mysql_fetch_assoc($result)){
			$data[]=$row;
		}
		return $data;
	}
	function row($query){
		$data=$this->assoc($query,"0,1");
		return $data[0];
	}
	function rowcount($from,$col="id",$as=NULL){
		if($as==NULL){
			$as = $col;
		}
		$as = " AS ".$as;
		$data = $this->row("SELECT COUNT(".$col.")".$as." FROM ".$from);
		return $data;
	}
}
?>
