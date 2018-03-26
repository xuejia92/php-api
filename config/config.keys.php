<?php

!defined('IN WEB') AND exit('Access Denied!');

class Config_Keys {

    //sitemid和mid的转换
    public static function sitemid2mid($sitemid, $sid) {
        return 'UMID' . $sitemid . '|' . $sid;
    }

    //取用户信息（userinfo表）
    public static function getUserInfo($mid) {
        return 'UCNF' . $mid;
    }

    //取游戏信息（gameinfo表）
    public static function getGameInfo($mid) {
        return 'GNF' . $mid;
    }

    //金币流通缓存
    public static function winlog($mid) {
        return 'WL-A' . $mid;
    }

    //版本控制缓存
    public static function versions($gameid, $cid, $ctype) {
        return "VSN|{$gameid}|{$cid}|{$ctype}";
    }

    //公告缓存
    public static function notice($gameid, $cid, $ctype) {
        return "NTE|{$gameid}|{$cid}|{$ctype}";
    }

    //商城列表缓存
    public static function shopList($ctype) {
        return "SPL|" . $ctype;
    }

    //金币wmode列表缓存
    public static function wmode() {
        return "WMD";
    }

    //用户 redis hash key 用于储蓄用户的其它信息
    public static function other($mid) {
        return "OTE|" . $mid;
    }

    //次数限制
    public static function timeLimit($type, $mid) {
        return "ULT|" . $mid . '|' . $type;
    }

    //验证码
    public static function codeCheck($phoneNo) {
        return "CDC|" . $phoneNo;
    }

    //账号类型缓存
    public static function accountType() {
        return "ACT";
    }

    //用户名缓存
    public static function userName() {
        return "USN";
    }

    //手机号缓存
    public static function phoneNo() {
        return "PHN";
    }

    //渠道缓存
    public static function channel() {
        return "CHN";
    }

    //客户端包缓存
    public static function pack() {
        return "PAK";
    }

    //反馈缓存
    public static function feedback($gameid, $mid) {
        return "FBK|$gameid|$mid";
    }

    //支付渠道缓存
    public static function paychannel($id) {
        return "PCL|$id";
    }

    //房间配置缓存
    public static function roomConfig($id) {
        return "ShowHand_RoomConfig:" . $id;
    }

    //斗地主房间配置缓存
    public static function landLordroomConfig($id) {
        return "LandLord_RoomConfig:" . $id;
    }

    //金花房间配置缓存
    public static function flowerRoomConfig($id) {
        return "Flower_RoomConfig:" . $id;
    }

    //斗牛房间配置
    public static function bullfightRoomConfig($id) {
        return "BullFight_RoomConfig:" . $id;
    }

    //炸金牛
    public static function flyBullRoomConfig($id) {
        return "FryBull_RoomConfig:" . $id;
    }

    //捕鱼房间配置
    public static function fishRoomConfig($id) {
        return "Fish_RoomCfg:" . $id;
    }

    //德州扑克
    public static function texasRoomConfig($id) {
        return "Texas_RoomConfig:" . $id;
    }

    //会员VIP
    public static function vip($mid) {
        return "VIP|" . $mid;
    }

    //推送的机器码等信息
    public static function pushinfo() {
        return "PIFO";
    }

    //推送的MID
    public static function pushmid() {
        return "PMID";
    }

    //已经推送过的用户MID
    public static function pushed() {
        return "PHED";
    }

    //封号
    public static function banAccount($mid) {
        return "BAT|" . $mid;
    }

    //实时统计
    public static function stat($date, $itemid) {
        return "UST|" . $date . "|" . $itemid;
    }

    //力美广告
    public static function ad() {
        return "LIMEIO";
    }

    //多盟
    public static function domob() {
        return "DOMOB";
    }

    //果盈
    public static function guoying() {
        return "GUOYING";
    }

    //有米广告
    public static function youmi() {
        return "YOUMI";
    }

    //排行榜
    public static function rank($gameid, $type) {
        return 'RNK|' . $gameid . '|' . $type;
    }

    //任务配置
    public static function taskconfig() {
        return "ShowHand_RoomConfig:1";
    }

    //保存android的device_no
    public static function androidKey($gameid) {
        return "ANDROIDKEY|$gameid";
    }

    //某段时间内注册的用户队列
    public static function registerMid() {
        return "U_REGISTER";
    }

    //禁言redis
    public static function gaghash() {
        return "gaghash";
    }

    //防沉迷在玩时长
    public static function onlinetimehash() {
        return "onlinetimehash";
    }

    //防沉迷身份验证通过的信息
    public static function verifyhash() {
        return "verifyhash";
    }

    //防沉迷开关
    public static function swicthVerify() {
        return "swicthVerify";
    }

    //某段时间内注册的用户队列(去重)
    public static function registerMidNew($day) {
        return "U_REGISTERNEW_" . $day;
    }

    //某段时间内活跃的用户队列(去重)
    public static function activeMid($day) {
        return "U_ACTIVE_" . $day;
    }

    //某段时间内的机器码
    public static function deviceList($gameid) {
        $date = date("Y-m-d", NOW);
        return "U_DEVICES|$gameid|" . $date;
    }

    //用于商城版本控制的KEY
    public static function shopVersions() {
        return 'VERKEY';
    }

    //昨日赚金榜
    public static function maxwinonecoin() {
        return "wincoin";
    }

    //昨日赚金榜-上一次记录
    public static function onecoinhash() {
        return "coinhash";
    }

    //财富排行榜
    public static function wealth() {
        return "wealth";
    }

    //财富排行榜-上一次记录
    public static function wealthhash() {
        return "wealthhash";
    }

    //炸金花财富排行榜
    public static function flowealth() {
        return "flowerwealth";
    }

    //炸金花财富排行榜-上一次记录
    public static function flowealthhash() {
        return "flowerwealthhash";
    }

    //炸金花今日赚金榜
    public static function flomaxwinonecoin() {
        return "flowerwincoin";
    }

    //炸金花今日赚金榜-上一次记录
    public static function floonecoinhash() {
        return "flowercoinhash";
    }

    //账号隔离
    public static function accountDisconnector($mid) {
        return "ADT|$mid";
    }

    //下载其它游戏奖励
    public static function downloadReward() {
        return "DRD";
    }

    //下载娱乐场资源标志
    public static function drf($mid) {
        return "DRF|" . $mid;
    }

    //用户中心
    public static function userServer($mid) {
        return "User:" . $mid;
    }

    //力美广告扣量
    public static function cutdown() {
        return "cutdown";
    }

    //已经计算待推送用户的标志
    public static function calpushflag($id) {
        return "CPF|$id";
    }

    //计算符合条件的推送用户
    public static function topush($id, $ptype, $gameid, $ctype, $cid) {
        return "$id|$ptype|$gameid|$ctype|$cid";
    }

    //符合推送的key
    public static function pushconfig() {
        return "PCG";
    }

    //推送进程数
    public static function forkps() {
        return "FOK";
    }

    //推送送金币的数量
    public static function pushReward() {
        return "PWD";
    }

    //单台设备绑定限制
    public static function bingLimit() {
        return "BLT";
    }

    //发送短信队列
    public static function msglist() {
        return "MGT";
    }

    //日注册用户记录
    public static function dayreg($gameid, $date) {
        return "DRG|$date|$gameid";
    }

    //五星评价活动已经领奖的名单
    public static function stars5($gameid) {
        return "SA5|$gameid";
    }

    //游客绑定点乐账号或手机号码的sitemid
    public static function bangdingRecord() {
        return "BRD";
    }

    //游客绑定点乐账号或手机号码的sitemid
    public static function bangdingwx() {
        return "WX";
    }

    //游客注册账号个数
    public static function guestreglimit($gameid) {
        return "GLT|$gameid";
    }

    //百家乐桌子状态
    public static function baccstatus() {
        return "ServerTable1";
    }

    //连胜的排行榜
    public static function winstreak() {
        return "winstreak";
    }

    //连胜排行棒-上一次记录
    public static function winhash() {
        return "winhash";
    }

    //累计赢金币排行榜
    public static function maxwincoin() {
        return "maxwincoin";
    }

    //累计赢金币排行榜-上一次记录
    public static function coinhash() {
        return "coinhash";
    }

    //红包索取者累计
    public static function asker($mid, $gameid) {
        return "ASK|$mid|$gameid";
    }

    //分配红包给用户
    public static function give2wallet($gameid) {
        return "GWT|$gameid";
    }

    //赚金榜
    public static function realcoin() {
        return "realcoin";
    }

    //赚金榜趋势
    public static function realhash() {
        return "realhash";
    }

    //慈善榜
    public static function charity() {
        return "charity";
    }

    //发红包队列	
    public static function walletList() {
        return "WLT";
    }

    //道具（天数）
    public static function props($gameid, $mid) {
        return "PRP|$gameid|$mid";
    }

    //已经完成了几局（局数任务）
    public static function taskhash() {
        return "taskhash";
    }

    //局数任务领取标志
    public static function taskcomhash() {
        return "taskcomhash";
    }

    //抽奖奖品奖池数量限制
    public static function pond($prize_id) {
        return "POD|$prize_id";
    }

    //大奖时间间隔
    public static function bonusTimeLimit($prize_id) {
        return "BTT|$prize_id";
    }

    //讨红包机会消耗
    public static function askWalletlimit($mid) {
        return "ALT|$mid";
    }

    //连续登陆奖领取标志（用以区分低版本的连续登陆奖与大转盘）
    public static function loginReward($mid) {
        return "LOD|$mid";
    }

    //限制每天同一个账号在多个游戏中只能生成一次大转盘概率
    public static function lgm($mid) {
        return "LGM|$mid";
    }

    //用来保存抢到大额红包后发喇叭的mid
    public static function tosend($mid) {
        return "TOS|$mid";
    }

    //保存翻翻乐的游戏信息
    public static function ffl($mid) {
        return "FFL|$mid";
    }

    //保存翻翻乐的游戏输赢金币数
    public static function fflcoin() {
        return "FFC";
    }

    //翻翻乐总池
    public static function ffa() {
        return "FFA";
    }

    //斗牛百人场壮家状态
    public static function serverBullTable() {
        return "ServerBullTable81";
    }

    //德州百人场壮家状态
    public static function serverTexasTable() {
        return "ServerTexasTable130";
    }

    //炸金花百人场壮家状态
    public static function serverFlowerTable() {
        return "ServerFlowerTable25";
    }

    //龙虎斗
    public static function serverDragonTable() {
        return "ServerDragonTable10";
    }

    //保存游戏版本
    public static function versionData() {
        return "VDA";
    }

    //保存登陆玩家IP分布
    public static function loginIp($gameid, $ctype, $date) {
        return "LIP|$gameid|$ctype|$date";
    }

    //保存付费玩家IP分布
    public static function payIp($gameid, $ctype) {
        return "PIP";
    }

    //翻翻乐大奖控制（五花牛，五小牛）
    public static function ffllimit($cardType) {
        return "FLT|$cardType";
    }

    //限时抢宝箱
    public static function limitqiang($gameid, $mid) {
        return "LQG|$gameid|$mid";
    }

    //首充控制
    public static function firstpay($mid) {
        return "FIP|$mid";
    }

    //机器码注册,IP注册排行榜
    public static function devicerank($type) {
        return "DEK|" . $type;
    }

    //机器码注册,每日登陆排行
    public static function deviceRankByDate($date, $type) {
        return "DEKD|" . $date . "|" . $type;
    }

    //资产分布
    public static function logMemory($date, $gameid) {
        return "LMY|" . $date . "|" . $gameid;
    }

    //短代支付
    public static function msgPay($gameid) {
        return "MGP|" . $gameid;
    }

    //短信支付每个包的支付渠道
    public static function msgPayPid($pid) {
        return "MGPP|" . $pid;
    }

    //短信支付渠道省份屏蔽
    public static function msgPayProvince($pmode) {
        return "MGPC|" . $pmode;
    }

    //短信支付渠道省份屏蔽2
    public static function msgPayProvinceBlocked($gameid, $pmode) {
        return "MSGPC|" . $gameid . "|" . $pmode;
    }

    //翻牌保存初始化幸运指数
    public static function initLuckyPoint($mid) {
        return "ILK|" . $mid;
    }

    //批量更新机器码队列
    public static function deviceUpdateList() {
        return "DEL";
    }

    //按日期统计每种支付方式的收入
    public static function payStat($date) {
        return "PST|" . $date;
    }

    //月报表key
    public static function monthData($gameid) {
        return "MTD|" . $gameid;
    }

    //验证码key
    public static function captchas($deviceno) {
        return "CPA|" . $deviceno;
    }

    //开关控制
    public static function optswitch() {
        return "SWITCH";
    }

    //捕鱼炮样式
    public static function gunStyle($mid, $style) {
        return "GS|" . $mid . "|" . $style;
    }

    public static function sau($groupID) {
        return "SAU|" . $groupID;
    }

    //金币流水log队列
    public static function winloglist($gameid) {
        return "WNLT|" . $gameid;
    }

    //连接登陆5天以上的用户
    public static function login5day() {
        $d = date("Ymd", NOW);
        return "LF5|" . $d;
    }
}
