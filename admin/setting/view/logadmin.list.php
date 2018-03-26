<form id="pagerForm" method="post" action="?m=setting&p=logadmin&navTabId=logadmin">
	<input type="hidden" name="username" value="<?php echo $_REQUEST['username']?>" />
	<input type="hidden" name="act" value="<?php echo $_REQUEST['act']?>" />
	<input type="hidden" name="model" value="<?php echo $_REQUEST['model']?>" />
	<input type="hidden" name="stime" value="<?php echo $_REQUEST['stime']?>" />
	<input type="hidden" name="etime" value="<?php echo $_REQUEST['etime']?>" />
	<input type="hidden" name="pageNum" value="<?php echo $currentPage?>" />
	<input type="hidden" name="numPerPage" value="<?php echo $numPerPage?>" />
</form>

<div class="pageHeader">
	<form onsubmit="return navTabSearch(this);" action="?m=setting&p=logadmin&navTabId=logadmin" method="post">
	<div class="searchBar">
		<table class="searchContent">
			<tr>
				<td>管理员名称：</td>			
				<td>					
					<input style="float:left" type="text" value="<?php echo $_REQUEST['username'] ? $_REQUEST['username'] : '' ?>" name=username />
				</td>
				
				<td>模块：</td>			
				<td>					
					<input style="float:left" type="text" value="<?php echo $_REQUEST['model'] ? $_REQUEST['model'] : '' ?>" name=model />
				</td>
				
				<td>动作(act)：</td>			
				<td>					
					<input style="float:left" type="text" value="<?php echo $_REQUEST['act'] ? $_REQUEST['act'] : '' ?>" name=act />
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
				<th style="font-weight:bold;text-align:center" width="80">用户名</th>
				<th style="font-weight:bold;text-align:center" width="80">模块</th>
				<th style="font-weight:bold;text-align:center" width="80">页面</th>
				<th style="font-weight:bold;text-align:center" width="80">动作</th>
				<th style="font-weight:bold;text-align:center" width="180">请求内容</th>
				<th style="font-weight:bold;text-align:center" width="80">ip</th>
				<th style="font-weight:bold;text-align:center" width="80" >时间</th>
			</tr>
		</thead>
		<tbody>
		<?php foreach($items as $item):?>
			<tr target="id" rel="<?php echo $item['id']?>">				
				<td style="text-align:center"><?php echo $item['username'] ?></td>
				<td style="text-align:center"><?php echo $item['model'] ?></td>
				<td style="text-align:center"><?php echo $item['page'] ?></td>
				<td style="text-align:center"><?php echo $item['act'] ?></td>
				<td style="text-align:center"><?php echo $item['req']?></td>
				<?php $local =  Lib_Ip::find($item['ip'])?>
				<td style="text-align:center"><?php echo implode(' ', $local) ?></td>	
				<td style="text-align:center"><?php echo date("Y-m-d H:i:s",$item['ctime']);?></td>				
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
