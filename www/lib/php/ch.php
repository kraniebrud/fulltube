<?php
class ch{
	protected static $ch = false;
	static function obj(){
		if(!self::$ch){
			self::$ch = curl_init();
			curl_setopt(self::$ch, CURLOPT_RETURNTRANSFER, 1);
		}
		return self::$ch;
	}
	static function destroy(){
		curl_close(self::obj());
		self::$ch = false;
	}
	static function getOutput($url){
		self::setUrl($url);
		$return = self::exec();
		return $return;
	}
	static function setUrl($url){
		curl_setopt(self::obj(), CURLOPT_URL, $url);
	}
	static function exec(){
		return curl_exec(self::obj());
	}
}