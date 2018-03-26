<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>点乐支付中心</title>
<link rel="stylesheet" type="text/css" href="http://bycdn5-i.akamaihd.net/styles/common.css">

<script>
document.write(window.navigator.userAgent.match(/android/i) ?
		'<meta name="viewport" content="width=device-width,target-densitydpi=high-dpi,initial-scale=0.9, minimum-scale=0.9, maximum-scale=0.9, user-scalable=yes"/>'
		: '<meta name="viewport" content = "width=device-width, initial-scale=1.0, maximum-scale=0.50, user-scalable=no" />');
</script>
<style type="text/css">
body {width: 100%;}
.nav{
	text-align:center;
	height:12%;
	background-image: url('statics/pay/images/top.png');
	background-repeat: no-repeat;
    background-size: cover;   
}
.nav .back{
	position:absolute; left:0; top:0;
}
.nav .center{
	margin:0 auto;font-size:2.8em;color:#FFFFFF
}

.nav .help{
	position:absolute; right:0; top:0;
}

.clear {
	clear:both;
}

.payinfo{
	margin-bottom:1%;
	margin-top:1%;
	margin-left:2%;
	margin-right:2%;
	height:100%;
    border: 1px solid #dedede;
    -moz-border-radius: 15px;      /* Gecko browsers */
    -webkit-border-radius: 15px;   /* Webkit browsers */
    border-radius:15px;            /* W3C syntax */
}

.channel{
    border: 1px solid #dedede;
    -moz-border-radius: 15px;      /* Gecko browsers */
    -webkit-border-radius: 15px;   /* Webkit browsers */
    border-radius:15px;            /* W3C syntax */
	margin-left:2%;
	margin-right:2%;
}

.payinfo .list{
	font-size:1.4em;
	color:#646363;
}

.imgbutton{
	width:120px;  
	height:103px;
}

dl{ 
	text-align:center;
	width:20%; 
	float:left; 
	margin-top:10px;
	font-size:1.2em;
}

@media screen and (max-width:860px) {    
	 dl{ 
		text-align:center;
		width:20%; 
		float:left; 
		margin-top:5px;
	}
	.imgbutton{
		width:110px;   	
		height:103px;
    }
}

@media  screen and (max-width:480px) {    
	dl{  
		text-align:center;
		width:25%; 
		float:left; 
		margin-top:5px;
	}
	.imgbutton{
		width:100px;   	
		height:80px;
    }
}

@media  screen and (max-width:320px) {    
    dl{ 
		text-align:center;
		width:33%; 
		float:left; 
		margin-top:5px;
    	font-size:1em;
	}
	.imgbutton{
		width:60px;   
		height:60px;  	
    }
}
</style>
</head>
<body>
<div class="nav">
	<span class="back" ><a href="#@"><img src="statics/pay/images/back.png"></a></span>
	<span class="center" style="margin-top:3px">点乐支付中心</span>
	<span class="help"><a href="#@"><img src="statics/pay/images/help.png"></a></span>
</div>

<div class="payinfo">
	<ul style="margin-left:1.9%;margin-top:1%;margin-bottom:1%">
		<li class="list">昵称：<?php echo $nick?></li>
		<li class="list">商品：<img src="statics/pay/images/red.png"><span style="color:#FF8A00;margin-left:0.7%"><?php echo $items['payinfo']['name']?></span></li>
		<li class="list">价格：<?php echo $items['payinfo']['price']?></li>
	</ul>
</div>

<div class="channel" style="padding-bottom:0.7%">

	<?php foreach($items['paychannel'] as $item):?>
		<form method="post" id="<?php echo $items['payinfo']['id']?>" action="?m=pay&act=redirect">
			<input type="hidden" value="<?php echo $items['payinfo']['id'] ?>" name="id" />
			<input type='hidden' name='pmode' value="<?php echo $item['pmode'] ?>" />				
			<dl title="<?php echo $item['pname']?>">										
				<dt><input class="imgbutton" type="image" id="" src="statics/pay/images/caifutong.png" ></dt>
				<dd><a href="javascript:;"  title="<?php echo $item['pname']?>"><?php echo $item['pname']?></a></dd>
			</dl>
		</form>		
	<?php endforeach;?>		
   <div class="clear"></div>
</div>


</body>
</html>