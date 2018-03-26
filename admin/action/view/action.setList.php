<div class="pageContent">
	<form method="post" action="?m=action&p=actionlist&act=update&navTabId=action_set&close=1" class="pageForm required-validate"  onsubmit="return iframeCallback(this, dialogAjaxDone)">
		<div class="pageFormContent" layoutH="58">
			
			<div class="unit">
				<label>活动名称：</label>
				<input type="text" name="name" size="60" maxlength="200" value="<?php echo $item['name'] ? $item['name'] : ''?>"/>
			</div>
			
			<div class="unit">
				<label>活动名称：</label>
				<input type="text" name="subject" size="60" maxlength="200" value="<?php echo $item['subject'] ? $item['subject'] : ''?>"/>
			</div>
			
			<div class="unit">
				<label>显示时间：</label>
				<input type="text" name="time" size="60" maxlength="200" value="<?php echo $item['time'] ? $item['time'] : ''?>"/>
			</div>
			
			<div class="unit">
				<label>描述：</label>
				<input type="text" name="desc" size="60" maxlength="200" value="<?php echo $item['desc'] ? $item['desc'] : ''?>"/>
			</div>
			
			<div class="unit">
				<label>图片地址：</label>
				<input type="text" name="image" size="60" maxlength="200" value="<?php echo $item['image'] ? $item['image'] : 'http://user.dianler.com/web/activity/image/example.png'?>"/>
			</div>
			
			<div class="unit">
				<label>Icon地址：</label>
				<input type="text" name="icon" size="60" maxlength="200" value="<?php echo $item['icon'] ? $item['icon'] : 'http://user.dianler.com/web/activity/image/example-banner.png'?>"/>
			</div>
			
			<div class="unit">
				<label>活动地址：</label>
				<input type="text" name="url" size="60" maxlength="200" value="<?php echo $item['url'] ? $item['url'] : 'http://user.dianler.com/index.php?m=activity&p=dispath&fpath=example'?>"/>
			</div>
			
			<div class="unit">
				<label>开关状态：</label>
				<input type="radio" name="open" value="1" <?php echo $item['open'] ? 'checked' : ''?> />开启
                <input type="radio" name="open" value="" <?php echo $item['open'] ? '' : 'checked'?> />关闭
			</div>
			
			<div class="unit">
				<label>上线时间：</label>
				<input style="float:left" type="text" value="<?php echo $item['start_time'] ? $item['start_time'] : date("Y-m-d",strtotime("+1 days")).' 00:00:00' ?>" name=start_time class="date" dateFmt="yyyy-MM-dd HH:mm:ss" readonly="true"/>
				<a class="inputDateButton" href="javascript:;">选择</a>
		        <span class="info">yyyy-MM-dd HH:mm:ss</span>
			</div>
			
			<div class="unit">
				<label>下线时间：</label>
				<input style="float:left" type="text" value="<?php echo $item['end_time'] ? $item['end_time'] : date("Y-m-d",strtotime("+8 days")).' 23:59:59' ?>" name=end_time class="date" dateFmt="yyyy-MM-dd HH:mm:ss" readonly="true"/>
				<a class="inputDateButton" href="javascript:;">选择</a>
		        <span class="info">yyyy-MM-dd HH:mm:ss</span>
			</div>
			
			<div class="unit">
				<div  class="nowrap">
				    <label>开启渠道：</label>
				    <input name="cid.cname" class="readonly" type="text" size="60" value="<?php echo $item['cname'] ? $item['cname'] : ""?>" />
				    <input name="cid.cid" type="text" size="60" value=<?php echo $item['openCid'] ? $item['openCid'] : ""?> >
				    <a class="btnLook" href="?m=action&p=actionlist&act=getChannel&id=<?php echo $item['name'] ? $item['name'] : ""?>" lookupGroup="cid">查找</a>
				    <span class="info">设置为空时开启所有渠道</span>
				</div>
			</div>
			
			<div class="unit">
			    <div  class="nowrap">
    				<label>客户端屏蔽：</label>
    				<?php foreach(Config_Game::$clientTyle as $ctype=>$typename):?>
    					<label><input <?php if(in_array($ctype,$item['closeType'])):?>checked<?php endif;?>  type="checkbox" name="closeCtype[]" value="<?php echo $ctype?>" /><?php echo $typename?></label>
    				<?php endforeach;?>
    				<label><input type="hidden" name="closeCtype[]" value="" /></label>
			    </div>
			</div>
			
			<div class="unit">
			    <div  class="nowrap">
    				<label>游戏屏蔽：</label>
    				<?php foreach(Config_Game::$game as $gameid=>$gameName):?>
    					<label><input <?php if(in_array($gameid,$item['closeGame'])):?>checked<?php endif;?>  type="checkbox" name="closeGameid[]" value="<?php echo $gameid?>" /><?php echo $gameName?></label>
    				<?php endforeach;?>
    				<label><input type="hidden" name="closeGameid[]" value="" /></label>
			     </div>
			</div>
			
			<div class="unit">
				<label>新活动标识：</label>
				<input type="radio" name="new" value="" <?php echo $item['new'] ? '' : 'checked'?> />不显示
                <input type="radio" name="new" value="1" <?php echo $item['new'] ? 'checked' : ''?> />显示
			</div>
			
			<div class="unit">
				<label>按钮名称：</label>
				<input type="text" name="buttonName" size="60" maxlength="200" value="<?php echo $item['buttonName'] ? $item['buttonName'] : ''?>"/>
			</div>
			
		</div>
		
		<div class="formBar">
			<ul>
				<li><div class="buttonActive"><div class="buttonContent"><button type="submit"><?php echo $tapid ? "修改" : "增加"?></button></div></div></li>
				<li><div class="button"><div class="buttonContent"><button type="button" class="close">取消</button></div></div></li>
			</ul>
		</div>
		
	</form>
	
</div>