<div class="pageContent">
<div class="pageHeader">
	<form onsubmit="return navTabSearch(this);" action="?m=user&p=behavior&navTabId=behavior-list" method="post">
	<div class="searchBar">
		<table class="searchContent">
			<tr>
				<td>
				<label>游戏：</label>
				<select name="gameid" >
					<option value="0">请选择</option>
					<?php foreach ($aGame as $gameId=>$gameName):?>
						<option  <?php if($gameId == $_REQUEST['gameid']):?> selected="selected" <?php endif;?> value="<?php echo $gameId?>"><?php echo $gameName;?></option>
					<?php endforeach;?>
				</select>
				</td>
				<td>mid：</td>			
				<td>					
					<input type="text" value="<?php echo $_REQUEST['mid'] ? $_REQUEST['mid'] : '' ?>" name='mid' />
				</td>
				<td>日期：</td>	
				<td>					
					<input type="text" value="<?php echo $_REQUEST['day'] ? $_REQUEST['day'] : date("Y-m-d") ?>" name="day" />
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
<table class="table" width="100%" layoutH="138">
	<thead>
		<tr>
			<th style="font-weight:bold;" width="10" >序号</th>
			<th style="font-weight:bold;" width="20" >时间</th>
			<th style="font-weight:bold;" width="30" >动作</th>
			<th style="font-weight:bold;" width="20" >cid</th>
			<th style="font-weight:bold;" width="20" >pid</th>
			<th style="font-weight:bold;" width="20" >ctype</th>
			<th style="font-weight:bold;" width="20" >gameid</th>
			<th style="font-weight:bold;" width="20" >versions</th>
			<th style="font-weight:bold;" width="20" >操作 </th>
		</tr>
	</thead>
	<tbody>
	<?php if($items):?>
	<?php $i=0;?>
	<?php foreach(  $items as $item ):?>
		<?php $i++ ;?>
		<tr target="id" rel="<?php echo $item['id']?>">
			<td><?php echo $i ?></td>
			<td><?php echo date("Y-m-d H:i:s",$item['time'])  ?></td>
			<td style="color: <?php echo  is_numeric($item['act']) ? "blue" : "red"?> "><?php echo $aBehavior[$item['act']] ?  $aBehavior[$item['act']] : $item['act'] ?></td>
			<?php $item['result'] = preg_replace('/{|}|"/', '', $item['result']);?>
			<td><?php echo $aCid[$item['request']['cid']] ?></td>
			<td><?php echo $item['request']['pid'] ?></td>
			<td><?php echo $aCtype[$item['request']['ctype']] ?></td>
			<td><?php echo $aGame[$item['request']['gameid']] ?></td>
			<td><?php echo $item['request']['versions'] ?></td>
			<td><a href="?m=user&p=behavior&act=showact&result=<?php echo($item['result']);?>&request=<?php echo base64_encode(json_encode($item['request']))?>&response=<?php echo base64_encode(json_encode($item['response'])); ?>" target="dialog" rel="showact" width="645" height="370" fresh="false">查看详细</a></td>
		</tr>		
	<?php endforeach;?>
	<?php endif;?>
	</tbody>
</table>
</div>


