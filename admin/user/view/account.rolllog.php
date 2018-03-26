<script>
function autoclick(){
	var gameid = "<?php echo $_REQUEST['gameid']?>";
	var stiem = $("#stime").val();
	var etiem = $("#etime").val();
	var mid   = "<?php echo $_REQUEST['mid']?>";
	var r_table = $("#r_table").val();
	var gid   = $("#m_gameid").val();
	$("#a_rolllog").attr("href","?m=user&p=account&act=rolllog&stime="+stiem+"&etime="+etiem+"&mid="+mid+"&gameid="+gameid+"&table="+r_table+"&gid="+gid) ;
	$("#a_rolllog").click(); 
}

</script>

<form rel="momeylog" id="pagerForm" method="post" action="?m=user&p=account&act=rolllog">
	<input type="hidden" name="mid" value="<?php echo $_REQUEST['mid']?>" />
	<input type="hidden" name="gameid" value="<?php echo $_REQUEST['gameid']?>" />
	<input type="hidden" name="stime" value="<?php echo $_REQUEST['stime']?>" />
	<input type="hidden" name="etime" value="<?php echo $_REQUEST['etime']?>" />
	<input type="hidden" name="pageNum" value="<?php echo $currentPage?>" />
	<input type="hidden" name="numPerPage" value="<?php echo $numPerPage?>" />
	<input type="hidden" name="table" value="<?php echo $_REQUEST['table']?>" />
	<input type="hidden" name="gid" value="<?php echo $_REQUEST['gid']?>" />
</form>

<div class="pageHeader" >
	<!--  <form  rel="rolllog" class="pageForm required-validate"  onsubmit="return iframeCallback(this, navTabAjaxDone)" action="?m=user&p=account&act=rolllog" method="post">  --> 
	<div class="searchBar">
		<table class="searchContent">
			<tr>
				<td >游戏：</td>
				<td>					
					<select id="m_gameid" class="combox" name="gid">
					<option value="0">所有</option>
						<?php foreach ($aGame as $gameid=>$gamename):?>
							<option <?php if($gameid == $_REQUEST['gid']):?> selected="selected" <?php endif;?> value="<?php echo $gameid?>"><?php echo $gamename;?></option>
						<?php endforeach;?>
					</select>
				</td>
			<!--  
				<td>表：</td>	
				<td>					
					<select id="r_table" style="float:left" name="table">
					<?php foreach ($tables as $table):?>
						<option <?php if($_REQUEST['table'] == $table):?>  selected="selected" <?php endif;?>  value="<?php echo $table?>"><?php echo $table?></option>
					<?php endforeach;?>
					</select>
				</td>
			-->	
				<td>开始时间：</td>			
				<td>					
					<input id="stime" style="float:left" type="text" value="<?php echo $_REQUEST['stime'] ? $_REQUEST['stime'] : date("Y-m-d",strtotime("-5 days")) ?>" name=stime class="date" dateFmt="yyyy-MM-dd" readonly="true"/>
					<a style="float:left" class="inputDateButton" href="javascript:;">选择</a>
				</td>
				<td>结束时间：</td>	
				<td>					
					<input id="etime" style="float:left" type="text" value="<?php echo $_REQUEST['etime'] ? $_REQUEST['etime'] : date("Y-m-d") ?>" name=etime class="date" dateFmt="yyyy-MM-dd" readonly="true"/>
					<a style="float:left" class="inputDateButton" href="javascript:;">选择</a>
				</td>				
			</tr>
		</table>

		<div class="subBar">
			<ul>
				<li><div class="buttonActive"><div class="buttonContent"><button onclick="return autoclick()" >检索</button></div></div></li>
			</ul>
		</div>
	</div>
	<!--  </form>	 -->
</div>

<div class="pageContent">
	<table class="table" width="100%" layoutH="138">
		<thead>
			<tr>
				<th style="font-weight:bold;" width="80">时间</th>
				<th style="font-weight:bold;" width="80">标志</th>
				<th style="font-weight:bold;" width="80">乐券数</th>
				<th style="font-weight:bold;" width="80">来源描述</th>
				<th style="font-weight:bold;" width="80">游戏</th>
			</tr>
		</thead>
		<tbody>
		<?php foreach($items as $item):?>
			<tr target="id" rel="<?php echo $item['id']?>">				
				<td><?php echo date("Y-m-d H:i:s",$item['ctime']) ?></td>
				<td><?php echo $item['amount'] > 0 ? "收获" : "付出" ?></td>
				<td><?php echo $item['amount']; ?></td>
				<td><?php echo $item['desc']; ?></td>	
				<td><?php echo Config_Game::$game[$item['gameid']] ?></td>			
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
		
		<div rel="rolllog" class="pagination" targetType="navTab" totalCount="<?php echo $totalCount?>" numPerPage="<?php echo $numPerPage?>" pageNumShown="10" currentPage="<?php echo $currentPage?>"></div>
	</div>
</div>
