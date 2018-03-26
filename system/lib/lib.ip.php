<?php !defined('IN WEB') AND exit('Access Denied!');

class Lib_Ip
{
    private static $ip     = NULL;

    private static $fp     = NULL;
    private static $offset = NULL;
    private static $index  = NULL;

    private static $cached = array();

    public static function find($ip)
    {
        return 'N/A';
        if (empty($ip) === TRUE)
        {
            return 'N/A';
        }

        $nip   = $ip;
        $ipdot = explode('.', $nip);

        if ($ipdot[0] < 0 || $ipdot[0] > 255 || count($ipdot) !== 4)
        {
            return 'N/A';
        }

        if (isset(self::$cached[$nip]) === TRUE)
        {
            return self::$cached[$nip];
        }
        
        $redis_cached = Loader_Redis::common()->get('ip:'.$nip);
        
        if($redis_cached)
        {
        	return $redis_cached;
        }
        
        if (self::$fp === NULL)
        {
            self::init();
        }

        $nip2 = pack('N', ip2long($nip));

        $tmp_offset = ((int)$ipdot[0] * 256 + (int)$ipdot[1]) * 4;
        $start      = unpack('Vlen', self::$index[$tmp_offset] . self::$index[$tmp_offset + 1] . self::$index[$tmp_offset + 2] . self::$index[$tmp_offset + 3]);

        $index_offset = $index_length = NULL;
        $max_comp_len = self::$offset['len'] - 262144 - 4;
        for ($start = $start['len'] * 9 + 262144; $start < $max_comp_len; $start += 9)
        {
            if (self::$index{$start} . self::$index{$start + 1} . self::$index{$start + 2} . self::$index{$start + 3} >= $nip2)
            {
                $index_offset = unpack('Vlen', self::$index{$start + 4} . self::$index{$start + 5} . self::$index{$start + 6} . "\x0");
                $index_length = unpack('nlen', self::$index{$start + 7} . self::$index{$start + 8});

                break;
            }
        }

        if ($index_offset === NULL)
        {
            return 'N/A';
        }

        fseek(self::$fp, self::$offset['len'] + $index_offset['len'] - 262144);

        self::$cached[$nip] = explode("\t", fread(self::$fp, $index_length['len']));
        
        Loader_Redis::common()->set('ip:'.$nip,self::$cached[$nip],false,true,2*24*3600);//缓存2天

        return self::$cached[$nip];
    }

    private static function init()
    {
   //      if (self::$fp === NULL)
   //      {
   //          self::$ip = new Lib_Ip();
			// $dat      = dirname(__FILE__) . "/ipdata/mydata4vipday2.datx";

   //          self::$fp = fopen($dat, 'rb');
   //          if (self::$fp === FALSE)
   //          {
   //              throw new Exception('Invalid 17monipdb.datx file!');
   //          }

   //          self::$offset = unpack('Nlen', fread(self::$fp, 4));
   //          if (self::$offset['len'] < 4)
   //          {
   //              throw new Exception('Invalid 17monipdb.datx file!');
   //          }

   //          self::$index = fread(self::$fp, self::$offset['len'] - 4);
   //      }
    }

    public function __destruct()
    {
        if (self::$fp !== NULL)
        {
            fclose(self::$fp);
        }
    }
}


