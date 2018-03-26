
<div class="pageContent">
<div class="panelBar">
<ul class="toolBar">
	<li><a class="add" href="?m=setting&p=cid&act=setView&navTabId=channel_<?php echo $_REQUEST['gameid']?>&gameid=<?php echo $_GET['gameid']?>&ctype=<?php echo $_REQUEST['ctype']?>" target="dialog" mask="true" width="800" height="580"><span>新增渠道商(cid)</span></a></li>
	<li><a class="delete" href="?m=setting&p=cid&act=del&id={id}&navTabId=channel_<?php echo $_REQUEST['gameid']?>" target="ajaxTodo" title="你确定要删除吗？" warn="请选择关卡"><span>删除</span></a></li>
</ul>
</div>

<table class="table" width="100%" layoutH="138">
	<thead>
		<tr>
			<th width="80" >cid</th>
			<th width="200" >渠道商名称</th>
			<th width="200" >客户端类型</th>
			<th width="200" >所属游戏</th>
			<th width="200" >渠道类型</th>
			<th width="150">操作 </th>
		</tr>
	</thead>
	<tbody>
	<?php foreach(  $items as $item ):?>
		<tr target="id" rel="<?php echo $item['id']?>">
			<td><?php echo $item['id'] ?></td>
			<td><?php echo $item['cname'] ?></td>
			<td><?php echo $aCtype[$item['ctype']] ?></td>
			<td><?php echo Config_Game::$game[$_GET['gameid']] ?></td>
			<td><?php echo Config_Game::$channelVertype[$item['vertype']] ?></td>
			<td>
			<a class="delete" href="?m=setting&p=cid&act=del&id=<?php echo $item['id']?>&navTabId=channel_<?php echo $_REQUEST['gameid']?>" target="ajaxTodo" title="你确定要删除这个关卡吗？" warn="请选择其中一个活动">
				删除 
			</a>	
			|		
			<a class="delete" href="?m=setting&p=cid&act=setView&id=<?php echo $item['id']?>&navTabId=channel_<?php echo $_REQUEST['gameid']?>&gameid=<?php echo $_GET['gameid']?>" target="dialog" width="800" height="580" >
				修改
			</a>
			</td>
		</tr>
	<?php endforeach;?>
	</tbody>
</table>
</div>


