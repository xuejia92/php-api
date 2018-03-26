
<form id="pagerForm" method="post" action="?m=data&p=item&navTabId=data_item">
	<input type="hidden" name="catid" value="<?php echo $_REQUEST['catid']?>" />
</form>

<div class="pageHeader">
	<form onsubmit="return navTabSearch(this);" action="?m=data&p=item&navTabId=data_item" method="post">
	<div class="searchBar">
		<table class="searchContent">
			<tr>
			    <td style="padding-right: 3px;">统计分类：</td>
				<td>					
					<select name="catid">
						<?php foreach ($cats as $catid=>$catname):?>
							<option <?php if($catid == $_REQUEST['catid']):?> selected="selected" <?php endif;?> value="<?php echo $catid?>"><?php echo $catname;?></option>
						<?php endforeach;?>
					</select>
				</td>
			</tr>
		</table>

		<div class="subBar">
			<ul>
				<li><div class="buttonActive"><div class="buttonContent"><button type="submit">检索</button></div></div></li>
			</ul>
		</div>
	</div>
	</form>
</div>

<div class="pageContent">
<form method="post"  action="?m=data&p=item&act=sort&navTabId=data_item" class="pageForm required-validate" onsubmit="return validateCallback(this, navTabAjaxDone)">
<div class="panelBar">
	<ul class="toolBar">
		<li><div class="buttonActive"><div class="buttonContent"><button type="submit">重新排序</button></div></div></li>
		<li><a class="add" href="?m=data&p=item&act=setView&navTabId=data_item&catid=<?php echo $_REQUEST['catid']?>" target="dialog" ><span>新增统计项</span></a></li>
	</ul>
</div>
<table class="table" width="100%" layoutH="138">
	<thead>
		<tr>
			<th style="font-weight:bold;" width="50" >排序</th>
			<th style="font-weight:bold;"  width="80" >编号</th>
			<th style="font-weight:bold;"  width="80" >统计项名称</th>
			<th style="font-weight:bold;"  width="80" >分类名称</th>
			<th style="font-weight:bold;"  width="80" >状态</th>
			<th style="font-weight:bold;"  width="80">操作 </th>
		</tr>
	</thead>
	<tbody>
	<?php foreach(  $items as $k=>$item ):?>
		<tr target="id" rel="<?php echo $item['itemid']?>">
			<input type="hidden" value="<?php echo $item['itemid'];?>" name="ids[]">	
			<td> <input type="text" size="5" value="<?php echo $k ;?>" name="pos[]"></td>	
			<td><?php echo $item['itemid'] ?></td>
			<td><?php echo $item['itemname'] ?></td>
			<td><?php echo $item['catname'] ?></td>
			<td><?php echo $item['status'] == 0 ? '不显示' : '显示' ?></td>
			<td>
			<a class="delete" href="?m=data&p=item&act=updateStatus&status=<?php echo $item['status'] == 0 ? 1 : 0 ?>&id=<?php echo $item['itemid']?>&navTabId=data_item" target="ajaxTodo" title="你确定要更改显示状态吗？" warn="请选择其中一个">
				<?php echo  $item['status'] == 0 ? '显示' : '不显示'?> 
			</a>	
			|
			<a class="delete" href="?m=data&p=item&act=del&id=<?php echo $item['itemid']?>&navTabId=data_item" target="ajaxTodo" title="你确定要删除吗？" warn="请选择其中一个">
				删除 
			</a>	
			|		
			<a class="delete" href="?m=data&p=item&act=setView&id=<?php echo $item['itemid']?>&navTabId=data_item" target="dialog">
				修改
			</a>
			</td>
		</tr>
	<?php endforeach;?>
	</tbody>
</table>
</form>
</div>


