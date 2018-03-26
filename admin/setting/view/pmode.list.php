<div class="pageContent">
<form method="post"  action="?m=setting&p=pmode&act=sort&navTabId=pmodelist" class="pageForm required-validate" onsubmit="return validateCallback(this, navTabAjaxDone)">
<div class="panelBar">
	<ul class="toolBar">
		<li><div class="buttonActive"><div class="buttonContent"><button type="submit">重新排序</button></div></div></li>
		<li><a class="add" href="?m=setting&p=pmode&act=setView&navTabId=pmodelist" target="dialog" mask="true" width="800" height="580"><span>新增支付渠道(pmode)</span></a></li>
		<li><a class="delete" href="?m=setting&p=payname&act=del&id={id}&navTabId=pmodelist" target="ajaxTodo" title="你确定要删除吗？" warn="请选择支付渠道"><span>删除</span></a></li>
	</ul>
</div>

<table class="table" width="100%" layoutH="138">
	<thead>
		<tr>
			<th style="font-weight:bold;" width="50" >排序</th>
			<th style="font-weight:bold;" width="80" >pmode</th>
			<th style="font-weight:bold;" width="200" >支付渠道名称</th>
			<th style="font-weight:bold;"  width="80" >状态</th>
			<th style="font-weight:bold;" width="150">操作 </th>
		</tr>
	</thead>
	<tbody>
	<?php foreach(  $items as $k=>$item ):?>
		<tr target="id" rel="<?php echo $item['id']?>">
			<input type="hidden" value="<?php echo $item['id'];?>" name="ids[]">	
			<td> <input type="text" size="5" value="<?php echo $k ;?>" name="pos[]"></td>	
			<td><?php echo $item['id'] ?></td>
			<td><?php echo $item['payname'] ?></td>
			<td><?php echo $item['status'] == 0 ? '不显示' : '显示' ?></td>
			<td>
			<a class="delete" href="?m=setting&p=pmode&act=updateStatus&status=<?php echo $item['status'] == 0 ? 1 : 0 ?>&id=<?php echo $item['id']?>&navTabId=pmodelist" target="ajaxTodo" title="你确定要更改显示状态吗？" warn="请选择其中一个">
				<?php echo  $item['status'] == 0 ? '显示' : '不显示'?> 
			</a>	
			|
			<a class="delete" href="?m=setting&p=pmode&act=del&id=<?php echo $item['id']?>&navTabId=pmodelist" target="ajaxTodo" title="你确定要删除这个关卡吗？" warn="请选择其中一个活动">
				删除 
			</a>	
			|		
			<a class="delete" href="?m=setting&p=pmode&act=setView&id=<?php echo $item['id']?>&navTabId=pmodelist" target="dialog" width="800" height="580" >
				修改
			</a>
			</td>
		</tr>
	<?php endforeach;?>
	</tbody>
</table>
</div>


