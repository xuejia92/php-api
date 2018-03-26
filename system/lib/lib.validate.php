<?php !defined('IN WEB') AND exit('Access Denied!');
/**
 * 显示图片并且返回验证码
 * 先要打开SESSION,生成的SESSION为: $_SESSION['idcode']
 */
class Lib_Validate{
	/*******************************************************
	**FILENAME: verify_image.php
	**COPYRIGHT: NONE! (但请保留此信息)
	**AUTHOR: vsFree.Com
	**DATE: 2007-08-08
	********************************************************/
	
	var $mode;  //1：数字模式，2：字母模式，3：数字字母模式，其他：数字字母优化模式
	var $v_num;  //验证码个数
	var $img_w;  //验证码图像宽度
	var $img_h;  //验证码图像高度
	var $int_pixel_num;  //干扰像素个数
	var $int_line_num;  //干扰线条数
	var $font_dir;   //字体文件相对路径
	var $border;   //图像边框
	var $borderColor;  //图像边框颜色
	var $v_code; //验证码值
	
	function validate($mode,$v_num,$img_w,$img_h,$int_pixel_num,$int_line_num,$font_dir='./fonts',$border=true,$borderColor='65,105,225',$v_code=''){	
		$this->mode = $mode;
		$this->v_num = $v_num;
		$this->img_w = $img_w;
		$this->img_h = $img_h;
		$this->int_pixel_num = $int_pixel_num;
		$this->int_line_num = $int_line_num;
		$this->font_dir = $font_dir;
		$this->border = $border;
		$this->borderColor = $borderColor;
		$this->v_code = $v_code;
		$this->GenerateImage();
	}
	
	function GetChar($mode){
		if($mode == "1"){
			$ychar = "0,1,2,3,4,5,6,7,8,9";
		}else if($mode == "2"){
			$ychar = "A,B,C,D,E,F,G,H,I,J,K,L,M,N,O,P,Q,R,S,T,U,V,W,X,Y,Z";
		}else if($mode == "3"){
			$ychar = "0,1,2,3,4,5,6,7,8,9,A,B,C,D,E,F,G,H,I,J,K,L,M,N,O,P,Q,R,S,T,U,V,W,X,Y,Z";
		}else{
			$ychar = "3,4,5,6,7,8,9,A,B,C,D,H,K,P,R,S,T,W,X,Y";
		}
		return $ychar;
	}
	
	function RandColor($rs,$re,$gs,$ge,$bs,$be){
		$r = mt_rand($rs,$re);
		$g = mt_rand($gs,$ge);
		$b = mt_rand($bs,$be);
		return array($r,$g,$b);
	}
	
	function GenerateImage(){
		$im = imagecreate($this->img_w,$this->img_h);
		
		$black = imagecolorallocate($im, 0,0,0);
		$white = imagecolorallocate($im, 255,255,255); 
		$bgcolor = imagecolorallocate($im, 250,250,250); 
		
		imagefill($im,0,0,$bgcolor);
		
		$fonts = ScanDir($this->font_dir);
		$fmax = count($fonts) - 2;
		
		$ychar = $this->GetChar($this->mode);
		$list = explode(",",$ychar);
		
		$x = mt_rand(2,$this->img_w/($this->v_num+2));
		$cmax = count($list) - 1;
	
		if(! strlen( $this->v_code)){
			for($i=0;$i<$this->v_num;$i++){ //验证码
				$randnum = mt_rand(0,$cmax);
				$this_char = $list[$randnum];
				$this->v_code .= $this_char;
			}
		}
	
		for($i=0;$i<$this->v_num;$i++){ //验证码
			$this_char = substr($this->v_code, $i, 1);
			$size = mt_rand(intval($this->img_w/5),intval($this->img_w/4));
			$angle = mt_rand(-20,20);
			$y = mt_rand(($size+2),($this->img_h-2));
			if($this->border)
			$y = ($size+3) < ($this->img_h-3) ? mt_rand(($size+3),($this->img_h-3)) : mt_rand(($this->img_h-3),($size+3));
			$rand_color = $this->RandColor(0,200,0,100,0,250);
			$randcolor = imagecolorallocate($im,$rand_color[0],$rand_color[1],$rand_color[2]);
			$fontrand = 2 < $fmax ? mt_rand(2, $fmax) : mt_rand($fmax, 2);
			$font = "$this->font_dir/"."font.ttf";
			imagettftext($im, $size, $angle, $x, $y, $randcolor, $font, $this_char);
			$x = $x + intval($this->img_w/($this->v_num+1));
		}
		
		for($i=0;$i<$this->int_pixel_num;$i++){//干扰像素
			$rand_color = $this->RandColor(50,250,0,250,50,250);
			$rand_color_pixel = imagecolorallocate($im,$rand_color[0],$rand_color[1],$rand_color[2]);
			imagesetpixel($im, mt_rand()%$this->img_w, mt_rand()%$this->img_h, $rand_color_pixel);
		}
		
		for($i=0;$i<$this->int_line_num;$i++){ //干扰线
			$rand_color = $this->RandColor(0,250,0,250,0,250);
			$rand_color_line = imagecolorallocate($im,$rand_color[0],$rand_color[1],$rand_color[2]);
			imageline($im, mt_rand(0,intval($this->img_w/3)), mt_rand(0,$this->img_h), mt_rand(intval($this->img_w - ($this->img_w/3)),$this->img_w), mt_rand(0,$this->img_h), $rand_color_line);
		}
		
		if($this->border){ //画出边框
			if(preg_match("/^\d{1,3},\d{1,3},\d{1,3}$/",$this->borderColor)){
				$borderColor = explode(',',$this->borderColor);
			}
			$border_color_line = imagecolorallocate($im,$borderColor[0],$borderColor[1],$borderColor[2]);
			imageline($im, 0, 0, $this->img_w, 0, $border_color_line); //上横
			imageline($im, 0, 0, 0, $this->img_h, $border_color_line); //左竖
			imageline($im, 0, $this->img_h-1, $this->img_w, $this->img_h-1, $border_color_line); //下横
			imageline($im, $this->img_w-1, 0, $this->img_w-1, $this->img_h, $border_color_line); //右竖
		}
		
		function_exists('imageantialias') && imageantialias($im,true); //抗锯齿
		
		$_SESSION['idcode'] = $this->v_code; //把验证码负值给$_SESSION[vCode]
		
		//生成图像给浏览器
		if (function_exists("imagegif")) {
			header ("Content-type: image/gif");
			imagegif($im);
		}elseif (function_exists("imagepng")) {
			header ("Content-type: image/png");
			imagepng($im);
		}elseif (function_exists("imagejpeg")) {
			header ("Content-type: image/jpeg");
			imagejpeg($im, "", 80);
		}elseif (function_exists("imagewbmp")) {
			header ("Content-type: image/vnd.wap.wbmp");
			imagewbmp($im);
		}else{
			die("No Image Support On This Server !");
		}
		imagedestroy($im);
	}
}
