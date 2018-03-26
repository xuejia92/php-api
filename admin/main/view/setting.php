
<div class="accordion" fillSpace="sideBar">
	<div class="accordionHeader">
		<h2><span>Folder</span>游戏设置</h2>
	</div>
	<div class="accordionContent">
		<ul class="tree treeFolder">
		    <?php if ($permission[0][0]):?>
			<li><a>版本管理</a>
				<ul>
					<li><a href="admin.php?m=setting&p=versions" rel="vlist" target="navTab">版本管理</a></li>
				</ul>
			</li>
			<?php endif;?>
			<?php if ($permission[0][1]):?>
			<li><a>公告管理</a>
				<ul>
					<li><a href="admin.php?m=setting&p=notice" rel="nlist" target="navTab">公告管理</a></li>
				</ul>
			</li>
			<?php endif;?>
			<?php if ($permission[0][2]):?>
			<li><a>喇叭管理</a>
				<ul>
					<li><a href="admin.php?m=setting&p=horn" rel="horn" target="navTab">喇叭管理</a></li>
				</ul>
			</li>
			<?php endif;?>
			<?php if ($permission[0][3]):?>
			<li><a>统计设置</a>
				<ul>
					<li><a href="admin.php?m=setting&p=sid" rel="slist" target="navTab">账号类型(sid)管理</a></li>
					<li><a href="admin.php?m=setting&p=wmode" rel="wlist" target="navTab">金币日记(wmode)管理</a></li>
					<li><a href="admin.php?m=setting&p=pmode" rel="pmodelist" target="navTab">支付渠道(pmode)管理</a></li>
				</ul>
			</li>
			<?php endif;?>
			<?php if ($permission[0][4]):?>
			<li><a>房间配置</a>
				<ul>
					<li><a href="admin.php?m=setting&p=room" rel="room" target="navTab">房间配置信息</a></li>
				</ul>
			</li>
			<?php endif;?>
			<?php if ($permission[0][5]):?>
			<li><a>用户行为配置</a>
				<ul>
					<li><a href="admin.php?m=setting&p=behavior" rel="behavior" target="navTab">行为配置</a></li>
				</ul>
			</li>
			<?php endif;?>
			<?php if ($permission[0][6]):?>
			<li><a>推送管理</a>
				<ul>
					<li><a href="admin.php?m=setting&p=push" rel="push" target="navTab">推送管理</a></li>
				</ul>
			</li>
			<?php endif;?>
			<?php if ($permission[0][7]):?>
			<li><a>权限管理</a>
				<ul>
					<li><a href="admin.php?m=setting&p=privilege" rel="privilege" target="navTab">权限管理</a></li>
					<li><a href="admin.php?m=setting&p=logadmin" rel="logadmin" target="navTab">管理员日记</a></li>
					<li><a href="admin.php?m=setting&p=goldoperation" rel="goldoperation" target="navTab">金币操作日记</a></li>
				</ul>
			</li>
			<?php endif;?>
			<?php if ($permission[0][8]):?>
			<li><a>短代支付管理</a>
				<ul>
					<li><a href="admin.php?m=setting&p=msgpay" rel="msgpaylist" target="navTab">支付管理</a></li>
					<li><a href="admin.php?m=setting&p=msgpay&act=getProvice" rel="provincelist" target="navTab">短代省份管理</a></li>
				</ul>
			</li>
			<?php endif;?>
		</ul>
	</div>
	<?php if ($permission[0][9]):?>
	<div class="accordionHeader">
		<h2><span>Folder</span>渠道包管理</h2>
	</div>	
	<div class="accordionContent">
		<?php foreach(Config_Game::$game as $gameid=>$gameName):?>
		<ul class="tree treeFolder collapse">
		<li><a><?php echo $gameName?></a>	
			<?php foreach(Config_Game::$clientTyle as $ctype=>$clientName):?>
			<ul>
				<li><a title="<?php echo $clientName.'-'.Config_Game::$game[$gameid]?>" href="admin.php?m=setting&p=cid&gameid=<?php echo $gameid?>&ctype=<?php echo $ctype?>&navTabId=channel_<?php echo $gameid?>" rel="channel_<?php echo $gameid?>" target="navTab"><?php echo $clientName?></a>
					<?php $channel = Setting_Cid::factory()->get(array('gameid'=>$gameid,'ctype'=>$ctype )) ?>
					<?php if($channel):?>
						<?php foreach($channel as $aChannel):?>
							<ul>
								<li><a title="<?php echo $aChannel['cname'].'-'.$clientName.'-'.Config_Game::$game[$gameid]?>" href="admin.php?m=setting&p=pid&gameid=<?php echo $gameid?>&ctype=<?php echo $ctype?>&cid=<?php echo $aChannel['id']?>&navTabId=channel_<?php echo $gameid?>_<?php echo $aChannel['id']?>" rel="channel_<?php echo $gameid?>_<?php echo $aChannel['id']?>" target="navTab"><?php echo $aChannel['cname']?></a></li>
							</ul>
						<?php endforeach;?>	
					<?php else:?>
						<ul>
							<li><a target="navTab">测试渠道</a></li>
						</ul>
					<?php endif;?>
					
				</li>
			</ul>
			<?php endforeach;?>
		</li>
		</ul>
		<?php endforeach;?>
	</div>
	<?php endif;?>
</div>


