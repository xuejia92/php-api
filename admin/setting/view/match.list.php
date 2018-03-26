<div class="pageContent">
<div class="panelBar">
</div>

<table class="table" width="100%" layoutH="138">
	<thead>
		<tr>
			<th width="80" >房间名称</th>
			<th width="150">台费(底注的百分比) </th>
			<th width="150">进入最低金币数</th>
			<th width="150">进入最高金币数</th>
			<th width="150">梭哈最大上限</th>
			<th width="150">输完保留金币数</th>
			<th width="150">操作 </th>
		</tr>
	</thead>
	<tbody>
	<?php for($i=1;$i<=5;$i++):?>
		<tr target="id" rel="<?php echo $items[$i]['id']?>">
			<td><?php echo $items[$i]['name'] ?></td>
			<td><?php echo  $items[$i]['raterent'] ?></td>
			<td><?php echo  $items[$i]['minmoney'] ?></td>
			<td><?php echo  $items[$i]['maxmoney'] ?></td>
			<td><?php echo  $items[$i]['carrycoin'] ?></td>
			<td><?php echo  $items[$i]['retaincoin'] ?></td>
			<td>	
			<a class="edit" href="?m=setting&p=room&act=setView&id=<?php echo $i?>&navTabId=room" target="dialog">
				修改
			</a>
			</td>
		</tr>
	<?php endfor;?>
	</tbody>
</table>
</div>


