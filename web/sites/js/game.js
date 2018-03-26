var flashVars = {};

/**
 * 生成FLASH参数
 * @return void
 */
function flashVars_init(params)
{
	flashVars.userInfo = params.userInfo;
	flashVars.version  = params.version.swf;
	flashVars.security = params.urlinfo.security;
	flashVars.urlinfo  = params.urlinfo;
	flashVars.gameinfo = params.gameinfo;
	
	flashVars.game = {
	    "option"   : {appId:  '363944990302148', 
		              xfbml:  true, 
					  cookie: true, 
					  oauth:  true
	    			},
		"amf"      : "0",
		"product"  : 0,
		'roomConfig' : flashVars.gameinfo.roomConfig
	};
	
	flashVars.urls = {
		"flash" : params.urlinfo.flashurl,
	    "app"   : params.urlinfo.apiurl,
		"xml"   : {"system": params.urlinfo.xmlurl+"system.data", 
		           "tables": params.urlinfo.xmlurl+"tables.data",
				   "lang"  : params.urlinfo.xmlurl+flashVars.game.lang+".data"}
	};
	
	flashVars.server = {
	    "hall" : [{"ip" : flashVars.gameinfo.flashServerIp, "port" : flashVars.gameinfo.flashServerPort}],
	    "room" : []
	};
}

/**
 * 设置FLASH参数
 *
 * @return void
 */
function setFlashVars(params)
{
    for(var item in params)
	{
	    flashVars[item] = params[item];
	}
}

/**
 * 获取FLASH参数
 * @return object
 */
function getFlashVars()
{
	debug( 'flashvar', flashVars )
    return flashVars;
}

/**
 * 加载FLASH
 * @return void
 */
function load_flash(){
	swfobject.embedSWF(
    	flashVars.urls.flash + 'miniLoading' + flashVars.version.miniLoading + '.swf',
	    "flashBox",
	    "1000",
	    "700",
	    "9.0.0",
	    "expressInstall.swf",
	    {},
	    {
	        "allowScriptAccess": "always",
	        "allowFullScreen": "true",
	        "wmode": "opaque"
	    }
	);
}	
/**
 * FLASH是否安装，和版本
 * @return void
 */
function checkFlash(){
    var hasFlash = false;
    var flashVersion = 0;
    if(window.ActiveXObject){
        var swf = new ActiveXObject('ShockwaveFlash.ShockwaveFlash');
        if(swf){
            hasFlash = true;
            VSwf=swf.GetVariable("$version");
            flashVersion=parseInt(VSwf.split(" ")[1].split(",")[0]);
        }
    }else{
        if(navigator.plugins && navigator.plugins.length > 0){
            var swf = navigator.plugins["Shockwave Flash"];
            if(swf){
                hasFlash = true;
                var words = swf.description.split(" ");
                for(var i=0; i < words.length; ++i){
                	if(isNaN(parseInt(words[i]))) continue;
                    flashVersion = parseInt(words[i]);
                }
            }
        }
    }
    return {f:hasFlash,v:flashVersion};
}

//console.log(flashVars.urls.flash + 'miniloading' + flashVars.version.miniloading + '.swf');