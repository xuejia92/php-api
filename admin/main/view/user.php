<div class="accordion" fillSpace="sideBar">
	<div class="accordionHeader">
		<h2><span>Folder</span>用户管理</h2>
	</div>
	<div class="accordionContent">
		<ul class="tree treeFolder">
		    <?php if ($permission[3][0]):?>
			<li><a href="admin.php?m=user&p=account" rel="u-alist" target="navTab">用户信息</a></li>
			<?php endif;?>
			<?php if ($permission[3][1]):?>
			<li><a href="admin.php?m=user&p=behavior" rel="behavior-list" target="navTab">用户行为</a></li>
			<?php endif;?>
			<?php if ($permission[3][2]):?>
			<li><a href="admin.php?m=user&p=account&act=getbatlist" rel="getbatlist" target="navTab">封号记录</a></li>
			<?php endif;?>
			<?php if ($permission[3][3]):?>
			<li><a href="admin.php?m=user&p=rank" rel="u-rlist" target="navTab">金币排行榜</a></li>
			<?php endif;?>
			<?php if ($permission[3][4]):?>
			<li><a href="admin.php?m=user&p=rank&act=getpayrank" rel="payrank" target="navTab">支付排行榜</a></li>
			<?php endif;?>
			<?php if ($permission[3][5]):?>
			<li><a href="admin.php?m=user&p=rank&act=getDeviceRank&type=<?php echo $_REQUEST['type'] ? $_REQUEST['type'] : 'deviceno' ?>" rel="devicerank" target="navTab">设备账号历史排行榜</a></li>
			<li><a href="admin.php?m=user&p=rank&act=getDeviceRankDay&type=ip" rel="devicerankday" target="navTab">设备账号日实时排行榜</a></li>
			<?php endif;?>
			<?php if ($permission[3][6]):?>
			<li><a href="admin.php?m=user&p=rank&act=getWinCoinRank" rel="rankwincoin" target="navTab">赢牌排行榜</a></li>
			<?php endif;?>
			<?php if ($permission[3][7]):?>
			<li><a href="admin.php?m=user&p=account&act=getNewRegister" rel="newregister" target="navTab">日注册列表</a></li>
			<?php endif;?>
			<?php if ($permission[3][8]):?>
			<li><a href="admin.php?m=user&p=provincepay" rel="provincepay" target="navTab">支付省份分布</a></li>
			<?php endif;?>
			<?php if ($permission[3][9]):?>
			<li><a href="admin.php?m=user&p=robotwin" rel="robotwin" target="navTab">系统库存</a></li>
			<?php endif;?>
			<?php if ($permission[3][10]):?>
			<li><a href="admin.php?m=user&p=blacklist" rel="blacklist" target="navTab">百人场黑名单</a></li>
			<?php endif;?>
		</ul>
	</div>
</div>