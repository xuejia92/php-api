<div class="accordion" fillSpace="sideBar">
	<div class="accordionHeader">
		<h2><span>Folder</span>反馈</h2>
	</div>
	<div class="accordionContent">
    <?php if ($permission[0]):?>
		<ul class="tree treeFolder">
			<li><a href="admin.php?m=feedback&p=list" rel="f_list" target="navTab">反馈列表</a></li>
			<li><a href="admin.php?m=feedback&p=send" rel="f_send" target="navTab">主动向玩家发消息</a></li>
		</ul>
	<?php endif;?>
	</div>
</div>