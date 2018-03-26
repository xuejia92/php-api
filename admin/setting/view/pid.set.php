<div class="pageContent">	
	<form method="post" action="?m=setting&p=pid&act=set&<?php echo $_GET['ctype'] ?>&navTabId=<?php echo isset($_GET['navTabId']) ? $_GET['navTabId'] : "plist"?>&close=1" class="pageForm required-validate"  onsubmit="return iframeCallback(this, navTabAjaxDone)">
		<input type="hidden" name="id" value="<?php echo isset($item['id']) ? $item['id'] : ""?>"/>
		<div class="pageFormContent" layoutH="58">
			<div class="unit">
				<label>所属游戏：</label>
				<select name="gameid">
					<?php foreach (Config_Game::$game as $gameid=>$gamename):?>
						<option <?php if($_REQUEST['gameid'] == $gameid):?> selected="selected" <?php endif;?> value="<?php echo $gameid?>"><?php echo $gamename;?></option>
					<?php endforeach;?>
				</select>
			</div>
			<div class="unit">
				<label>所属渠道：</label>
				<select name="cid">
					<?php foreach ($aCid as $cid=>$cname):?>
						<option <?php if($_REQUEST['cid'] == $cid):?> selected="selected" <?php endif;?> value="<?php echo $cid?>"><?php echo $cname;?></option>
					<?php endforeach;?>
				</select>
			</div>
			<div class="unit">
				<label>客户端包名称：</label>
				<input type="text" name="pname" size="60" maxlength="200" value="<?php echo isset($item['pname']) ? $item['pname'] : ''?>"/>
			</div>
			
			<div class="unit">
			    <div  class="nowrap">
				<label>移动支付渠道选择：</label>
				<input class="required" class="readonly" name="shop.payname1" type="text" size="60" value="<?php echo isset($aPay[1]['name']) ?$aPay[1]['name'] : ""?>" />
				<input id='p_pmode1' type="hidden"  name="shop.pmode1" value="<?php echo isset($aPay[1]['pmode']) ? implode(',', $aPay[1]['pmode']) : ''?>"  />
				<a class="btnLook" href="?m=setting&p=msgpay&act=getPayChannelByPid&pid=<?php echo $item['id']?>&mtype=1&gameid=<?php echo $_GET['gameid']?>" lookupGroup="shop">查找</a>
				</div>
			</div>
			
			<div class="unit">
			    <div  class="nowrap">
				<label>联通支付渠道选择：</label>
				<input class="required" class="readonly" name="shop.payname2" type="text" size="60" value="<?php echo isset($aPay[2]['name']) ?$aPay[2]['name'] : ""?>" />
				<input id='p_pmode2' type="hidden"  name="shop.pmode2" value="<?php echo isset($aPay[2]['pmode']) ? implode(',', $aPay[2]['pmode']) : ''?>"  />
				<a class="btnLook" href="?m=setting&p=msgpay&act=getPayChannelByPid&mtype=2&pid=<?php echo $item['id']?>&mtype=2&gameid=<?php echo $_GET['gameid']?>" lookupGroup="shop">查找</a>
				</div>
			</div>
			
			<div class="unit">
			    <div  class="nowrap">
				<label>电信支付渠道选择：</label>
				<input class="required" class="readonly" name="shop.payname3" type="text" size="60" value="<?php echo isset($aPay[3]['name']) ?$aPay[3]['name'] : ""?>" />
				<input id='p_pmode3' type="hidden"  name="shop.pmode3" value="<?php echo isset($aPay[3]['pmode']) ? implode(',', $aPay[3]['pmode']) : ''?>"  />
				<a class="btnLook" href="?m=setting&p=msgpay&act=getPayChannelByPid&mtype=3&pid=<?php echo $item['id'] ?>&mtype=3&gameid=<?php echo $_GET['gameid']?>" lookupGroup="shop">查找</a>
				</div>
			</div>
			
			<div class="unit">
				<label>开关控制：</label>
					<label><input  <?php if(in_array(1,$switch)):?>checked<?php endif;?>  type="checkbox" name="switch[]" value="1" />关网页支付</label>
					<label><input  <?php if(in_array(2,$switch)):?>checked<?php endif;?>  type="checkbox" name="switch[]" value="2" />关更多游戏</label>
					<label><input  <?php if(in_array(6,$switch)):?>checked<?php endif;?>  type="checkbox" name="switch[]" value="6" />关实物兑换</label>
					<label><input  <?php if(in_array(9,$switch)):?>checked<?php endif;?>  type="checkbox" name="switch[]" value="9" />关点卡兑换</label>
					<?php if($_GET['ctype'] == 2):?>
					<label><input  <?php if(in_array(11,$switch)):?>checked<?php endif;?>  type="checkbox" name="switch[]" value="11" />关账号通用</label>
					<label><input  <?php if(in_array(12,$switch)):?>checked<?php endif;?>  type="checkbox" name="switch[]" value="12" />关游客注册</label>
					<?php endif?>
					<label><input  <?php if(in_array(3,$switch)):?>checked<?php endif;?>  type="checkbox" name="switch[]" value="3" />关娱乐场</label>
					<label><input  <?php if(in_array(4,$switch)):?>checked<?php endif;?>  type="checkbox" name="switch[]" value="4" />关首充</label>
					<label><input  <?php if(in_array(5,$switch)):?>checked<?php endif;?>  type="checkbox" name="switch[]" value="5" />关破产充</label>
					<label><input  <?php if(in_array(10,$switch)):?>checked<?php endif;?>  type="checkbox" name="switch[]" value="10" />关限时充</label>
					<?php if($_GET['ctype'] == 1):?>
					<label><input  <?php if(in_array(7,$switch)):?>checked<?php endif;?>  type="checkbox" name="switch[]" value="7" />关短代防刷</label>
					<label><input  <?php if(in_array(13,$switch)):?>checked<?php endif;?>  type="checkbox" name="switch[]" value="13" />关短代省份过滤</label>
					<?php endif?>
					<label><input  <?php if(in_array(8,$switch)):?>checked<?php endif;?>  type="checkbox" name="switch[]" value="8" />关活动</label>
					<label><input  <?php if(in_array(14,$switch)):?>checked<?php endif;?>  type="checkbox" name="switch[]" value="14" />关公告</label>
					
			</div>
			
			<div class="unit">
				<label>子渠道id：</label>
				<input type="text" name="childid" size="60" maxlength="200" value="<?php echo isset($item['childid']) ? $item['childid'] : 0?>"/>
			</div>
		</div>
		<div class="formBar">
			<ul>
				<li><div class="buttonActive"><div class="buttonContent"><button type="submit"><?php echo isset($item['id']) ? "修改" : "增加"?></button></div></div></li>
				<li><div class="button"><div class="buttonContent"><button type="button" class="close">取消</button></div></div></li>
			</ul>
		</div>
	</form>	
</div>



