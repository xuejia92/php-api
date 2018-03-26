<div class="pageContent">
<?php if($items):?>	
	<table class="table" width="100%" layoutH="138">
		<thead>
			<tr>
			    <th width="80" style="text-align: center;">序号(共<?php echo count($items)?>个)</th>
				<th width="130">mid</th>
				<th >昵称</th>
				<th > <?php echo $_REQUEST['deviceno'] ? "机器码" : "IP" ?></th>
				<th width="120">账号类型</th>
				<th width="200">操作</th>
			</tr>
		</thead>
		<tbody>
		<?php foreach($items as $key=>$item):?>
			<tr target="id" rel="<?php echo $item['mid']?>">
			    <td style="text-align: center;"><?php echo $key+1?></td>
				<td <?php echo $item['dflag'] ? "style=color:red" : ""?>><?php echo $item['dflag'] ? $item['mid']."(已封号)" : $item['mid']; ?></td>
				<td><?php echo $item['mnick']?></td>
				<td style="color:red"><?php echo $_REQUEST['deviceno'] ? $item['deviceno'] : $item['ip']?></td>
				<td><?php foreach ($aSid as $sid=>$accountType):?>
					<?php if($sid == $item['sid']):?><?php echo $accountType ?><?php endif;?>  
					<?php endforeach;?></td>
				<td> 
					<?php $aGame = array_keys($item['mtime'],true)?>
					<?php foreach ($aGame as $gameid):?>
						<a style="color:red" class="edit" href="?m=user&p=account&act=detail&mid=<?php echo $item['mid']?>&navTabId=u-detail&gameid=<?php echo $gameid?>" rel="u-detail" target="navTab" title="明细-<?php echo Config_Game::$game[$gameid]?>"><?php echo Config_Game::$game[$gameid]?></a>
					<?php endforeach;?>
				</td>
			</tr>
		<?php endforeach;?>
		
		</tbody>
	</table>
<?php endif;?>	
</div>
