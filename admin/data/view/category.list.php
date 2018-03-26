<div class="pageContent">
<div class="panelBar">
<ul class="toolBar">
	<li><a class="add" href="?m=data&p=category&act=setView&navTabId=data_cat" target="dialog" ><span>新增分类</span></a></li>
</ul>
</div>

<table class="table" width="100%" layoutH="138">
	<thead>
		<tr>
			<th width="80" >编号</th>
			<th width="80" >分类名称</th>
			<th width="80">操作 </th>
		</tr>
	</thead>
	<tbody>
	<?php foreach(  $items as $item ):?>
		<tr target="id" rel="<?php echo $item['catid']?>">
			<td><?php echo $item['catid'] ?></td>
			<td><?php echo $item['catname'] ?></td>
			<td>
			<a class="delete" href="?m=data&p=category&act=del&id=<?php echo $item['catid']?>&navTabId=data_cat" target="ajaxTodo" title="你确定要删除这个关卡吗？" warn="请选择其中一个活动">
				删除 
			</a>	
			|		
			<a class="delete" href="?m=data&p=category&act=setView&id=<?php echo $item['catid']?>&navTabId=data_cat" target="dialog">
				修改
			</a>
			</td>
		</tr>
	<?php endforeach;?>
	</tbody>
</table>
</div>


