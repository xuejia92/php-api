<script>
function autoclickplay(){
	var gameid = "<?php echo $_REQUEST['gameid']?>";
	var stiem = $("#p_stime").val();
	var etiem = $("#p_etime").val();
	var mid   = "<?php echo $_REQUEST['mid']?>";
	$("#a_exchangelog").attr("href","?m=user&p=account&act=exchangelog&stime="+stiem+"&etime="+etiem+"&mid="+mid+"&gameid="+gameid) ;
	$("#a_exchangelog").click(); 

}

</script>

<form rel="exchangelog" id="pagerForm" method="post" action="?m=user&p=account&act=exchangelog">
	<input type="hidden" name="mid" value="<?php echo $_REQUEST['mid']?>" />
	<input type="hidden" name="gameid" value="<?php echo $_REQUEST['gameid']?>" />
	<input type="hidden" name="stime" value="<?php echo $_REQUEST['stime']?>" />
	<input type="hidden" name="etime" value="<?php echo $_REQUEST['etime']?>" />
	<input type="hidden" name="pageNum" value="<?php echo $currentPage?>" />
	<input type="hidden" name="numPerPage" value="<?php echo $numPerPage?>" />
</form>

<div class="pageHeader" >
	<!--  <form  rel="playlog" class="pageForm required-validate"  onsubmit="return iframeCallback(this, navTabAjaxDone)" action="?m=user&p=account&act=playlog" method="post">  --> 
	<div class="searchBar">
		<table class="searchContent">
			<tr>
				<td>开始时间：</td>			
				<td>					
					<input id="p_stime" style="float:left" type="text" value="<?php echo $_REQUEST['stime'] ? $_REQUEST['stime'] : date("Y-m-d",strtotime("-5 days")) ?>" name=stime class="date" dateFmt="yyyy-MM-dd" readonly="true"/>
					<a style="float:left" class="inputDateButton" href="javascript:;">选择</a>
				</td>
				<td>结束时间：</td>	
				<td>					
					<input id="p_etime" style="float:left" type="text" value="<?php echo $_REQUEST['etime'] ? $_REQUEST['etime'] : date("Y-m-d") ?>" name=etime class="date" dateFmt="yyyy-MM-dd" readonly="true"/>
					<a style="float:left" class="inputDateButton" href="javascript:;">选择</a>
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
	<table class="table" width="100%" layoutH="138">
		<thead>
			<tr>
				<th style="font-weight:bold;" width="80">兑换时间</th>
				
				<th style="font-weight:bold;" width="80">消耗乐券</th>
				<th style="font-weight:bold;" width="80">对换时乐券数</th>
				<th style="font-weight:bold;" width="80">兑换物品</th>
				<th style="font-weight:bold;" width="80">手机号码</th>
				<th style="font-weight:bold;" width="80">兑换状态</th>
			
			</tr>
		</thead>
		<tbody>
		<?php foreach($items as $item):?>
			<tr target="id" rel="<?php echo $item['id']?>">				
				<td><?php echo date("Y-m-d H:i:s",$item['extime']) ?></td>
			
				<td><?php echo $item['roll'] ?></td>
				<td><?php echo $item['rollnow'] ?></td>
				<td><?php echo $item['gid'] ?></td>
				<td><?php echo $item['phone'] ?></td>
				<td><?php echo $item['status'] ?></td>
			
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
		
		<div rel="exchangelog" class="pagination" targetType="navTab" totalCount="<?php echo $totalCount?>" numPerPage="<?php echo $numPerPage?>" pageNumShown="10" currentPage="<?php echo $currentPage?>"></div>
	</div>
</div>
