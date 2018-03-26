<div class="pageContent">
<div class="panelBar">
<ul class="toolBar">
	<li><a class="add" href="?m=setting&p=horn&act=setView&navTabId=horn" target="dialog" ><span>发送喇叭</span></a></li>
	<li><a class="delete" href="?m=setting&p=horn&act=del&id={id}&navTabId=horn" target="ajaxTodo" title="你确定要删除吗？" warn="请选择关卡"><span>删除</span></a></li>
</ul>
</div>

<table class="table" width="100%" layoutH="138">
	<thead>
		<tr>
			<th width="80" >编号</th>
			<th width="80" >游戏</th>
			<th width="80" >mid</th>
			<th width="80" >昵称</th>
			<th >喇叭内容 </th>
			<th width="150" >时间</th>
		</tr>
	</thead>
	<tbody>
	<?php foreach(  $items as $item ):?>
		<tr target="id" rel="<?php echo $item['id']?>">
			<td><?php echo (int)$item['id'] ?></td>
			<td><?php echo  Config_Game::$game[(int)$item['gameid']] ?></td>
			<td><a title="用户信息" href="?m=user&p=account&navTabId=u-alist&mid=<?php echo (int)$item['mid'] ?>" target="navTab" style="text-decoration: underline;"><?php echo (int)$item['mid'] ?></a></td>
			<td><?php echo $item['mnick'] ?></td>
			<td><?php echo $item['content'] ?></td>
			<td><?php echo date("Y-m-d H:i:s",$item['time'] )?></td>
		</tr>
	<?php endforeach;?>
	</tbody>
</table>
</div>


