<div class="pageContent">	
	<form method="post" action="?m=setting&p=msgpay&act=setProvice&navTabId=provincelist&close=1" class="pageForm required-validate"  onsubmit="return iframeCallback(this, dialogAjaxDone)">
		<div class="pageFormContent" layoutH="58">
			
			<div class="unit">
				<label>支付渠道：</label>
				<select name="pmode" id="pmode" >
					<option value="0">请选择</option>
					<?php if($aPmode):?>
						<?php foreach($aPmode as $pmode=>$pmodeName):?>
							<option <?php if($pmode == $_GET['pmode']):?> selected="selected" <?php endif;?> value="<?php echo $pmode?>"><?php echo $pmodeName?></option>
						<?php endforeach;?>
					<?php endif;?>
				</select>
			</div>

			<div class="unit">
				<label>游戏：</label>
				<select name="gameid" id="gameid" >
					<?php foreach(Config_Game::$game as $gameid=>$gameName):?>
						<option <?php if($gameid == $_GET['gameid']):?> selected="selected" <?php endif;?> value="<?php echo $gameid?>"><?php echo $gameName?></option>
					<?php endforeach;?>
				</select>
			</div>
			
			<div class="unit">
			    <div  class="nowrap">
				<label>移动省份屏蔽：</label>
				<input   name="province1" type="text" size="80" value="<?php echo isset($item[1]) ?$item[1] : ""?>" />
				</div>
			</div>
			
			<div class="unit">
			    <div  class="nowrap">
				<label>联通省份屏蔽：</label>
				<input   name="province2" type="text" size="80" value="<?php echo isset($item[2]) ?$item[2] : ""?>" />
				</div>
			</div>
			
			<div class="unit">
			    <div  class="nowrap">
				<label>电信省份屏蔽：</label>
				<input   name="province3" type="text" size="80" value="<?php echo isset($item[3]) ?$item[3] : ""?>" />
				</div>
			</div>
		</div>
		<div class="formBar">
			<ul>
				<li><div class="buttonActive"><div class="buttonContent"><button type="submit"><?php echo $item ? "修改" : "增加"?></button></div></div></li>
				<li><div class="button"><div class="buttonContent"><button type="button" class="close">取消</button></div></div></li>
			</ul>
		</div>
	</form>
	
</div>



