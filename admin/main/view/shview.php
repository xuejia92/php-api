<?php 

$width = $_GET['gameid'] == 6 ? 1000 : 800;

?>

<div class="pageContent">
		<table class="table" width="100%" layoutH="138">
			<thead>
				<tr>
					<th style="font-weight:bold;" width="280">在玩人员</th>
					<th style="font-weight:bold;" width="80">桌子ID</th>
					<th style="font-weight:bold;" width="80">操作</th>
				</tr>
			</thead>
			<tbody>
					
			<?php foreach($tables as $k=>$table):?>
				<tr >				
					<td><?php echo $info[$k] ?></td>
					<td><?php echo $table ?></td>
					<td><a href="?&p=sh&act=getcard&gameid=<?php echo $_GET['gameid']?>&table=<?php echo $table;?>" target="dialog" width="<?php echo $width?>" height="500">查看底牌</a></td>						
				</tr>
			<?php endforeach;?>	
			</tbody>
		</table>

</div>