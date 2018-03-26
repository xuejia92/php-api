<?php 
!defined('IN WEB') AND exit('Access Denied!');

$time  = time();
$today = date('Y-m-d H:i:s',$time);
$keyday = date('Y-m-d',$time);

$ret = array();
$ret['flag']   = 0;  	//返回值
$ret['messid'] = 0;		//错误号
$ret['money']  = 0;	    //领取金额

//时间控制 超过这个时间不在进行处理
if($time< strtotime("2014-3-11") || $time > strtotime("2014-12-30")){
	echo json_encode($ret);
	exit;
}

//与自身活动相关的参数及一些载入逻辑
$gameid=$_REQUEST['gameid'];
$mid=$_REQUEST['mid'];
$sid=$_REQUEST['sid'];
$cid=$_REQUEST['cid'];
$pid=$_REQUEST['pid'];
$ctype=$_REQUEST['ctype'];

$buttonctype = $_REQUEST['buttonctype'];
$tihao       = $_REQUEST['tihao'];
$oldmoney=$_REQUEST['oldmoney'];

//echo json_encode($ret);
//exit;
//灯谜:
$timu = array(
		"1"=>array(
			"question"=>'双手赞成 （打一体育项目）',
			"A"=>'游泳',
			"B"=>'举重',
			"C"=>'跑步',
			"D"=>'B',
		),
		
		"2"=>array(
			"question"=>'阎王爷不做主 （打一电影）',
			"A"=>'小鬼当家',
			"B"=>'分手大师',
			"C"=>'后会无期',
			"D"=>'A',
		),
		
		"3"=>array(
			"question"=>'明日分离，无人相伴。（打一字）',
			"A"=>'胖',
			"B"=>'瘦',
			"C"=>'腰',
			"D"=>'A',
		),
		
		"4"=>array(
			"question"=>'回身一箭 （打一物理用语）',
			"A"=>'反射',
			"B"=>'摩擦力',
			"C"=>'惯性',
			"D"=>'A',
		),
		
		"5"=>array(
			"question"=>'王 （打一数学用语）',
			"A"=>'负负得正',
			"B"=>'子空间',
			"C"=>'复合不等式',
			"D"=>'A',
		),
		
		"6"=>array(
			"question"=>'禁止叫好 （打一成语）',
			"A"=>'百里挑一',
			"B"=>'兵临城下',
			"C"=>'妙不可言',
			"D"=>'C',
		),
		
		"7"=>array(
			"question"=>'流水落花春去也 （打一节气）',
			"A"=>'夏至',
			"B"=>'冬至',
			"C"=>'大寒',
			"D"=>'A',
		),
		
		"8"=>array(
			"question"=>'男女单打 （打一成语）',
			"A"=>'一决雌雄',
			"B"=>'天上人间',
			"C"=>'掌上明珠',
			"D"=>'A',
		),
		
		"9"=>array(
			"question"=>'2468 （打一成语）',
			"A"=>'百里挑一',
			"B"=>'无独有偶',
			"C"=>'八仙过海',
			"D"=>'B',
		),
		
		
		"10"=>array(
			"question"=>'谢绝还价 （打一成语）',
			"A"=>'不折不扣',
			"B"=>'海阔天空',
			"C"=>'皆大欢喜',
			"D"=>'A',
		),
		
		"11"=>array(
			"question"=>'迎春袭人贾宝玉 （打一成语）',
			"A"=>'花花公子',
			"B"=>'愚公移山',
			"C"=>'龙生九子',
			"D"=>'A',
		),
		
		"12"=>array(
			"question"=>'兔子不吃窝边草 （打一成语）',
			"A"=>'舍近求远',
			"B"=>'三皇五帝',
			"C"=>'水木清华',
			"D"=>'A',
		),
		
		"13"=>array(
			"question"=>'一月又一月，月月紧相连。 （打一字）',
			"A"=>'用',
			"B"=>'远',
			"C"=>'玥',
			"D"=>'A',
		),
		
		"14"=>array(
			"question"=>'一颗心七上八下 （打一歌名）',
			"A"=>'《恋爱ing》',
			"B"=>'《忐忑》',
			"C"=>'《小情歌》',
			"D"=>'B',
		),
		
		"15"=>array(
			"question"=>'一气之下，拂手而去。 （打一字）',
			"A"=>'道',
			"B"=>'感',
			"C"=>'氟',
			"D"=>'C',
		),
		
		"16"=>array(
			"question"=>'秋后赶到 （打一节气）',
			"A"=>'冬至',
			"B"=>'夏至',
			"C"=>'立春',
			"D"=>'A',
		),
		
		"17"=>array(
			"question"=>'小虫有文化，到处嗡嗡飞。 （打一字）',
			"A"=>'蚊',
			"B"=>'蛇',
			"C"=>'鱼',
			"D"=>'A',
		),
		
		"18"=>array(
			"question"=>'一块豆腐，切成四块，放到锅里，盖上锅盖。 （打一字）',
			"A"=>'凶',
			"B"=>'画',
			"C"=>'聊',
			"D"=>'B',
		),
		
		"19"=>array(
			"question"=>'此起彼落 （打一玩具）',
			"A"=>'跷跷板',
			"B"=>'小汽车',
			"C"=>'画板',
			"D"=>'A',
		),
		
		"20"=>array(
			"question"=>'人人有房住 （打一成语）',
			"A"=>'壮志凌云',
			"B"=>'金枝玉叶',
			"C"=>'各得其所',
			"D"=>'C',
		),
		
		"21"=>array(
			"question"=>'夜来风雨声，花落知多少。 （打一成语）',
			"A"=>'落花流水',
			"B"=>'亡羊补牢',
			"C"=>'万里长城',
			"D"=>'A',
		),
		
		"22"=>array(
			"question"=>'百岁挂帅 （打一成语）',
			"A"=>'千军万马',
			"B"=>'大器晚成',
			"C"=>'日日夜夜',
			"D"=>'B',
		),
		
		"23"=>array(
			"question"=>'王白两先生，坐在石头上。 （打一字）',
			"A"=>'碧',
			"B"=>'语',
			"C"=>'名',
			"D"=>'A',
		),
		
		"24"=>array(
			"question"=>'从上往下数，只有人一口。 （打一字）',
			"A"=>'言',
			"B"=>'合',
			"C"=>'夏',
			"D"=>'B',
		),
		
		"25"=>array(
			"question"=>'猜中了 （打一成语）',
			"A"=>'不出所料',
			"B"=>'永无止境',
			"C"=>'石破惊天',
			"D"=>'A',
		),
		
		"26"=>array(
			"question"=>'耳朵长，尾巴短。只吃菜，不吃饭。(打一动物名)',
			"A"=>'狗',
			"B"=>'兔子',
			"C"=>'猫',
			"D"=>'B',
		),
		
		"27"=>array(
			"question"=>'沟里走，沟里串。背了针，忘了线。(打一动物名)',
			"A"=>'刺猬',
			"B"=>'猫',
			"C"=>'狗',
			"D"=>'A',
		),
		
		"28"=>array(
			"question"=>'蓝色之洋 （打一省区名）',
			"A"=>'青海',
			"B"=>'新疆',
			"C"=>'西藏',
			"D"=>'A',
		),
		
		"29"=>array(
			"question"=>'长篇论文 （打一成语）',
			"A"=>'亡羊补牢',
			"B"=>'千言万语',
			"C"=>'六道轮回',
			"D"=>'B',
		),
		
		"30"=>array(
			"question"=>'看似小孩过家家 （打一成语）',
			"A"=>'永无止境',
			"B"=>'塞翁失马',
			"C"=>'视同儿戏',
			"D"=>'C',
		),
		
		"31"=>array(
			"question"=>'口吃 （打一成语）',
			"A"=>'吞吞吐吐',
			"B"=>'画蛇添足',
			"C"=>'一生一世',
			"D"=>'A',
		),
		
		"32"=>array(
			"question"=>'啃书本 （打一成语）',
			"A"=>'咬文嚼字',
			"B"=>'逍遥法外',
			"C"=>'国色天香',
			"D"=>'A',
		),
		
		"33"=>array(
			"question"=>'一心二用 （打一字）',
			"A"=>'地',
			"B"=>'忿',
			"C"=>'天',
			"D"=>'B',
		),
		
		"34"=>array(
			"question"=>'以静制动 （七言唐诗一句）',
			"A"=>'此时无声胜有声',
			"B"=>'四月清和雨乍晴',
			"C"=>'南山当户转分明',
			"D"=>'A',
		),
		
		"35"=>array(
			"question"=>'独唱 （打一成语）',
			"A"=>'逍遥法外',
			"B"=>'自得其乐',
			"C"=>'兵临城下',
			"D"=>'B',
		),
		
		"36"=>array(
			"question"=>'拳术男儿 （打一省会名）',
			"A"=>'武汉',
			"B"=>'深圳',
			"C"=>'上海',
			"D"=>'A',
		),
		
		"37"=>array(
			"question"=>'长生不老 （打一植物）',
			"A"=>'万年青',
			"B"=>'文竹',
			"C"=>'芦荟',
			"D"=>'A',
		),
		
		"38"=>array(
			"question"=>'九千九百九十九 （打一成语）',
			"A"=>'张灯结彩',
			"B"=>'万无一失',
			"C"=>'大江东去',
			"D"=>'B',
		),
		
		"39"=>array(
			"question"=>'千年树无叶 （打一字）',
			"A"=>'固',
			"B"=>'姑',
			"C"=>'枯',
			"D"=>'C',
		),
		
		"40"=>array(
			"question"=>'人能驾云 （打一字）',
			"A"=>'会',
			"B"=>'忐',
			"C"=>'忑',
			"D"=>'A',
		),
		
		"41"=>array(
			"question"=>'华夏之后 （打一节日）',
			"A"=>'春节',
			"B"=>'除夕',
			"C"=>'中秋',
			"D"=>'C',
		),
		
		"42"=>array(
			"question"=>'一口吃掉牛尾巴 （打一字）',
			"A"=>'说',
			"B"=>'告',
			"C"=>'声',
			"D"=>'B',
		),
		
		"43"=>array(
			"question"=>'宜下不宜上 （打一字）',
			"A"=>'且',
			"B"=>'好',
			"C"=>'声',
			"D"=>'A',
		),
		
		"44"=>array(
			"question"=>'男女声合唱（打一成语',
			"A"=>'闻鸡起舞',
			"B"=>'四面楚歌',
			"C"=>'异口同声',
			"D"=>'C',
		),
		
		"45"=>array(
			"question"=>'爱听老公唱一曲（打一歌名）',
			"A"=>'好汉歌',
			"B"=>'你是我的眼',
			"C"=>'同桌的你',
			"D"=>'A',
		),
		
		"46"=>array(
			"question"=>'不问明白不罢休（打一昆虫）',
			"A"=>'知了',
			"B"=>'蛐蛐',
			"C"=>'蜻蜓',
			"D"=>'A',
		),
		
		"47"=>array(
			"question"=>'早晨发生大地震 （打一成语）',
			"A"=>'毁于一旦',
			"B"=>'杏雨梨云',
			"C"=>'大江东去',
			"D"=>'A',
		),
		
		"48"=>array(
			"question"=>'张飞钻进树洞里（打一成语）',
			"A"=>'一丝不挂',
			"B"=>'英雄无用武之地',
			"C"=>'美轮美奂',
			"D"=>'B',
		),
		
		"49"=>array(
			"question"=>'坠入黑暗 （打一成语）',
			"A"=>'赤子之心',
			"B"=>'龙凤呈祥',
			"C"=>'下落不明',
			"D"=>'C',
		),
		
		"50"=>array(
			"question"=>'拍一个巴掌（打一地名）',
			"A"=>'五指山',
			"B"=>'华山',
			"C"=>'岳山',
			"D"=>'A',
		),
		
		"51"=>array(
			"question"=>'两兄弟，手拉手，一个转，一个留（打一文具用品）',
			"A"=>'橡皮',
			"B"=>'圆规',
			"C"=>'铅笔',
			"D"=>'B',
		),
		
		"52"=>array(
			"question"=>'草上飞（打一字）',
			"A"=>'中',
			"B"=>'晚',
			"C"=>'早',
			"D"=>'C',
		),
		
		"53"=>array(
			"question"=>'中央一条狗，上下四个口（打一字）',
			"A"=>'器',
			"B"=>'哭',
			"C"=>'骂',
			"D"=>'A',
		),
		
		"54"=>array(
			"question"=>'五句话（打一成语）',
			"A"=>'迫在眉睫',
			"B"=>'三言两语',
			"C"=>'人来人往',
			"D"=>'B',
		),
		
		"55"=>array(
			"question"=>'十个哥哥（打一字）',
			"A"=>'兄',
			"B"=>'棵',
			"C"=>'克',
			"D"=>'C',
		),
		
		"56"=>array(
			"question"=>'多一半（打一字）',
			"A"=>'夕',
			"B"=>'少',
			"C"=>'且',
			"D"=>'A',
		),
		
		"57"=>array(
			"question"=>'牛头马面挂两边(打一口语)',
			"A"=>'呀,好厉害',
			"B"=>'装神弄鬼,想吓唬谁',
			"C"=>'吃饭了吗',
			"D"=>'B',
		),
		
		"58"=>array(
			"question"=>'二十四小时(打一成语)',
			"A"=>'春暖花开',
			"B"=>'相亲相爱',
			"C"=>'一朝一夕',
			"D"=>'C',
		),
		
		"59"=>array(
			"question"=>'谋一半，勇一半（猜一字）',
			"A"=>'诵',
			"B"=>'语',
			"C"=>'说',
			"D"=>'A',
		),
		
		"60"=>array(
			"question"=>'中餐，粒粒皆辛苦(打一成语)',
			"A"=>'峰回路转',
			"B"=>'点点滴滴',
			"C"=>'新亭对泣',
			"D"=>'B',
		),
		
		
		"61"=>array(
			"question"=>'书签(猜一字)',
			"A"=>'蛋',
			"B"=>'掌',
			"C"=>'颊',
			"D"=>'C',
		),
		
		"62"=>array(
			"question"=>'千里通电话(打一成语)',
			"A"=>'遥相呼应',
			"B"=>'迫不及待',
			"C"=>'安居乐业',
			"D"=>'A',
		),
		
		"63"=>array(
			"question"=>'一块变九块(打一成语)',
			"A"=>'外强中干',
			"B"=>'四分五裂',
			"C"=>'人浮于事',
			"D"=>'B',
		),
		
		"64"=>array(
			"question"=>'节日的焰火(打一成语)',
			"A"=>'尽善尽美',
			"B"=>'金刚怒目',
			"C"=>'五彩缤纷',
			"D"=>'C',
		),
		
		"65"=>array(
			"question"=>'伞兵(打一成语)',
			"A"=>'从天而降',
			"B"=>'车水马龙',
			"C"=>'目无全牛',
			"D"=>'A',
		),
		
		"66"=>array(
			"question"=>'打边鼓(打一成语)',
			"A"=>'坐以待毙',
			"B"=>'旁敲侧击',
			"C"=>'行将就木',
			"D"=>'B',
		),
		
		"67"=>array(
			"question"=>'鲁达当和尚(打一成语)',
			"A"=>'尽善尽美',
			"B"=>'粉墨登场',
			"C"=>'半路出家',
			"D"=>'C',
		),
		
		"68"=>array(
			"question"=>'美梦(打一成语)',
			"A"=>'好景不长',
			"B"=>'东山再起',
			"C"=>'重见天日',
			"D"=>'A',
		),
		
		"69"=>array(
			"question"=>'内里有人(打一字)',
			"A"=>'肉',
			"B"=>'内',
			"C"=>'外',
			"D"=>'A',
		),
		
		"70"=>array(
			"question"=>'上下一体 (打一字)',
			"A"=>'化',
			"B"=>'卡',
			"C"=>'咯',
			"D"=>'B',
		),
		



);

$names = array(
	"SCH-I959",
	"HUAWEI C8813Q",
	"HUAWEI C8813",
	"SCH-I739",
	"HUAWEI C8812",
	"HUAWEI C8815",
	"SCH-I869",
	"GT-I9300",
	"SM-N9009",
	"GT-I9100",
	"GT-N7100",
	"HUAWEI A199",
	"GT-I9500",
	"HUAWEI C8812E",
	"HUAWEI C8813D",
	"SCH-I619",
	"SCH-I829",
	"SCH-I879",
	"HTC T328d",
	"SCH-I779",
	"C8650",
	"HS-E820",
	"SCH-W999",
	"SCH-i929",
	"SCH-N719",
	"SCH-W2013",
	"SCH-i509",
	"ZTE N880E",
	"SCH-W899",
	"SCH-I939",
	"HUAWEI G610-C00",
	"GT-N7000",
	"GT-I8552",
	"SCH-I699",
	"ZTE N909",
	"HUAWEI Y300C",
	"GT-I9152",
	"GT-S7562",
	"GT-S7572",
	"SCH-i909",
	"GT-S7568",
	"ONDA MID",
	"GT-S7562i",
	"HUAWEI C8650+",
	"SCH-I759",
	"ZTE V889D",
	"GT-S5830i",
	"GT-I8262D",
	"GT-I9220",
	"GT-I9308",
	"abc'siphone" ,
	"Amity的iPhone" ,
	"bai的IPHONE" ,
	"bdbd117" ,
	"BMWiPhone" ,
	"iphone5" ,
	"iphone" ,
	"LEiPhone" ,
	"Lenovo" ,
	"lenvov" ,
	"leo" ,
	"LEO" ,
	"LeoChen" ,
	"Leon" ,
	"lgalove" ,
	"LGCiPhone" ,
	"MyiPhone" ,
	"李大海的iPhone" ,
	"33c8888",
	"610",
	"6.22",
	"701",
	"acer",
	"Acer",
	"admin",
	"Administrator",
	"AiPhone",
	"Alan",
	"apple",
	"Apple",
	"AppleiPhone",
	"applestore",
	"asus",
	"ausu",
	"Avenger’siPodTouch4",
	"BR",
	"CC",
	"dashuai",
	"dell",
	"Dell",
	"dj",
	"Drew",
	"drivepro",
	"e",
	"E909C",
	"E909C’siPhone",
	"E909C的iPhon...(4.3.3",
	"EROS",
	"Hs",
	"HT",
	"iPad",
	"iphone",
	"iPhone",
	"iPhone3",
	"iPhone3GS",
	"iphone4",
	"iPhone4",
	"iPhone4s",
	"iPhone4S",
	"iphone5.01",
	"iPhone君",
	"iPhone沈洋",
	"iPod",
	"iPodtouch",
	"Jiiniphone",
	"iphone",
	"iphone4",
	"iphone5.01",
	"Jiiniphone",
	"Vishal'siphone",
	"AiPhone",
	"AppleiPhone",
	"Avenger’siPodTouch4",
	"E909C’siPhone",
	"E909C的iPhon...(4.3.3",
	"iPad",
	"iPhone",
	"iPhone3",
	"iPhone3GS",
	"iPhone4",
	"iPhone4s",
	"iPhone4S",
	"iPhone君",
	"iPhone沈洋",
	"iPod",
	"iPodtouch",
	"刘烽的iPhone",
	"斯iPhone4s",
	"apple",
	"applestore",
	"sttxapple",
	"Apple",
	"AppleiPhone",
	"jj",
	"Jkdch",
	"JOE",
	"john",
	"jx",
	"K",
	"KJtx",
	"Kyle",
	"lenovo",
	"lightning",
	"liwq2",
	"LJY",
	"lzm",
	"mac",
	"Mac",
	"Marvin",
	"Mike",
	"ml",
	"my",
	"NokLok",
	"Peter",
	"Q421975019",
	"Rinky",
	"rock",
	"StevenZhang",
	"sttxapple",
	"sunlinsong",
	"Think",
	"Tom",
	"tyll",
	"T樣",
	"user",
	"User",
	"iPhone君",
	"iPhone沈洋",
	"iPod",
	"iPodtouch",
	"Jiiniphone",
	"iphone",
	"iphone4",
	"iphone5.01",
	"Jiiniphone",
	"Vishal'siphone",
	"AiPhone",
	"AppleiPhone",
	"Avenger’siPodTouch4",
	"E909C’siPhone",
	"E909C的iPhon...(4.3.3",
	"iPad",
	"iPhone",
	"iPhone3",
	"iPhone3GS",
	"iPhone4",
	"iPhone4s",
	"iPhone4S",
	"iPhone君",
	"iPhone沈洋",
	"刘伟",
	"刘烽的iPhone",
	"北斗星的爱",
	"卓越通讯",
	"叶",
	"々司仪-子鱼々",
	"咸龙",
	"哥的神器",
	"壮",
	"大润发",
	"奢求，爱你",
	"婷",
	"孙韩",
	"小丹",
	"小康",
	"小景",
	"小晶晶",
	"小马",
	"张华",
	"张明军",
	"徐立立",
	"振鹏",
	"救救小熊吧",
	"斯iPhone4s",
	"易永胜",
	"晓东的iPhone",
	"曹铭",
	"曾斌其",
	"月少很忙",
	"李立",
	"杜先生",
	"来友通讯",
	"欧阳",
	"江建峰",
	"沉鱼儿小姐",
	"泽冗iPhone",
	"混沌之风",
	"温永玺",
	"潘帅",
	"爱3G永中iPhone",
	"王亮",
	"王现琨",
	"王虎",
	"王鹏的iPhone",
	"珈玥的iphone4s",
	"iPhone君",
	"iPhone沈洋",
	"iPod",
	"iPodtouch",
	"Jiiniphone",
	"iphone",
	"iphone4",
	"iphone5.01",
	"Jiiniphone",
	"Vishal'siphone",
	"AiPhone",
	"AppleiPhone",
	"Avenger’siPodTouch4",
	"E909C’siPhone",
	"E909C的iPhon...(4.3.3",
	"iPad",
	"iPhone",
	"iPhone3",
	"iPhone3GS",
	"iPhone4",
	"iPhone4s",
	"iPhone4S",
	"iPhone君",
	"iPhone沈洋",
	"随便",
	"难得赢",
	"韩成锁",
	"顺利",
	"飞",
);
$temp2 	= rand(0,265);
$mnick  = $names[$temp2];

$s = array(
		"1"=>"博学多才,聪明伶俐猜中",
		"2"=>"见多识广，才高八斗猜中",
		"3"=>"德才兼备，卓尔不群猜中",
);
$t = rand(1,3);
$st = rand(1,100);
if($st >= 85 && $st<=90){
	//$msg      = "系统消息:恭喜".$mnick.$s[$t]."5道灯谜,总共收获5000金币！";//".$mnick."
	//Loader_Tcp::callServer2Horn()->setHorn($msg,$gameid,10);//type  100 表示发到全部游戏
}else if($st >= 91 && $st<=93){
	//$msg      = "系统消息:恭喜".$mnick.$s[$t]."8道灯谜,总共收获8000金币！";//".$mnick."
	//Loader_Tcp::callServer2Horn()->setHorn($msg,$gameid,10);//type  100 表示发到全部游戏
}else if($st >= 94 && $st<=95){
	//$msg      = "系统消息:恭喜".$mnick.$s[$t]."10道灯谜,总共收获10000金币！";//".$mnick."
	//Loader_Tcp::callServer2Horn()->setHorn($msg,$gameid,10);//type  100 表示发到全部游戏
}


//用户牌局信息
$paijuinfo="y";
//获取用户的牌局信息
if($gameid==1){
	$paijuinfo = Loader_Redis::common()->hGet("sh_winstreak",$mid);
	//$paijuinfo="1010101111110110011111111011111111111111111111111111111111111111";
}else if($gameid==3){
	$paijuinfo = Loader_Redis::rank(3)->hGet("lc_winstreak",$mid);
	//$paijuinfo="10101011111101100111111110111111111111111111111111111111111111";
}else if($gameid==4){
	$paijuinfo = Loader_Redis::rank(4)->hGet("bf_winstreak",$mid);
	//$paijuinfo="10101011111101100111111110111111111111111111111111111111111";
}
if(!$paijuinfo){
	$paijuinfo="y";
}

//分析牌局信息得到当前连赢局数 和 最高连赢次数
$lianyin = 0;
$maxlianyin = 0;
$paijushu   = 0;
if($paijuinfo && $paijuinfo!="y")
{
	
	$paijushu = strlen($paijuinfo);
	$length = strlen($paijuinfo);
	for($i=0;$i<$length;$i++)
	{
		$tmp = substr($paijuinfo,$i,1);
		
		if($tmp == 0){
			if($lianyin>$maxlianyin){
				$maxlianyin = $lianyin;
			}
			$lianyin = 0;
		}else if($tmp==1){
			$lianyin = $lianyin+1;
		}
	}
	
	if($lianyin>$maxlianyin){
		$maxlianyin = $lianyin;
	}
}


//查看是否已经领取
//可以答题的数目
$mytimuNum = 5;
if($paijushu>=5){
$mytimuNum = 10;
}

//已经答题的数目
$handAnswer = Loader_Redis::common()->get("ZQDT_".$mid."_".$gameid."_".$keyday);
$handAnswerNum = 0;
$handAnswerTihao = array();
if($handAnswer){
 $handAnswerTihao = explode("|",$handAnswer);
 
 $handAnswerNum   = count($handAnswerTihao) - 1;
}

if($handAnswerNum>=$mytimuNum)//
{
	//今日答题数目已经超过
	if($mytimuNum == 10){
		$ret['messid'] = 3;
		$ret['money']  = $handAnswerNum;
		echo json_encode($ret);
		exit;
	}else{
		$ret['messid'] = 4;
		$ret['money']  = $handAnswerNum;
		echo json_encode($ret);
		exit;
	}
}

//2.查看是否已经答对题目
$answer = -1;

if($buttonctype==0){
	//请先答题
	$ret['messid'] = 2;
	echo json_encode($ret);
	exit;
}else if($buttonctype == 1){
	$answer = "A";
}else if($buttonctype == 2){
	$answer = "B";
}else if($buttonctype  == 3){
	$answer = "C";
}

 

if($answer == -1){
	//所选的选项 不在可选范围之内
	echo json_encode($ret);
	exit;
}

//0.题目回答错误 1.题目回答正确
$ROE = 0;
if($answer == $timu[$tihao]['D']){
	$ROE = 1;
}


if($ROE == 0){
	//题目答错 提示正确的应该是  以及下一题的题号。
	$ret['messid'] = 1;
	$ret['titleError'] = $timu[$tihao]['question'];
	$ret['titleAnswer'] = $timu[$tihao][$timu[$tihao]['D']];
	
	$handAnswer = $handAnswer.$tihao."|";
	Loader_Redis::common()->set("ZQDT_".$mid."_".$gameid."_".$keyday,$handAnswer);
	
	$tmpTihao = rand(1,70);
	while(in_array($tmpTihao,$handAnswerTihao))
	{
		$tmpTihao = rand(1,70);
	}
	
	$ret['questTittle'] = $timu[$tmpTihao]['question'];
	$ret['questA'] = 'A:'.$timu[$tmpTihao]['A'];
	$ret['questB'] = 'B:'.$timu[$tmpTihao]['B'];
	$ret['questC'] = 'C:'.$timu[$tmpTihao]['C'];
	$ret['tihao'] = $tmpTihao;
	$ret['canTiNum'] = $mytimuNum-$handAnswerNum-1;
	echo json_encode($ret);
	exit;
}

//三项任务进展状态 0.未达到领取条件 1.可以领取. 2.已经领取
$canLingqu = 0;
$jianglimoney = 1000;
	






$handAnswer = $handAnswer.$tihao."|";
Loader_Redis::common()->set("ZQDT_".$mid."_".$gameid."_".$keyday,$handAnswer);
	
//发货
//标记领取
Logs::factory()->addWin($gameid,$mid,15,$sid, $cid, $pid,$ctype,0,$jianglimoney,$desc='zqdti');
$ret['money']  = $jianglimoney;

//********发喇叭********//
$OKkey = "ZQDTOK_".$mid."_".$gameid."_".$keyday;
$OK = Loader_Redis::common()->get($OKkey);
if(!$OK){
	$OK = 0;
}
Loader_Redis::common()->set($OKkey,($OK+1));

$userinfo = Member::factory()->getUserInfo($mid,false);
$mnick    = $userinfo['mnick'];
$mnick    = trim($mnick);
$mnick = $mnick ? $mnick : "GT-I9100";
//if($OK+1 == 8){
//	$msg      = "系统消息:恭喜".$mnick.$s['2']."8道灯谜,总共收获8000金币！";//".$mnick."
//	Loader_Tcp::callServer2Horn()->setHorn($msg,$gameid,10);//type  100 表示发到全部游戏
//}else 

if($OK+1 == 10){
	$msg      = "系统消息:恭喜".$mnick.$s[$t]."10道灯谜,总共收获10000金币！";//".$mnick."
	Loader_Tcp::callServer2Horn()->setHorn($msg,$gameid,10);//type  100 表示发到全部游戏
}
//********发喇叭结束********//

//生成新的题目
$handAnswerTihao = explode("|",$handAnswer);
$tmpTihao = rand(1,70);
while(in_array($tmpTihao,$handAnswerTihao))
{
	$tmpTihao = rand(1,70);
}

//返回结果
$ret['questTittle'] = $timu[$tmpTihao]['question'];
$ret['questA'] = 'A:'.$timu[$tmpTihao]['A'];
$ret['questB'] = 'B:'.$timu[$tmpTihao]['B'];
$ret['questC'] = 'C:'.$timu[$tmpTihao]['C'];
$ret['tihao'] = $tmpTihao;
$ret['flag'] = 1;
$ret['canTiNum'] = $mytimuNum-$handAnswerNum-1;

//上报数据
//Loader_Udp::stat()->sendData(65,$mid,$ctype,$cid,$sid,$pid,'');
echo json_encode($ret);
