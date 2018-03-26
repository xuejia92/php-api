
<div class="pageHeader">
	 <form onsubmit="return navTabSearch(this);" action="?m=data&p=task&act=overview&gameid=<?php echo $_REQUEST['gameid'] ?>&navTabId=<?php echo $_REQUEST['navTabId'] ?>" method="post">
	<div class="searchBar">
		<table class="searchContent">		
			<tr>
				<td style="padding-right: 3px;">房间场次：</td>
				<td>
					<select  class="combox" name="roomid">
						<option value="">所有</option>
						<?php foreach($roomConfig as $roomid=>$roomname):?>
							<option <?php if($_REQUEST['roomid'] == $roomid):?> selected="selected" <?php endif;?> value="<?php echo $roomid?>"><?php echo $roomname;?></option>
						<?php endforeach;?>
					</select>
				</td>
				
				<td>时间：</td>			
				<td>					
					<input style="float:left" type="text" value="<?php echo $_REQUEST['ctime'] ? $_REQUEST['ctime'] : date("Y-m-d",strtotime('-1 days')) ?>" name=ctime class="date" dateFmt="yyyy-MM-dd" readonly="true"/>
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
	<table class="table" width="100%" layoutH="138">
		<thead>
			<tr>
			<?php foreach($aItem as $item):?>
				<th style="font-weight:bold;text-align:center;" width="80"><?php echo $item?></th>
			<?php endforeach;?>
			</tr>
		</thead>
		<tbody>
			<?php foreach($aContent as $row):?>
			<tr>
				<td  style="text-align:center"><?php echo $row['taskid']?></td>
				<td  style="text-align:center"><?php echo $row['itemname']?></td>
				<td  style="text-align:center"><?php echo $row['minmoney']?>-<?php echo $row['maxmoney']?></td>
				<td  style="text-align:center"><?php echo $row['issued']?></td>
				<td  style="text-align:center"><?php echo $row['complete']?></td>
				<td  style="text-align:center"><?php echo $row['proportation']?></td>
				<td  style="text-align:center"><?php echo $row['roll']?></td>
			</tr>
			<?php endforeach;?>
			<tr>
				<th style="font-weight:bold;text-align:center;" width="80">小计</th>
				<th style="font-weight:bold;text-align:center;" width="80">-</th>
				<th style="font-weight:bold;text-align:center;" width="80">-</th>
				<th style="font-weight:bold;text-align:center;" width="80"><?php echo $count['issued']?></th>
				<th style="font-weight:bold;text-align:center;" width="80"><?php echo $count['complete']?></th>
				<th style="font-weight:bold;text-align:center;" width="80"><?php echo $count['proportation']?></th>
				<th style="font-weight:bold;text-align:center;" width="80"><?php echo $count['roll']?></th>
			</tr>
		</tbody>	
	</table>
	
</div>


