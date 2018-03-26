<div class="pageContent">
<div class="panelBar">
<ul class="toolBar">
	<li><a class="add" href="?m=setting&p=privilege&act=setView&navTabId=privilege" target="dialog" mask="true" width="800" height="580"><span>新增管理员</span></a></li>
	<li><a class="delete" href="?m=setting&p=privilege&act=del&id={id}&navTabId=privilege" target="ajaxTodo" title="你确定要删除吗？" warn="请选择关卡"><span>删除</span></a></li>
</ul>
</div>

<table class="table" width="100%" layoutH="138">
	<thead>
		<tr>
			<th width="80" >编号</th>
			<th width="100" >用户名</th>
			<th width="200" >权限</th>
			<th width="80">操作 </th>
		</tr>
	</thead>
	<tbody>
	<?php foreach(  $items as $item ):?>
		<tr target="id" rel="<?php echo $item['id']?>">
			<td><?php echo $item['id'] ?></td>
			<td><?php echo $item['username'] ?></td>
			<td><?php echo $item['privilege'] ?></td>
			<td>
			<a class="delete" href="?m=setting&p=privilege&act=del&id=<?php echo $item['id']?>&navTabId=privilege" target="ajaxTodo" title="你确定要删除这个成员吗？" warn="请选择其中一个活动">
				删除 
			</a>	
			|		
			<a class="delete" href="?m=setting&p=privilege&act=setView&id=<?php echo $item['id']?>&navTabId=privilege" target="dialog" width="800" height="580" >
				修改
			</a>
			</td>
		</tr>
	<?php endforeach;?>
	</tbody>
</table>
</div>


