<form id="pagerForm" method="post" action="?m=user&p=account&act=getNewRegister&navTabId=newregister">
	<input type="hidden" name="gameid" value="<?php echo $_REQUEST['gameid']?>" />
	<input type="hidden" name="ctype" value="<?php echo $_REQUEST['ctype']?>" />
	<input type="hidden" name="cid" value="<?php echo $_REQUEST['cid']?>" />
	<input type="hidden" name="date" value="<?php echo $_REQUEST['date']?>" />
	<input type="hidden" name="pageNum" value="<?php echo $currentPage?>" />
	<input type="hidden" name="numPerPage" value="<?php echo $numPerPage?>" />
</form>

<?php foreach ($aCid as $cid=>$cname):?>

<?php endforeach;?>

<div class="pageHeader">
	<form onsubmit="return navTabSearch(this);" action="?m=user&p=account&act=getNewRegister&navTabId=newregister" method="post">
	<div class="searchBar">
		<table class="searchContent">
			<tr>
				<td >游戏：</td>
				<td>					
					<select class="combox" name="gameid">
						<?php foreach ($aGame as $gameid=>$gamename):?>
							<option <?php if($gameid == $_REQUEST['gameid']):?> selected="selected" <?php endif;?> value="<?php echo $gameid?>"><?php echo $gamename;?></option>
						<?php endforeach;?>
					</select>
				</td>
				
				<td >客户端类型：</td>
				<td>					
					<select class="combox" name="ctype">
					<option value="">所有</option>
						<?php foreach ($aCtype as $ctype=>$cliname):?>
							<option <?php if($ctype == $_REQUEST['ctype']):?> selected="selected" <?php endif;?> value="<?php echo $ctype?>"><?php echo $cliname;?></option>
						<?php endforeach;?>
					</select>
				</td>
				
				<td >渠道：</td>
				<td>					
					<select class="combox" name="cid">
					<option value="">所有</option>
						<?php foreach ($aCid as $cid=>$cname):?>
							<option <?php if($cid == $_REQUEST['cid']):?> selected="selected" <?php endif;?> value="<?php echo $cid?>"><?php echo $cname;?></option>
						<?php endforeach;?>
					</select>
				</td>

				<td>时间：</td>			
				<td>					
					<input style="float:left" type="text" value="<?php echo $_REQUEST['date'] ?>" name=date class="date" dateFmt="yyyy-MM-dd" readonly="true"/>
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
				<th width="22"><input type="checkbox" group="ids[]" class="checkboxCtrl"></th>
				<th style="font-weight:bold" width="80">mid</th>
				<th style="font-weight:bold" width="80">游戏</th>
				<th style="font-weight:bold" width="80">客户端</th>
				<th style="font-weight:bold" width="80">渠道</th>
				<th style="font-weight:bold" width="80">版本号</th>
				<th style="font-weight:bold" width="80">账号名</th>
				<th style="font-weight:bold" width="80">昵称</th>
				<th style="font-weight:bold" width="80">注册时间</th>
				<th style="font-weight:bold" width="80">操作</th>
			</tr>
		</thead>
		<tbody>
		<?php foreach($items as $item):?>
			<tr target="id" rel="<?php echo $item['id']?>">	
				<td><input name="ids[]" value="<?php echo $item['id'] ?>" type="checkbox"></td>			
				<td><a title="用户信息" href="?m=user&p=account&navTabId=u-alist&mid=<?php echo $item['mid'] ?>" target="navTab" style="text-decoration: underline;"><?php echo $item['mid'] ?></a></td>
				<td><?php echo Config_Game::$game[$_REQUEST['gameid']] ?></td>
				<td><?php echo Config_Game::$clientTyle[$item['ctype']] ?></td>
				<td><?php echo $aCid[$item['cid']] ?></td>
				<td><?php echo $item['versions']?></td>
				<td><?php echo $item['registername']?></td>
				<td><?php echo $item['mnick'] ?></td>
				<td><?php echo date("Y-m-d H:i:s", $item['mtime'][$_REQUEST['gameid']]) ?></td>
				<td>
				    <?php $gameid = $_REQUEST['gameid']?>
					<a style="color:red" class="edit" href="?m=user&p=account&act=detail&mid=<?php echo $item['mid']?>&navTabId=u-detail&gameid=<?php echo $gameid?>" rel="u-detail" target="navTab" title="明细-<?php echo Config_Game::$game[$gameid]?>"><?php echo Config_Game::$game[$gameid]?></a>
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
