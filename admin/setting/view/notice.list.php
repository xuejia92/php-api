<div class="pageContent">
<div class="panelBar">
<ul class="toolBar">
	<li><a class="add" href="?m=setting&p=notice&act=setView&navTabId=nlist" target="navTab" ><span>新增公告</span></a></li>
	<li><a class="delete" href="?m=setting&p=notice&act=del&id={id}&navTabId=nlist" target="ajaxTodo" title="你确定要删除吗？" warn="请选择关卡"><span>删除</span></a></li>
</ul>
</div>

<table class="table" width="100%" layoutH="138">
	<thead>
		<tr>
			<th width="80" >编号</th>
			<th width="130" >所属游戏</th>
			<th width="80" >客户端类型</th>
			<th width="100" >渠道</th>
			<th width="100">公告标题</th>
			<th width="300">公告内容 </th>
			<th width="60">更新URL</th>
			<th width="90" >开始时间</th>
			<th width="90" >结束时间</th>
			<th width="150">操作 </th>
		</tr>
	</thead>
	<tbody>
	<?php foreach(  $items as $item ):?>
		<tr target="id" rel="<?php echo $item['id']?>">
			<td><?php echo $item['id'] ?></td>
			<td><?php echo $item['gameid'] ?></td>
			<td><?php echo $item['ctype'] ?></td>
			<td><?php echo $item['cid']  ?></td>
			<td><?php echo $item['title'] ?></td>
			<td><?php echo $item['content'] ?></td>
			<td><?php echo $item['url'] ?></td>
			<td><?php echo date("Y-m-d H:i:s",$item['stime'] )?></td>
			<td><?php echo date("Y-m-d H:i:s",$item['etime'] )?></td>
			<td>
			<a class="delete" href="?m=setting&p=notice&act=del&id=<?php echo $item['id']?>&navTabId=nlist" target="ajaxTodo" title="你确定要删除这个关卡吗？" warn="请选择其中一个活动">
				删除 
			</a>	
			|		
			<a class="delete" href="?m=setting&p=notice&act=setView&id=<?php echo $item['id']?>&navTabId=nlist" target="navTab">
				修改
			</a>
			</td>
		</tr>
	<?php endforeach;?>
	</tbody>
</table>
</div>


