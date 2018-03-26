<div class="accordion" fillSpace="sideBar">
	<div class="accordionHeader">
		<h2><span>Folder</span>活动列表</h2>
	</div>	
    <?php if ($permission[5][0]):?>
	<div class="accordionContent">
			<ul class="tree">
			    <?php foreach (Action_Config::$action as $actionid=>$actionName):?>
			    <ul class="tree treeFolder collapse">
			             <li><a href="admin.php?m=action&p=list&actionid=<?php echo $actionid?>&catid=1&navTabId=action_<?php echo $actionid?>" rel="action_<?php echo $actionid?>" target="navTab"><?php echo $actionName['subject']?></a>
			                 <?php foreach(Config_Game::$game as $gameid=>$gameName):?>
    			             <ul>
    			                 <li><a href="admin.php?m=action&p=list&actionid=<?php echo $actionid?>&gameid=<?php echo $gameid?>&catid=1&navTabId=action_<?php echo $actionid?>_<?php echo $gameid?>" rel="action_<?php echo $actionid?>_<?php echo $gameid?>" target="navTab"><?php echo $gameName?></a>	
                        			<?php foreach(Config_Game::$clientTyle as $ctype=>$clientName):?>
                        			<ul>
                        				<li><a title="<?php echo $clientName.'-'.Config_Game::$game[$gameid]?>" href="admin.php?m=action&p=list&actionid=<?php echo $actionid?>&gameid=<?php echo $gameid?>&ctype=<?php echo $ctype?>&catid=1&navTabId=action_<?php echo $actionid?>_<?php echo $gameid?>_<?php echo $ctype?>" rel="action_<?php echo $actionid?>_<?php echo $gameid?>_<?php echo $ctype?>" target="navTab"><?php echo $clientName?></a></li>
                        			</ul>
                        			<?php endforeach;?>
                        		 </li>
    			             </ul>
    			             <?php endforeach;?>
			             </li>
			    <?php endforeach;?>
			    </ul>
			</ul>
	</div>
	
	<div class="accordionHeader">
		<h2><span>Folder</span>活动配置</h2>
	</div>	
	<div class="accordionContent">
			<ul class="tree">
			    <li><a href="admin.php?m=action&p=actionlist&navTabId=action_actionlist" rel="action_actionlist" target="navTab">活动列表</a></li>
			    <?php $result = Action_Configuration::$action;?>
			    <?php foreach ($result as $key=>$value):?>
				<li><a href="admin.php?m=action&p=<?php echo $value['name']?>&navTabId=action_modify_<?php echo $key?>" rel="action_modify_<?php echo $key?>" target="navTab"><?php echo $value['subject']?></a></li>
				<?php endforeach;?>
			</ul>
	</div>	
	
	<div class="accordionHeader">
		<h2><span>Folder</span>活动工具</h2>
	</div>	
	<div class="accordionContent">
			<ul class="tree">
				<li><a>测试数据</a></li>
				<li><a>抽奖记录</a></li>
			</ul>
	</div>	
    <?php endif;?>
</div>




