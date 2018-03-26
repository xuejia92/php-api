
	<div class="accordion" fillSpace="sideBar">
		<div class="accordionHeader">
			<h2><span>Folder</span>监控菜单</h2>
		</div>
		<div class="accordionContent">
			<ul class="tree treeFolder">
			    <?php if ($permission[1][0]):?>
				<li><a>API统计监控</a>
					<ul>
						<li><a rel="stat_list" target="navTab" href="http://121.201.0.148:55757">API统计监控数据</a></li>
					</ul>
				</li>
			    <?php endif;?>
			    <?php if ($permission[1][1]):?>
				<li><a>短信</a>
					<ul>
						<li><a href="admin.php?m=monitor&p=message" target="navTab" rel="msg_list">发送列表</a></li>
						<li><a href="admin.php?m=monitor&p=message&act=stat" target="navTab" rel="msg_stat">发送统计</a></li>
						<li><a href="admin.php?m=monitor&p=rank" target="navTab" rel="msg_rank">排行</a></li>
					</ul>
				</li>
				<?php endif;?>
			</ul>
		</div>
	</div>

	