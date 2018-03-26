
<div class="pageHeader">
	<form onsubmit="return navTabSearch(this);" action="?m=data&p=show&navTabId=f_list" method="post">
	<input type="hidden" name=cid value=<?php echo $_REQUEST['cid']?>>
	<div class="searchBar">
		<table class="searchContent">		
			<tr>
				<td style="padding-right: 3px;">客户端包：</td>
				<td>
					<select class="combox" name="pid">
						<option value="">所有</option>
						<?php foreach ($aPid as $p):?>
							<option <?php if($_REQUEST['pid'] == $p['id']):?> selected="selected" <?php endif;?> value="<?php echo $p['id']?>"><?php echo $p['pname'];?></option>
						<?php endforeach;?>
					</select>
				</td>
				<td>开始时间：</td>			
				<td>					
					<input style="float:left" type="text" value="<?php echo $_REQUEST['stime'] ? $_REQUEST['stime'] : $stime ?>" name=stime class="date" dateFmt="yyyy-MM-dd" readonly="true"/>
					<a style="float:left" class="inputDateButton" href="javascript:;">选择</a>
				</td>
				<td>结束时间：</td>	
				<td>					
					<input style="float:left" type="text" value="<?php echo $_REQUEST['etime'] ? $_REQUEST['etime'] : date("Y-m-d") ?>" name=etime class="date" dateFmt="yyyy-MM-dd" readonly="true"/>
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
			<th  width="90">时间</th>
			<?php foreach($aItem as $item):?>
				<th width="80"><?php echo $item['itemname']?></th>
			<?php endforeach;?>
			</tr>
		</thead>
		<tbody>
		<?php foreach($aDate as $date):?>
			<tr>
				<td><?php echo $date?></td>
				<?php foreach($aContent[$date] as $content):?>
					<td><?php echo $content?></td>
				<?php endforeach;?>
			</tr>
		<?php endforeach; ?>
		</tbody>
	</table>
</div>
