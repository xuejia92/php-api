<div class="accordion" fillSpace="sideBar">
	<div class="accordionHeader">
		<h2><span>Folder</span>游戏列表</h2>
	</div>	
	<div class="accordionContent">
		<ul class="tree treeFolder collapse">
		<?php foreach(Config_Game::$game as $gameid=>$gameName):?>
		<?php 
		      $swithch = array(1=>0,3=>1,4=>2,5=>3,6=>4,7=>5);
		      if ($permission[4][$swithch[$gameid]]):
		?>
		<li><a href="admin.php?m=data&p=show&gameid=<?php echo $gameid?>&catid=1&navTabId=data_<?php echo $gameid?>" rel="data_<?php echo $gameid?>" target="navTab"><?php echo $gameName?></a>
			<ul>
			    <li><a title="月度报表<?php echo '-'.Config_Game::$game[$gameid]?>" href="admin.php?m=data&p=monthlyReport&gameid=<?php echo $gameid?>&ctype=&navTabId=data_0_<?php echo $gameid?>" rel="data_0_<?php echo $gameid?>" target="navTab">月度报表</a></li>
				<?php if ($gameid==3):?>
				<li><a title="牌型统计" href="admin.php?m=data&p=landcardCardType&navTabId=data_0_<?php echo $gameid?>_3" rel="data_0_<?php echo $gameid?>_3" target="navTab">牌型统计</a></li>
				<?php endif;?>
				<?php if ($gameid==5):?>
				<li><a title="道具使用情况" href="admin.php?m=data&p=fish&act=skills&navTabId=data_0_<?php echo $gameid?>_5" rel="data_0_<?php echo $gameid?>_5" target="navTab">道具使用情况</a></li>
				<?php endif;?>
				<?php foreach(Config_Game::$clientTyle as $ctype=>$clientName):?>
				<li><a title="<?php echo $clientName.'-'.Config_Game::$game[$gameid]?>" href="admin.php?m=data&p=show&gameid=<?php echo $gameid?>&ctype=<?php echo $ctype?>&catid=1&navTabId=data_<?php echo $gameid?>_<?php echo $ctype?>" rel="data_<?php echo $gameid?>_<?php echo $ctype?>" target="navTab"><?php echo $clientName?></a>
				<?php $channel = Setting_Cid::factory()->get(array('gameid'=>$gameid,'ctype'=>$ctype)) ?>
				    <ul>
				        <li><a title="月度报表<?php echo '-'.Config_Game::$game[$gameid]?><?php echo '-'.Config_Game::$clientTyle[$ctype]?>" href="admin.php?m=data&p=monthlyReport&gameid=<?php echo $gameid?>&ctype=<?php echo $ctype?>&navTabId=data_0_<?php echo $gameid?>_<?php echo $ctype?>" rel="data_0_<?php echo $gameid?>_<?php echo $ctype?>" target="navTab">月度报表</a></li>
				        <li><a title="渠道概况<?php echo '-'.Config_Game::$game['gameid']?>" href="admin.php?m=data&p=channel&gameid=<?php echo $gameid?>&ctype=<?php echo $ctype?>&catid=1&navTabId=data_<?php echo $gameid?>_<?php echo $ctype?>_0" rel="data_<?php echo $gameid?>_<?php echo $ctype?>_0" target="navTab">渠道概况</a></li>
					<?php foreach($channel as $aChannel):?>
						<li><a title="<?php echo $aChannel['cname'].'-'.$clientName.'-'.Config_Game::$game[$gameid]?>" href="admin.php?m=data&p=show&gameid=<?php echo $gameid?>&ctype=<?php echo $ctype?>&cid=<?php echo $aChannel['id']?>&catid=1&navTabId=data_<?php echo $gameid?>_<?php echo $ctype?>_<?php echo $aChannel['id']?>" rel="data_<?php echo $gameid?>_<?php echo $ctype?>_<?php echo $aChannel['id']?>" target="navTab"><?php echo $aChannel['cname']?></a></li>
					<?php endforeach;?>
					</ul>
				</li>
			    <?php endforeach;?>
			</ul>
		</li>
		<?php endif;?>
		<?php endforeach;?>
		</ul>
		<ul class="tree treeFolder collapse">
		      <?php if ($permission[4][6]):?>
    		  <li><a id="data_all" title="全局金币概况" href="admin.php?m=data&p=show&catid=5&act=detail&navTabId=data_all" rel="data_all" target="navTab">全局金币概况</a></li>
    		  <?php endif;?>
    		  <?php if ($permission[4][7]):?>
    		  <li><a id="data_roll" title="全局乐券概况" href="admin.php?m=data&p=show&catid=14&act=detail&navTabId=data_roll" rel="data_roll" target="navTab">全局乐券概况</a></li>
    		  <?php endif;?>
    		  <?php if ($permission[4][8]):?>
    		  <li><a id="data_roll" title="娱乐场金币消耗概况" href="admin.php?m=data&p=show&act=yule&navTabId=data_yule" rel="data_yule" target="navTab">娱乐场金币消耗概况</a></li>
    		  <?php endif;?>
		</ul>
	</div>	
	<?php if ($permission[4][9]):?>
	<div class="accordionHeader">
		<h2><span>Folder</span>玩家版本分布</h2>
	</div>	
	<div class="accordionContent">
		<?php foreach(Config_Game::$game as $gameid=>$gameName):?>
		<ul class="tree treeFolder collapse">
			<li><a href="admin.php?m=data&p=version&gameid=<?php echo $gameid?>&catid=1&navTabId=version_<?php echo $gameid?>" rel="version_<?php echo $gameid?>" target="navTab"><?php echo $gameName?></a>	</li>
		</ul>
		<?php endforeach;?>
	</div>	
	<?php endif;?>
	<?php if ($permission[4][10]):?>
	<div class="accordionHeader">
		<h2><span>Folder</span>网页统计</h2>
	</div>	
	<div class="accordionContent">
	    <ul class="tree treeFolder collapse">
             <li><a href="admin.php?m=data&p=mobile&catid=1&filter=0&navTabId=mobile_1" rel="mobile_1" target="navTab">移动端网页统计</a>
                 <?php foreach(Config_Game::$game as $gameid=>$gameName):?>
	             <ul>
	                 <li><a title="<?php echo $gameName?>" href="admin.php?m=data&p=mobile&act=detail&gameid=<?php echo $gameid?>&catid=1&filter=0&navTabId=mobile_1_<?php echo $gameid?>" rel="mobile_1_<?php echo $gameid?>" target="navTab"><?php echo $gameName?></a></li>
	             </ul>
	             <?php endforeach;?>
             </li>
             <li><a href="admin.php?m=data&p=baiduSearch&catid=1&navTabId=mobile_2" rel="mobile_2" target="navTab">百度搜索统计</a>
                 <?php foreach(Config_Game::$game as $gameid=>$gameName):?>
	             <ul>
	                 <li><a title="<?php echo $gameName?>" href="admin.php?m=data&p=baiduSearch&act=detail&gameid=<?php echo $gameid?>&catid=1&navTabId=mobile_2_<?php echo $gameid?>" rel="mobile_2_<?php echo $gameid?>" target="navTab"><?php echo $gameName?></a></li>
	             </ul>
	             <?php endforeach;?>
	             <ul>
	                 <li><a title="IP地址统计" href="admin.php?m=data&p=baiduSearch&act=IP&gameid=&catid=1&navTabId=mobile_2_8" rel="mobile_2_8" target="navTab">IP地址统计</a></li>
	             </ul>
             </li>
	    </ul>
	</div>
	<?php endif;?>
	<?php if ($permission[4][11]):?>
	<div class="accordionHeader">
		<h2><span>Folder</span>牌型任务数据</h2>
	</div>	
	<div class="accordionContent">
		<ul class="tree">
		<?php foreach(Config_Game::$game as $gameid=>$gameName):?>
			<li><a title="<?php echo Config_Game::$game[$gameid]?>" href="admin.php?m=data&p=task&act=overview&gameid=<?php echo $gameid?>&navTabId=task_<?php echo $gameid?>" rel="task_<?php echo $gameid?>" target="navTab"><?php echo $gameName?></a></li>
		<?php endforeach;?>	
		</ul>
	</div>
    <?php endif;?>
    <?php if ($permission[4][12]):?>
	<div class="accordionHeader">
		<h2><span>Folder</span>鱼儿捕捉数据</h2>
	</div>	
	<div class="accordionContent">
	    <ul class="tree treeFolder collapse">
    		<li><a href="admin.php?m=data&p=fish&act=killed&navTabId=fishkilled" rel="fishkilled" target="navTab">全局数据</a>
        		<ul>
        		<?php $fishRoomCfg = User_Account::factory()->fishRoomCfg();?>
        		<?php foreach($fishRoomCfg as $roomid=>$roomname):?>
        			<li><a title="<?php echo $roomname?>" href="admin.php?m=data&p=fish&act=killed&roomid=<?php echo $roomid?>&navTabId=fishkilled_<?php echo $roomid?>" rel="fishkilled_<?php echo $roomid?>" target="navTab"><?php echo $roomname?></a></li>
        		<?php endforeach;?>	
        		</ul>
    		</li>
		</ul>
	</div>
	<?php endif;?>
	<?php if ($permission[4][13]):?>
	<div class="accordionHeader">
		<h2><span>Folder</span>统计设置</h2>
	</div>	
	<div class="accordionContent">
			<ul class="tree">
				<li><a  href="admin.php?m=data&p=category" rel="data_cat" target="navTab">统计分类</a></li>
				<li><a  href="admin.php?m=data&p=item" rel="data_item" target="navTab">统计项设置</a></li>
			</ul>
	</div>	
    <?php endif;?>
</div>




