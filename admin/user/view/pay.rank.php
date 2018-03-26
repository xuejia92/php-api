
<div class="pageHeader">
	<form onsubmit="return navTabSearch(this);" action="?m=user&p=rank&act=getpayrank&navTabId=getpayrank" method="post">
	<div class="searchBar">
		<table class="searchContent">
			<tr>
				<td>游戏：</td>
				<td>
					<select class="combox" name="gameid">
						<option <?php if(0 == $_REQUEST['gameid']):?> selected="selected" <?php endif;?> value="<?php echo 0?>">历史总榜</option>
						<?php foreach(Config_Game::$game as $gameid=>$gname):?>
							<option <?php if($gameid == $_REQUEST['gameid']):?> selected="selected" <?php endif;?> value="<?php echo $gameid?>"><?php echo $gname?></option>
						<?php endforeach;?>
					</select>
				</td>
					
				<td>开始时间：</td>			
				<td>					
					<input id="s_<?php echo $_REQUEST['navTabId'] ?>" style="float:left" type="text" value="<?php echo $_REQUEST['stime'] ? $_REQUEST['stime'] : date("Y-m-d") ?>" name=stime class="date" dateFmt="yyyy-MM-dd" readonly="true"/>
					<a style="float:left" class="inputDateButton" href="javascript:;">选择</a>
				</td>
				<td>结束时间：</td>	
				<td>					
					<input id="e_<?php echo $_REQUEST['navTabId'] ?>" style="float:left" type="text" value="<?php echo $_REQUEST['etime'] ? $_REQUEST['etime'] : date("Y-m-d") ?>" name=etime class="date" dateFmt="yyyy-MM-dd" readonly="true"/>					
					<a style="float:left" class="inputDateButton" href="javascript:;">选择</a>
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
				<th style="font-weight:bold" width="80">金额</th>
				<th style="font-weight:bold" width="80">下单次数</th>
			</tr>
		</thead>
		<tbody>
		<?php if($items):?>
		<?php foreach($items as $k=>$item):?>
			<tr target="id" rel="<?php echo $item['id']?>">	
				<td> <?php echo $k + 1 ?> </td>
				<td><a title="用户信息" href="?m=user&p=account&navTabId=u-alist&mid=<?php echo $item['mid'] ?>" target="navTab" style="text-decoration: underline;"><?php echo $item['mid'] ?></a></td>			
				<td><?php echo $item['mnick'] ?></td>
				<td style="color:red"> <?php echo $item['amount']; ?></td>
				<td > <?php echo $item['times']; ?></td>
			</tr>
		<?php endforeach;?>	
		<?php endif;;?>
		</tbody>
	</table>
</div>
