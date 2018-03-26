<div class="pageContent">
<div class="panelBar">
<ul class="toolBar">
	<li><a class="add" href="?m=setting&p=versions&act=setView&navTabId=vlist" target="navTab" mask="true"><span>新增版本</span></a></li>
	<li><a class="delete" href="?m=setting&p=versions&act=del&id={id}&navTabId=vlist" target="ajaxTodo" title="你确定要删除吗？" warn="请选择关卡"><span>删除</span></a></li>
</ul>
</div>

<table class="table" width="100%" layoutH="138">
	<thead>
		<tr>
			<th width="80" >编号</th>
			<th width="120" >所属游戏</th>
			<th width="120" >客户端类型</th>
			<th width="150" >渠道</th>
			<th width="80">更新版本号</th>
			<th width="60" >更新方式 </th>
			<th  width="60" >更新URL</th>
			<th width="50" >更新描述</th>
			<th width="130" >更新时间</th>
			<th width="100">操作 </th>
		</tr>
	</thead>
	<tbody>
	<?php foreach(  $items as $item ):?>
		<tr target="id" rel="<?php echo $item['id']?>">
			<td><?php echo $item['id'] ?></td>
			<td><?php echo Config_Game::$game[$item['gameid']] ?></td>
			<td><?php echo $item['ctype'] ?></td>
			<td><?php echo $item['cid'] ?></td>
			<td><?php echo $item['con'].$item['versions'] ?></td>
			<td><?php echo $item['updatetype'] == 0 ? "可选更新" : "强制更新" ?></td>
			<td><?php echo $item['url'] ?></td>
			<td><?php echo $item['description'] ?></td>
			<td><?php echo date("Y-m-d H:i:s",$item['time']); ?></td>
			<td>
			<a class="delete" href="?m=setting&p=versions&act=del&id=<?php echo $item['id']?>&navTabId=vlist" target="ajaxTodo" title="你确定要删除这个关卡吗？" warn="请选择其中一个活动">
				删除 
			</a>	
			|		
			<a class="delete" href="?m=setting&p=versions&act=setView&id=<?php echo $item['id']?>&navTabId=vlist" target="navTab">
				修改
			</a>
			</td>
		</tr>
	<?php endforeach;?>
	</tbody>
</table>
</div>


