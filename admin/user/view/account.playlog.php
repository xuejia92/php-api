<script>
function autoclickplay(){
	var gameid = "<?php echo $_REQUEST['gameid']?>";
	var stiem = $("#p_stime_"+gameid).val();
	var etiem = $("#p_etime_"+gameid).val();
	var p_level = $("#p_level_"+gameid).val();
	var p_table = $("#p_table_"+gameid).val();
	var mid   = "<?php echo $_REQUEST['mid']?>";
	var cmid  = $("#p_cmid").val();
	var title = "a_playlog";
	$("#"+title).attr("href","?m=user&p=account&act=playlog&stime="+stiem+"&etime="+etiem+"&mid="+mid+"&level="+p_level+"&gameid="+gameid+"&table="+p_table) ;
	$("#"+title).click(); 

}

</script>

<form rel="playlog" id="pagerForm" method="post" action="?m=user&p=account&act=playlog">
	<input type="hidden" name="mid" value="<?php echo $_REQUEST['mid']?>" />
	<input type="hidden" name="gameid" value="<?php echo $_REQUEST['gameid']?>" />
	<input type="hidden" name="table" value="<?php echo $_REQUEST['table']?>" />
	<input type="hidden" name="stime" value="<?php echo $_REQUEST['stime']?>" />
	<input type="hidden" name="etime" value="<?php echo $_REQUEST['etime']?>" />
	<input type="hidden" name="pageNum" value="<?php echo $currentPage?>" />
	<input type="hidden" name="numPerPage" value="<?php echo $numPerPage?>" />
	<input type="hidden" name="level" value="<?php echo $_REQUEST['level']?>" />
	<input type="hidden" name="cmid" value="<?php echo $_REQUEST['cmid']?>" />
	<input type="hidden" name="gid" value="<?php echo $_REQUEST['gid']?>" />
</form>

<div class="pageHeader" >
	<!--  <form  rel="playlog" class="pageForm required-validate"  onsubmit="return iframeCallback(this, navTabAjaxDone)" action="?m=user&p=account&act=playlog" method="post">  --> 
	<div class="searchBar">
		<table class="searchContent">
			<tr>
				<td>表：</td>	
				<td>					
					<select id="p_table_<?php echo $_REQUEST['gameid']?>" style="float:left" name="table">
					<?php foreach ($tables as $table):?>
						<option <?php if($_REQUEST['table'] == $table):?>  selected="selected" <?php endif;?>  value="<?php echo $table?>"><?php echo $table?></option>
					<?php endforeach;?>
					</select>
				</td>	
				<td>开始时间：</td>			
				<td>					
					<input id="p_stime_<?php echo $_REQUEST['gameid']?>" style="float:left" type="text" value="<?php echo $_REQUEST['stime'] ? $_REQUEST['stime'] : date("Y-m-d",strtotime("-5 days")).' 00:00:00' ?>" name=stime class="date" dateFmt="yyyy-MM-dd HH:mm:ss" readonly="true"/>
					<a style="float:left" class="inputDateButton" href="javascript:;">选择</a>
				</td>
				<td>结束时间：</td>	
				<td>					
					<input id="p_etime_<?php echo $_REQUEST['gameid']?>" style="float:left" type="text" value="<?php echo $_REQUEST['etime'] ? $_REQUEST['etime'] : date("Y-m-d H:i:s") ?>" name=etime class="date" dateFmt="yyyy-MM-dd HH:mm:ss" readonly="true"/>
					<a style="float:left" class="inputDateButton" href="javascript:;">选择</a>
				</td>
				<td>房间场次：</td>	
				<td>									
					<select id="p_level_<?php echo $_REQUEST['gameid']?>" style="float:left" name="level">
						<option value="">请选择</option>
						<?php foreach($roomConfig[$_REQUEST['gameid']] as $room=>$roomInfo):?>
						<option <?php if($_REQUEST['level'] == ($roomInfo['upper'] ? $roomInfo['level'].'-'.$roomInfo['upper'] : $roomInfo['level'])):?>  selected="selected" <?php endif;?>  value="<?php echo $roomInfo['upper'] ? $roomInfo['level'].'-'.$roomInfo['upper'] : $roomInfo['level']?>"><?php echo $roomInfo['roomName']?></option>
						<?php endforeach;?>
					</select>
				</td>
				<td>对手ID(mid)：</td>
				<td>
					<input id="p_cmid" type="text" name="cmid" value="<?php echo isset($_REQUEST['cmid']) ? $_REQUEST['cmid'] : ''?>"/>
				</td>		
			</tr>
		</table>

		<div class="subBar">
			<ul>
				<li><div class="buttonActive"><div class="buttonContent"><button onclick="return autoclickplay()" >检索</button></div></div></li>
			</ul>
		</div>
	</div>
	<!--  </form>	 -->
</div>

<div class="pageContent">
	<table class="table" width="100%" layoutH="168">
		<thead>
			<tr>
				<th style="font-weight:bold;" width="80">开始时间</th>
				<th style="font-weight:bold;" width="80">结束时间</th>
				<th style="font-weight:bold;" width="80">场次</th>
				<th style="font-weight:bold;" width="80">台费</th>
				<th style="font-weight:bold;" width="80">底注</th>
				<th style="font-weight:bold;" width="80">游戏开始金币</th>
				<th style="font-weight:bold;" width="80">输赢金币</th>
				<th style="font-weight:bold;" width="80">游戏结束金币</th>
				<th style="font-weight:bold;" width="80">游戏</th>
				<th style="font-weight:bold;" width="80">结果</th>
				<th style="font-weight:bold;" width="80">任务奖励</th>
				<th style="font-weight:bold;" width="80">查看明细</th>
			</tr>
		</thead>
		<tbody>
		<?php $roomRange = $roomConfig[$_REQUEST['gameid']];?>
		<?php foreach($items as $k=>$item):?>
			<tr target="id" rel="<?php echo $item['id']?>">				
				<td><?php echo date("Y-m-d H:i:s",$item['stime']) ?></td>
				<td><?php echo date("Y-m-d H:i:s",$item['etime']) ?></td>
				<td><?php echo $item['roomName'] ?></td>
				<td><?php echo $item['tax'] ?></td>
				<td><?php echo $item['ante'] ?></td>
				<td><?php echo $item['moneynow'] - $item['money']  ?></td>
				<td><?php echo $item['money'] ?></td>
				<td><?php echo $item['moneynow'] ?></td>
				<td><?php echo $aGame[$item['gid']] ?></td>
				<?php if($item['roomName'] == '翻翻乐'):?>
				<td><?php echo $item['endtype']?></td>
				<?php elseif($item['level'] >= 4 && $item['level'] <= 11):?>
				<td><?php echo $item['endtype']?></td>
				<?php else:?>
				<td>-</td>
				<?php endif;?>
				<td><?php echo $item['taskcoin'] ?></td>
				<td>
				<?php if($_REQUEST['gameid'] !=5):?>
				<a href="?m=user&p=account&act=boardDetail&navTabId=clist&boardid=<?php echo $item['boardid'];?>&gameid=<?php echo $_REQUEST['gameid']?>" target="dialog" target="dialog" width="1200" height="800">查看明细</a>
				<?php else:?>
				<?php echo $item['playerType'] ?>
				<?php endif;?>
				</td>		
			</tr>
		<?php endforeach;?>	
		</tbody>
	</table>
	<div  class="panelBar">
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
		
		<div rel="playlog" class="pagination" targetType="navTab" totalCount="<?php echo $totalCount?>" numPerPage="<?php echo $numPerPage?>" pageNumShown="10" currentPage="<?php echo $currentPage?>"></div>
	</div>
</div>
