<form id="pagerForm" method="post" action="?m=monitor&p=message&navTabId=msg_list">
	<input type="hidden" name="status" value="<?php echo $_REQUEST['status']?>" />
	<input type="hidden" name="type" value="<?php echo $_REQUEST['type']?>" />
	<input type="hidden" name="stime" value="<?php echo $_REQUEST['stime']?>" />
	<input type="hidden" name="etime" value="<?php echo $_REQUEST['etime']?>" />
	<input type="hidden" name="pageNum" value="<?php echo $currentPage?>" />
	<input type="hidden" name="numPerPage" value="<?php echo $numPerPage?>" />
</form>

<div class="pageHeader">
	<form onsubmit="return navTabSearch(this);" action="?m=monitor&p=message&navTabId=msg_list" method="post">
	<div class="searchBar">
		<table class="searchContent">
			<tr>
				<td style="padding-right: 3px;">发送类型：</td>
				<td>					
					<select class="combox" name="type">
						<option <?php if($_REQUEST['type'] == 0):?>  selected="selected" <?php endif;?>  value="0">所有</option>
						<option <?php if($_REQUEST['type'] == 1):?>  selected="selected" <?php endif;?>  value="1">验证码</option>
						<option <?php if($_REQUEST['type'] == 2):?>  selected="selected" <?php endif;?>  value="2">账号密码</option>
					</select>
				</td>	
				<td style="padding-right: 3px;">状态：</td>
				<td>					
					<select class="combox" name="status">
						<option <?php if($_REQUEST['status'] == 0):?>   selected="selected" <?php endif;?>  value="0">所有</option>
						<option <?php if($_REQUEST['status'] == 1):?>   selected="selected" <?php endif;?>  value="1">成功</option>
						<option <?php if($_REQUEST['status'] == -1):?>  selected="selected" <?php endif;?>  value="-1">异常</option>

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
				<th style="font-weight:bold;text-align:center" width="150">时间</th>
				<th style="font-weight:bold;text-align:center" width="80">mid</th>
				<th style="font-weight:bold;text-align:center" width="180">号码</th>
				<th style="font-weight:bold;text-align:center" >内容</th>
				<th style="font-weight:bold;text-align:center" width="180" >状态</th>
			</tr>
		</thead>
		<tbody>
		<?php foreach($items as $item):?>
			<tr target="id" rel="<?php echo $item['id']?>">				
				<td style="text-align:center"><?php echo $item['ctime'] ?></td>
				<td style="text-align:center"><?php echo $item['mid'] ?></td>
				<td style="text-align:center"><?php echo $item['phoneno'] ?></td>
				<td style="text-align:center"><?php echo $item['content'] ?></td>
				<td style="text-align:center"><?php echo $item['status']?></td>			
			</tr>
		<?php endforeach;?>	
		</tbody>
	</table>
	<div class="panelBar">
		<div class="pages">
			<span>显示</span>
			<select class="combox" name="numPerPage" onchange="navTabPageBreak({numPerPage:this.value})">
				<option <?php if($_REQUEST['numPerPage'] == 30):?>  selected="selected" <?php endif;?> value="30">30</option>
				<option <?php if($_REQUEST['numPerPage'] == 50):?>  selected="selected" <?php endif;?> value="50">50</option>
				<option <?php if($_REQUEST['numPerPage'] == 100):?>  selected="selected" <?php endif;?> value="100">100</option>
				<option <?php if($_REQUEST['numPerPage'] == 200):?>  selected="selected" <?php endif;?> value="200">200</option>
			</select>
			<span>条，共<?php echo $totalCount?>条</span>
		</div>
		
		<div class="pagination" targetType="navTab" totalCount="<?php echo $totalCount?>" numPerPage="<?php echo $numPerPage?>" pageNumShown="10" currentPage="<?php echo $currentPage?>"></div>
	</div>
</div>
