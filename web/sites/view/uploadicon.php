<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>UploadiFive Test</title>
<script src="web/sites/js/jquery.uploadify.min.js?v=<?php echo time()?>" type="text/javascript"></script>
<link rel="stylesheet" type="text/css" href="web/sites/css/uploadify.css?v=<?php echo time()?>">
<style type="text/css">
body{font:12px/1.5 Arial, Helvetica, sans-serif;}
ul,li{list-style:none;}
a{text-decoration:none;}
img{border:none;vertical-align:middle;}

/*=========help=======*/
	#wrap1{
		height:357px;_height:355px;
		z-index:999;
		width:496px;
		background:url(statics/web/image/uploadDivBg.png) no-repeat;
		margin:0 auto;
		text-align:center;
		left:250px;
		top:175px;
		position:absolute;
	} 
	#_container { 
		height:252px;
		_height:250px;
		padding-left:15px;
		width:427px;
		background:url(statics/web/image/helpContentDivBg.png) no-repeat; 
	}	 

	#_container #_content{ 
		height:240px;width:400px;float:left;overflow:hidden;
		color:#666666;
	} 
 
	#_container #scroll{ 
		height:200px;width:15px;float:right; 
	}

 
	.b4-2{font-size:0px; height:245px; width:15px; background-image:url(statics/web/image/scrollLine.png);position:relative;} 
	.b4-3{font-size:0px; height:57px; width:17px; background-image:url(statics/web/image/scrollButton.png);  position:absolute;  } 
	
/*=========feedback=======*/
	#_container1 { 
		height:209px;
		_height:207px;
		padding-left:15px;
		width:427px;
		background:url(statics/web/image/feedbackContentDivBg.png) no-repeat; 
	} 

	#_container1 #_content1{
		padding-top:10px;
		padding-bottom:10px;
		height:179px;width:400px;float:left;overflow:hidden;
		color:#666666;
	}  
	#_container1 #scroll1{ 
		height:200px;width:15px;float:right; 
	}

	/*history*/
	#_container2 { 
		height:252px;
		_height:250px;
		padding-left:15px;
		width:427px;
		background:url(statics/web/image/helpContentDivBg.png) no-repeat; 
	} 

	#_container2 #_content2{ 
		height:240px;width:400px;float:left;overflow:hidden;
		color:#666666;
	} 
 
	#_container2 #scroll2{ 
		height:200px;width:15px;float:right; 
	}
</style>
</head>

<body>

<!--帮助弹框-->
<div id="wrap1" >
	<!--关闭按钮-->
	<div style="width:496px;height:21px;">
		<a href="javascript:;" onclick="showWindow()" style="float:right;margin-top:15px;margin-right:15px;width:20px;height:21px;background:url(statics/web/image/helpClose.png) no-repeat" onmouseover="this.style.background='url(statics/web/image/helpClose_inout.png?1=1) no-repeat';" onmouseout="this.style.background='url(statics/web/image/helpClose.png?1=1) no-repeat';"></a>
		<div style="clear:both;"></div>
	</div>
			
	<!--tab切换按钮-->
	<div style="width:496px;height:34px;margin-top:20px;">
		<div style="clear:both;"></div>
	</div> 
			
	<!--帮助面板-->
	<div id="_container" style="margin:0 auto;text-align:center;margin-top:10px;"> 
		<div id="_content" style="text-align:left;margin-top:10px;outline:none;font-size:14px;line-height:25px;"> 
		<h3>请点击按钮选择相片</h3>
		<form>
			<input id="file_upload" name="icon" type="file" multiple="true">
		</form>

		<script type="text/javascript">
			<?php $timestamp = time();?>
			$(function() {
				$('#file_upload').uploadify({
					'formData'     : {
						'timestamp' : '<?php echo $timestamp;?>',
						'token'     : '<?php echo md5('unique_salt' . $timestamp);?>',
						'mid'       : "<?php echo $_REQUEST['mid']?>"
					},
					'swf'      : 'web/sites/js/uploadify.swf',
					'uploader' : '?p=upload&act=uploadicon',
					'buttonText':'浏览……', 
					'fileSizeLimit' : '3000KB',
					'onUploadSuccess' : function(file, data, response) {  
						if(response == 1){
							alert('上传成功！页面自动刷新，请确认！');
							showWindow();
							window.location.reload();
						}else if(response == -1){
							alert('文件格式不对,请重新上传！');
							showWindow();
						}else{
							alert('非法上传！');
							showWindow();
						}
					}
				});
			});

			function showWindow(){
				var mObj = document.getElementById("wrap1");
				var css  = mObj.style.display;
				if(css=="none"){
					mObj.style.display = "block";
					var deom4=new Slider($('_container'),$('block4'),$('scroll4'),{shapechange:false,topvalue:2,bottomvalue:6,border:1}); 
					addListener($('p1'),'click',Bind(deom4,deom4.Left)); 
					addListener($('p2'),'click',Bind(deom4,deom4.Right));
				}else{
					mObj.style.display = "none";
				}
			}
		</script>
		</div> 
		<div style="clear:both;"></div>
	</div>
</div>
</body>
</html>

