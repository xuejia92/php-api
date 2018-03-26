<script type="text/javascript">
 $(".tabsPageContent").css('overflow','auto');
</script>
<div class="pageHeader">
	<h1>五星评价配置</h1>
</div>
<div class="pageContent">
    <div class="panelBar">
    	<ul class="toolBar">
    		<li><a class="add" href="?m=action&p=actionconfig&act=setCid&tapid=<?php echo $tapid?>&navTabId=action_modify&close=1" target="dialog" ><span>新增项</span></a></li>
    		<li><a class="delete" href="?m=action&p=actionconfig&act=unsetCid&tapid=<?php echo $tapid?>&navTabId=action_modify&close=1" target="dialog" ><span>删除项</span></a></li>
    	</ul>
    </div>
    <?php foreach (Config_Game::$game as $key=>$value):?>
    <form method="post" action="?m=action&p=actionconfig&act=set&gameid=<?php echo $key?>&tapid=<?php echo $tapid?>&navTabId=action_modify&close=1" class="pageForm required-validate"  onsubmit="return iframeCallback(this, dialogAjaxDone)">
		<div class="pageFormContent">
			<h1><?php echo $value?></h1>
			
			<div class="unit">
				<label>iOS版本号：</label>
				<input class="required" type="text" name="version" size="60" maxlength="20" value="<?php echo $action[$key]['version'] ? $action[$key]['version'] : ''?>"/>
			</div>
			
			<div class="unit">
				<label>奖励金币：</label>
				<input class="required" type="text" name="bonus" size="60" maxlength="20" value="<?php echo $action[$key]['bonus'] ? $action[$key]['bonus'] : ''?>"/>
			</div>
			
			<div class="unit">
			    <div class="nowrap">
    				<label>游戏url：</label>
    				<select id="gid<?php echo $key?>" class="gid" name="gid">
    				    <?php if ($aCid[$key]):?>
        				    <?php foreach ($aCid[$key] as $cid):?>
        				        <option value="<?php echo $cid?>"><?php echo Base::factory()->getChannel()[$cid]?></option>
        				    <?php endforeach;?>
    				    <?php endif;?>
    				</select>
    				<input type="text" id="url<?php echo $key?>" name="url" size="60" maxlength="200" value="<?php echo isset($action[$key]['url'][0]) ? $action[$key]['url'][0] : 'https://itunes.apple.com'?>"/>
				</div>
			</div>
			
			<div class="unit">
				<button type="submit">修改</button>
			</div>
		</div>
    </form>
    <?php endforeach;?>
</div>

<script type="text/javascript">
$(document).ready(function(){
    $(".gid").change(function(){
        var gid = $(this).attr("id").replace("gid","");
        var cid = $(this).val();
        $.ajax({
            type: "POST",
            data: "gid="+gid+"&cid="+cid,
            url: "<?php echo $url;?>?m=action&p=actionconfig&act=changeOption&navTabId=action_modify",
            success: function(e){
                $("#url"+gid).val(e);
            }
        })
    });
});
</script>