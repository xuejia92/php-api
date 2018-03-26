
<div class="pageHeader">
	<form onsubmit="return navTabSearch(this);" action="?m=user&p=rank&navTabId=rank" method="post">
	<div class="searchBar">
		<table class="searchContent">
			<tr>
				<td>游戏：</td>
				<td>
					<select class="combox" name="gameid">
						<option <?php if(0 == $_REQUEST['gameid']):?> selected="selected" <?php endif;?> value="<?php echo 0?>">总榜</option>
						<?php foreach(Config_Game::$game as $gameid=>$gname):?>
							<option <?php if($gameid == $_REQUEST['gameid']):?> selected="selected" <?php endif;?> value="<?php echo $gameid?>"><?php echo $gname?></option>
						<?php endforeach;?>
					</select>
				</td>
					
				<td>类型：</td>
				<td>	
					<select class="combox" name="type">
						<option <?php if("money" == $_REQUEST['type']):?> selected="selected" <?php endif;?> value="money">金币</option>
						<option <?php if("roll"  == $_REQUEST['type']):?> selected="selected" <?php endif;?> value="roll">乐卷</option>
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
	<div class="panelBar">
	</div>
	<table class="table" width="100%" layoutH="138">
		<thead>
			<tr>
				<th style="font-weight:bold" width="80">Top</th>
				<th style="font-weight:bold" width="80">mid</th>
				<th style="font-weight:bold" width="80">昵称</th>
				<th style="font-weight:bold" width="80">金币数</th>
				<th style="font-weight:bold" width="80">乐卷数</th>
			</tr>
		</thead>
		<tbody>
		<?php if($items):?>
		<?php foreach($items as $k=>$item):?>
			<tr target="id" rel="<?php echo $item['id']?>">	
				<td> <?php echo $k + 1 ?> </td>
				<td><a title="用户信息" href="?m=user&p=account&navTabId=u-alist&mid=<?php echo $item['mid'] ?>" target="navTab" style="text-decoration: underline;"><?php echo $item['mid'] ?></a></td>			
				<td><?php echo $item['mnick'] ?></td>
				<td <?php if("money" == $_REQUEST['type'] || !$_REQUEST['type'] ):?>style="color:red" <?php endif;?>><?php echo $item['money'] + $item['freezemoney'] ?></td>
				<td <?php if("roll" == $_REQUEST['type']):?>style="color:red" <?php endif;?>><?php echo $item['roll'] ?></td>
			</tr>
		<?php endforeach;?>	
		<?php endif;;?>
		</tbody>
	</table>
</div>
