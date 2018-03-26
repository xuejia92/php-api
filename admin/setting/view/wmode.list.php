<div class="pageContent">
<div class="panelBar">
<ul class="toolBar">
	<li><a class="add" href="?m=setting&p=wmode&act=setView&navTabId=wlist" target="dialog" mask="true" width="800" height="580"><span>新增wmode</span></a></li>
	<li><a class="delete" href="?m=setting&p=wmode&act=del&id={id}&navTabId=wlist" target="ajaxTodo" title="你确定要删除吗？" warn="请选择关卡"><span>删除</span></a></li>
</ul>
</div>

<table class="table" width="100%" layoutH="138">
	<thead>
		<tr>
			<th width="80" >wmode</th>
			<th width="200" >后台显示</th>
			<th width="200">前端显示</th>
			<th width="200">操作</th>
		</tr>
	</thead>
	<tbody>
	<?php foreach(  $items as $item ):?>
		<tr target="id" rel="<?php echo $item['id']?>">
			<td><?php echo $item['id'] ?></td>
			<td><?php echo $item['admindesc'] ?></td>
			<td><?php echo $item['gamedesc'] ?></td>
			<td>
			<a class="delete" href="?m=setting&p=wmode&act=del&id=<?php echo $item['id']?>&navTabId=wlist" target="ajaxTodo" title="你确定要删除这个关卡吗？" warn="请选择其中一个活动">
				删除 
			</a>	
			|		
			<a class="delete" href="?m=setting&p=wmode&act=setView&id=<?php echo $item['id']?>&navTabId=wlist" target="dialog" width="800" height="580" >
				修改
			</a>
			</td>
		</tr>
	<?php endforeach;?>
	</tbody>
</table>
</div>


