<div class="pageContent">
<div class="panelBar">
<ul class="toolBar">
	<li><a class="add" href="?m=setting&p=behavior&act=setView&navTabId=behavior" target="dialog" ><span>增加</span></a></li>
	<li><a class="delete" href="?m=setting&p=behavior&act=del&id={id}&navTabId=behavior" target="ajaxTodo" title="你确定要删除吗？" warn="请选择关卡"><span>删除</span></a></li>
</ul>
</div>

<table class="table" width="100%" layoutH="138">
	<thead>
		<tr>
			<th width="80" >编号</th>
			<th width="150">游戏ID</th>
			<th width="150">命令符 </th>
			<th width="150" >命令名称</th>
			<th width="150">操作 </th>
		</tr>
	</thead>
	<tbody>
	<?php foreach(  $items as $item ):?>
		<tr target="id" rel="<?php echo $item['id']?>">
			<td><?php echo $item['id'] ?></td>
			<td><?php echo $item['gameid'] ?></td>
			<td><?php echo $item['beid'] ?></td>
			<td><?php echo $item['betitle'] ?></td>
			<td>
			<a class="delete" href="?m=setting&p=behavior&act=del&id=<?php echo $item['id']?>&navTabId=behavior" target="ajaxTodo" title="你确定要删除吗？" warn="请选择其中一个">
				删除 
			</a>	
			|		
			<a class="delete" href="?m=setting&p=behavior&act=setView&id=<?php echo $item['id']?>&navTabId=behavior" target="dialog">
				修改
			</a>
			</td>
		</tr>
	<?php endforeach;?>
	</tbody>
</table>
</div>


