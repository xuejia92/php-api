<div class="pageContent">	
	<form method="post" action="?m=setting&p=room&act=set&navTabId=<?php echo isset($_GET['navTabId']) ? $_GET['navTabId'] : "room"?>&close=1" class="pageForm required-validate"  onsubmit="return iframeCallback(this, dialogAjaxDone)">
		<input type="hidden" name="id" value="<?php echo isset($item['id']) ? $item['id'] : ""?>"/>
		<div class="pageFormContent" layoutH="58">

			<div class="unit">
				<label>台费：</label>
				<input type="text" name="raterent" size="10" maxlength="400" value="<?php echo isset($item['raterent']) ? $item['raterent'] : ''?>"/>
				<span style="color:red;float:left;">(台费为底注的百分比)</span>
			</div>
			<div class="unit">
				<label>最小金币数：</label>
				<input type="text" name="minmoney" size="10" maxlength="400" value="<?php echo isset($item['minmoney']) ? $item['minmoney'] : ''?>"/>
				<span style="color:red;float:left;">(进入该房间的需要最小金币值限制)</span>
			</div>
			<div class="unit">
				<label>最大金币数：</label>
				<input type="text" name="maxmoney" size="10" maxlength="400" value="<?php echo isset($item['maxmoney']) ? $item['maxmoney'] : ''?>"/>
				<span style="color:red;float:left;">(进入该房间最大金币值限制)</span>
			</div>
			<div class="unit">
				<label>梭哈最大上限：</label>
				<input type="text" name="carrycoin" size="10" maxlength="400" value="<?php echo isset($item['carrycoin']) ? $item['carrycoin'] : ''?>"/>
				<span style="color:red;float:left;">(梭哈最大上限，包括跟注，加注总和)</span>
			</div>
			<div class="unit">
				<label>输完保留金币数：</label>
				<input type="text" name="retaincoin" size="10" maxlength="400" value="<?php echo isset($item['retaincoin']) ? $item['retaincoin'] : ''?>"/>
				<span style="color:red;float:left;">(玩家在该房间输完后为其保留的金币数)</span>
			</div>
			
		</div>
		<div class="formBar">
			<ul>
				<li><div class="buttonActive"><div class="buttonContent"><button type="submit"><?php echo isset($item['id']) ? "修改" : "修改"?></button></div></div></li>
				<li><div class="button"><div class="buttonContent"><button type="button" class="close">取消</button></div></div></li>
			</ul>
		</div>
	</form>
	
</div>



