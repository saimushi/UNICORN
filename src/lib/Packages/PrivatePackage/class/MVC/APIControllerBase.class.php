<?php

class APIControllerBase {

	public $controlerClassName = "";
	public $httpStatus = 200;
	public $outputType = "html";

	/**
	 */
	protected static function _clearCacheImage($argFilePath, $argMemcacheDSN=NULL){
		$DSN = NULL;
		if(NULL === $argMemcacheDSN && class_exists('Config') && NULL !== Config::constant('MEMCACHE_DSN')){
			$DSN = Config::MEMCACHE_DSN;
		}
		else {
			$DSN = $argMemcacheDSN;
		}
		if(NULL !== $DSN && class_exists('Memcache', FALSE)){
			try{
				Memcached::start($DSN);
				@Memcached::delete($argFilePath);
			}
			catch (Exception $Exception){
				logging(__CLASS__.PATH_SEPARATOR.__METHOD__.PATH_SEPARATOR.__LINE__.PATH_SEPARATOR.$Exception->getMessage(), 'exception');
			}
		}
		return true;
	}
}

?>