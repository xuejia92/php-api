<?php !defined('IN WEB') AND exit('Access Denied!');

/**
 * 实现类的按需加载

 * @modifier GaifyYang
 * 
 * @version 1.2.0
 */
final class Autoload
{
    protected static $_paths = array( 
	                                  API_PATH,
	                                  MODEL_PATH,
									  CFG_PATH,
                                      SYS_HEP_PATH,
                                      SYS_LIB_PATH,
                                      ADMIN_MODEL_PATH,
                                      ADMIN_CFG_PATH,
                                      WEB_CFG_PATH,
                                      WEB_MODEL_PATH,
                                      PUSH_API_PATH,
                                      );
									  
    protected static $_files    = array();
    protected static $_instance = array();
    public    static $ext       = '.php';
    protected static $_init     = FALSE;
    
    /**
     * 环境变量初始化
     * @return void
     */
    public static function init()
    {
        if(self::$_init)
        {
            return;
        }
        self::$_init = TRUE;
        spl_autoload_register(array('Autoload', 'autoload_load'));
    }
    
    /**
     * 类自动加载器
     * @param {string} $class 类名
     * @return boolean
     */
    public static function autoload_load($class)
    {     
        $pos  = strrpos($class, '_');
        
        $dir = '';
        if($pos > 0)
        {
            $suffix = substr($class, 0, $pos + 1);
            $dir    = str_replace('_', '/', $suffix );
        }

        $dir = strtolower($dir);

        $file = str_replace('_', '.', strtolower($class));

        if($file = Autoload::do_path($dir, $file))
        {
            require($file);
            
            return TRUE;
        }
        
        return FALSE;
    }

    
    public static function do_path($dir, $file)
    {
		$found = false;
        if(strpos($file, 'config') === 0)
        {
        	return Autoload::find_file($dir, CFG_PATH, $file);
        }
        
    	if(strpos($file, 'lib') === 0)
    	{
        	return Autoload::find_file($dir, SYS_LIB_PATH, $file);
        }
        
    	if(strpos($file, 'helper') === 0)
    	{
        	return Autoload::find_file($dir, SYS_HEP_PATH, $file);
        }
        
    	if( $dir == '')
    	{
        	$found = Autoload::find_file($dir, MODEL_PATH, $file);
        	if(!$found)
        	{
        		return Autoload::find_file($dir, API_PATH, $file);
        	}
        }

        foreach(Autoload::$_paths as $path)
        {
        	$foundFile = Autoload::find_file($dir, $path, $file);
        	if($foundFile)
        	{
        		return $foundFile;
        	}
        }

        return $found;        
    }
    
    /**
     * 查找文件
     */
    public  static function find_file($dir, $path,$file, $ext=''){
    	$ext      = $ext ? ".{$ext}" : Autoload::$ext;
    	$path     = str_replace('{module}/', $dir, $path);
    	$filePath = $path . $file . $ext;
        $key      = md5($filePath);
        
        if(isset(Autoload::$_files[$key]))
        {
            return Autoload::$_files[$key];
        }
            
        if(is_file($filePath))
        {         
            Autoload::$_files[$key] = $filePath;
            return $filePath;
        }
        
        return false;
    }
    
    /**
     * 设置查找路径
     * @param {string} $path
     * @return void
     */
    public static function setPath($path)
    {
        if(!in_array($path, Autoload::$_paths))
        {
            Autoload::$_paths[] = $path;
        }
    }
}