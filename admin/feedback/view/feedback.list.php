<form id="pagerForm" method="post" action="?m=feedback&p=list&navTabId=f_list">
	<input type="hidden" name="mid" value="<?php echo $_REQUEST['mid']?>">
	<input type="hidden" name="gameid" value="<?php echo $_REQUEST['gameid']?>">
	<input type="hidden" name="ctype" value="<?php echo $_REQUEST['ctype']?>" />
	<input type="hidden" name="cid" value="<?php echo $_REQUEST['cid']?>" />
	<input type="hidden" name="status" value="<?php echo $_REQUEST['status']?>" />
	<input type="hidden" name="stime" value="<?php echo $_REQUEST['stime']?>" />
	<input type="hidden" name="etime" value="<?php echo $_REQUEST['etime']?>" />
	<input type="hidden" name="pageNum" value="<?php echo $currentPage?>" />
	<input type="hidden" name="numPerPage" value="<?php echo $numPerPage?>" />
</form>

<div class="pageHeader">
	<form onsubmit="return navTabSearch(this);" action="?m=feedback&p=list&navTabId=f_list" method="post">
	<div class="searchBar">
		<table class="searchContent">
			<tr>
				<td style="padding-right: 3px;">游戏：</td>
				<td>					
					<select class="combox" name="gameid">
					<option value="">请选择</option>
						<?php foreach (Config_Game::$game as $gameid=>$gameName):?>
							<option <?php if($gameid == $_REQUEST['gameid']):?> selected="selected" <?php endif;?> value="<?php echo $gameid?>"><?php echo $gameName;?></option>
						<?php endforeach;?>
						<option <?php if(100 == $_REQUEST['gameid']):?> selected="selected" <?php endif;?> value="100">微信</option>
					</select>
				</td>
			    <td style="padding-right: 3px;">客户端类型：</td>
				<td>					
					<select class="combox" name="ctype">
					<option value="">所有</option>
						<?php foreach ($aCtype as $ctype=>$clientName):?>
							<option <?php if($ctype == $_REQUEST['ctype']):?> selected="selected" <?php endif;?> value="<?php echo $ctype?>"><?php echo $clientName;?></option>
						<?php endforeach;?>
					</select>
				</td>
				<td style="padding-right: 3px;">渠道：</td>
				<td>
					<select class="combox" name="cid">
						<option value="">所有</option>
						<?php foreach ($aCid as $cid=>$cname):?>
							<option <?php if($cid == $_REQUEST['cid']):?> selected="selected" <?php endif;?> value="<?php echo $cid?>"><?php echo $cname;?></option>
						<?php endforeach;?>
					</select>
				</td>
				
				<td style="padding-right: 3px;">状态：</td>
				<td>					
					<select class="combox" name="status">
						<option <?php if($_REQUEST['status'] == 0):?>  selected="selected" <?php endif;?>  value="0">未回复</option>
						<option <?php if($_REQUEST['status'] == 1):?>  selected="selected" <?php endif;?>  value="1">已回复</option>
						<option <?php if($_REQUEST['status'] == 2):?>  selected="selected" <?php endif;?>  value="2">已忽略</option>
					</select>
				</td>	
				<td>开始时间：</td>			
				<td>					
					<input style="float:left" type="text" value="<?php echo $_REQUEST['stime'] ? $_REQUEST['stime'] : date("Y-m-d",strtotime("-15 days")) ?>" name=stime class="date" dateFmt="yyyy-MM-dd" readonly="true"/>
					<a style="float:left" class="inputDateButton" href="javascript:;">选择</a>
				</td>
				<td>结束时间：</td>	
				<td>					
					<input style="float:left" type="text" value="<?php echo $_REQUEST['etime'] ? $_REQUEST['etime'] : date("Y-m-d") ?>" name=etime class="date" dateFmt="yyyy-MM-dd" readonly="true"/>
					<a style="float:left" class="inputDateButton" href="javascript:;">选择</a>
				</td>		
				
				<td style="padding-right: 3px;">mid：</td>
				<td>					
					<input type="text" value="<?php echo $_REQUEST['mid'] ? $_REQUEST['mid'] : "" ?>" name=mid />
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
		<ul class="toolBar">
			<li><a class="delete" href="?m=feedback&p=list&act=ignore&id={id}&navTabId=f_list" target="ajaxTodo" title="确定要忽略吗?"><span>忽略</span></a></li>
			<li><a class="icon" href="?m=feedback&p=list&act=excel&gameid=<?php echo $_REQUEST['gameid']?>&ctype=<?php echo $_REQUEST['ctype']?>&cid=<?php echo $_REQUEST['cid']?>&status=<?php echo $_REQUEST['status']?>&stime=<?php echo $_REQUEST['stime']?>&etime=<?php echo $_REQUEST['etime']?>&mid=<?php echo $_REQUEST['mid']?>" target="dwzExport" targetType="navTab" title="实要导出这些记录吗?"><span>导出EXCEL</span></a></li>	
		</ul>
	</div>
	<table class="table" width="100%" layoutH="170">
		<thead>
			<tr>
				<th width="80">编号</th>
				<th width="80">游戏</th>
				<th width="80">mid</th>
				<th width="80">昵称</th>
				<th width="80">渠道</th>
				<th >内容</th>
				<th width="150">时间</th>
				<th width="80" >状态</th>
				<th width="80">操作</th>
			</tr>
		</thead>
		<tbody>
		<?php foreach($items as $item):?>
			<tr target="id" rel="<?php echo $item['id']?>">				
				<td><?php echo $item['id'] ?></td>
				<td><?php echo $item['gameid']==100? '微信' : Config_Game::$game[$item['gameid']];?></td>
				<td><?php echo $item['mid'] ?></td>
				<td><?php echo $item['mnick'] ?></td>
				<td><?php echo $item['cname'] ?></td>
				<td><?php echo $item['content'] ? $item['content'] : '图片反馈' ?></td>
				<td><?php echo date("Y-m-d H:i:s",$item['ctime']) ?></td>
				<td><?php echo $item['status'] == 0 ? "未回复" : ($item['status'] == 1 ? "已回复":"已忽略")?></td>
				<td>
				<a style="color:red" class="edit" href="?m=feedback&p=reply&act=get&id=<?php echo $item['id']?>&mid=<?php echo $item['mid']?>" target="navTab" title="回复【<?php echo $item['mnick'];?>】" rel="reply_<?php echo $item['mid']?>"  >
				<?php if($item['status']==0):?>回复<?php else:?> 查看<?php endif;?>
				</a>	
				|		
				<a class="delete" href="?m=feedback&p=list&act=ignore&id=<?php echo $item['id']?>&navTabId=f_list" target="ajaxTodo" title="确定要忽略吗?" >
				<?php if($item['status']==0):?>忽略<?php else:?>--<?php endif;?>
				</a>
				</td>				
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
