<div class="pageContent">	
	<form method="post" action="?m=setting&p=msgpay&act=set&navTabId=msgpaylist&close=1" class="pageForm required-validate"  onsubmit="return iframeCallback(this, navTabAjaxDone)">
		<input type="hidden" name="id" value="<?php echo isset($item['id']) ? $item['id'] : ""?>"/>
		<div class="pageFormContent" layoutH="58">
			
			<div class="unit">
				<label>游戏：</label>
				<?php foreach(Config_Game::$game as $gameid=>$gameName):?>
					<label><input  <?php if($_GET['gameid'] == $gameid):?>checked<?php endif;?>  type="checkbox" name="gameid[]" value="<?php echo $gameid?>" /><?php echo $gameName?></label>
				<?php endforeach;?>
			</div>

			<div class="unit">
			    <div  class="nowrap">
				<label>移动支付渠道选择：</label>
				<input class="required" class="readonly" name="shop.payname1" type="text" size="60" value="<?php echo isset($item[1]['name']) ?$item[1]['name'] : ""?>" />
				<input id='p_pmode1' type="hidden"  name="shop.pmode1" value="<?php echo isset($item[1]['pmode']) ? $item[1]['pmode'] : ''?>"  />
				<a class="btnLook" href="?m=setting&p=msgpay&act=getPayChannel&mtype=1&gameid=<?php echo $_GET['gameid']?>" lookupGroup="shop">查找</a>
				</div>
			</div>
			
			<div class="unit">
			    <div  class="nowrap">
				<label>联通支付渠道选择：</label>
				<input class="required" class="readonly" name="shop.payname2" type="text" size="60" value="<?php echo isset($item[2]['name']) ? $item[2]['name'] : ""?>" />
				<input id='p_pmode2' type="hidden"  name="shop.pmode2" value="<?php echo isset($item[2]['pmode']) ? $item[2]['pmode'] : ''?>"  />
				<a class="btnLook" href="?m=setting&p=msgpay&act=getPayChannel&mtype=2&gameid=<?php echo $_GET['gameid']?>" lookupGroup="shop">查找</a>
				</div>
			</div>
			
			<div class="unit">
			    <div  class="nowrap">
				<label>电信支付渠道选择：</label>
				<input class="required" class="readonly" name="shop.payname3" type="text" size="60" value="<?php echo isset($item[3]['name']) ? $item[3]['name'] : ""?>" />
				<input id='p_pmode3' type="hidden"  name="shop.pmode3" value="<?php echo isset($item[3]['pmode']) ? $item[3]['pmode'] : ''?>"  />
				<a class="btnLook" href="?m=setting&p=msgpay&act=getPayChannel&mtype=3&gameid=<?php echo $_GET['gameid'] ?>" lookupGroup="shop">查找</a>
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



