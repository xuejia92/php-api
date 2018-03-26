<form id="pagerForm" method="post" action="?m=feedback&p=send&navTabId=f_send">
	<input type="hidden" name="gameid" value="<?php echo $_REQUEST['gameid']?>">
	<input type="hidden" name="mid" value="<?php echo $_REQUEST['mid']?>" />
	<input type="hidden" name="stime" value="<?php echo $_REQUEST['stime']?>" />
	<input type="hidden" name="etime" value="<?php echo $_REQUEST['etime']?>" />
	<input type="hidden" name="pageNum" value="<?php echo $currentPage?>" />
	<input type="hidden" name="numPerPage" value="<?php echo $numPerPage?>" />
</form>

<div class="pageHeader">
	<form onsubmit="return navTabSearch(this);" action="?m=feedback&p=send&navTabId=f_send" method="post">
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
					</select>
				</td>
				<td style="padding-right: 3px;">mid：</td>
				<td>					
					<input style="float:left" type="text" value="<?php echo $_REQUEST['mid'] ? $_REQUEST['mid'] :'' ?>" name=mid  />
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
			<li><a class="add" href="?m=feedback&p=send&act=setView&navTabId=f_send" target="dialog" ><span>向某个玩家发消息</span></a></li>
		</ul>
	</div>
	<table class="table" width="100%" layoutH="138">
		<thead>
			<tr>
				<th width="80">游戏</th>
				<th width="80">mid</th>
				<th >内容</th>
				<th width="150">时间</th>
				<th width="80">操作</th>
			</tr>
		</thead>
		<tbody>
		<?php foreach($items as $item):?>
			<tr target="fid" rel="<?php echo $item['fid']?>">		
				<td><?php echo Config_Game::$game[$item['gameid']] ?></td>		
				<td><?php echo $item['mid'] ?></td>
				<td><?php echo $item['content'] ?></td>
				<td><?php echo date("Y-m-d H:i:s",$item['rtime']) ?></td>
				<td>
				<a style="color:red" class="edit" href="?m=feedback&p=send&act=setView&fid=<?php echo $item['fid']?>&mid=<?php echo $item['mid']?>" target="dialog" title="主动发信息【<?php echo $item['mid'];?>】" rel="send_<?php echo $item['mid']?>"  >
				编辑
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
