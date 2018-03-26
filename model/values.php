<?php !defined('IN WEB') AND exit('Access Denied!');

class Values{
    static function getUserInfo(){ //单记录所有值
        return array(
                    0 => 'mid',
                    1 => 'sid', //站点名
                    2 => 'sitemid', 
                    3 => 'mnick', //用户真实姓名
                    4 => 'vip',
                    5 => 'viptime',
                    6 => 'prop',
                    7 => 'proptime',
                    8 => 'mstatus',
                    9=> 'mactivetime', 
                    10 => 'mentercount',
                    11 => 'mtime',
                    12 => 'invite_uid', 
                    13 => 'sex',
		    		14 => 'hometown',
                    15 => 'bid',
                    16 => 'versions',
                );
    }
    
    static function getGameInfo(){
    	return array(
                    0 => 'mid',
                    1 => 'sid', 
                    2 => 'money',
                    3 => 'level', //等级
                    4 => 'score',//积分
                    5 => 'exp', //经验值
                    6 => 'wintimes',
                    7 => 'losetimes',
                    8 => 'drawtimes',
                    9 =>'bccoins',
                    10=>'freezemoney'
                );
    }
    
    /**
     * 把对应的值压入数组
     */
    static function combine($aKey, $aValue){
        foreach ((array)$aKey as $key => $value){
            $aTemp[$key] = $aValue[$value];
        }
        return $aTemp;
    }
    /**
     * 反转数组
     */
    static function uncombine($aKey, $aValue){
        foreach ((array)$aKey as $key => $value){
            $aTemp[$value] = isset( $aValue[$key] ) ? $aValue[$key] : '';
        }
        return $aTemp;
    }
}