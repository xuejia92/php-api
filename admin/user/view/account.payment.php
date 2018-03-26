<script>
function autoclick(){
	var gameid = "<?php echo $_REQUEST['gameid']?>";
	var stiem = $("#pay_stime").val();
	var etiem = $("#pay_etime").val();
	var mid   = "<?php echo $_REQUEST['mid']?>";
	$("#a_payment").attr("href","?m=user&p=account&act=payment&stime="+stiem+"&etime="+etiem+"&mid="+mid+"&gameid="+gameid) ;
	$("#a_payment").click(); 
}

</script>

<form rel="momeylog" id="pagerForm" method="post" action="?m=user&p=account&act=payment">
	<input type="hidden" name="mid" value="<?php echo $_REQUEST['mid']?>" />
	<input type="hidden" name="gameid" value="<?php echo $_REQUEST['gameid']?>" />
	<input type="hidden" name="stime" value="<?php echo $_REQUEST['stime']?>" />
	<input type="hidden" name="etime" value="<?php echo $_REQUEST['etime']?>" />
	<input type="hidden" name="pageNum" value="<?php echo $currentPage?>" />
	<input type="hidden" name="numPerPage" value="<?php echo $numPerPage?>" />
</form>

<div class="pageHeader" >
	<!--  <form  rel="payment" class="pageForm required-validate"  onsubmit="return iframeCallback(this, navTabAjaxDone)" action="?m=user&p=account&act=payment" method="post">  --> 
	<div class="searchBar">
		<table class="searchContent">
			<tr>
				<td>开始时间：</td>			
				<td>					
					<input id="pay_stime" style="float:left" type="text" value="<?php echo $_REQUEST['stime'] ? $_REQUEST['stime'] : date("Y-m-d",strtotime("-5 days")) ?>" name=stime class="date" dateFmt="yyyy-MM-dd" readonly="true"/>
					<a style="float:left" class="inputDateButton" href="javascript:;">选择</a>
				</td>
				<td>结束时间：</td>	
				<td>					
					<input id="pay_etime" style="float:left" type="text" value="<?php echo $_REQUEST['etime'] ? $_REQUEST['etime'] : date("Y-m-d") ?>" name=etime class="date" dateFmt="yyyy-MM-dd" readonly="true"/>
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
				<th style="font-weight:bold;" width="80">订单号</th>
				<th style="font-weight:bold;" width="80">支付方式</th>
				<th style="font-weight:bold;" width="80">付款金额</th>
				<th style="font-weight:bold;" width="80">商品类型</th>
				<th style="font-weight:bold;" width="80">获得数量</th>
				<th style="font-weight:bold;" width="80">当前金币数</th>
				<th style="font-weight:bold;" width="80">当前乐币数</th>
				<th style="font-weight:bold;" width="80">银行交易号</th>
				<th style="font-weight:bold;" width="80">状态</th>
			</tr>
		</thead>
		<tbody>
		<?php foreach($items as $item):?>
			<tr target="id" rel="<?php echo $item['id']?>">				
				<td><?php echo date("Y-m-d H:i:s",$item['ptime']) ?></td>
				<td><?php echo $item['pdealno']?></td>
				<td><?php echo $aPmode[$item['pmode']] ?></td>
				<td><?php echo $item['pamount'] ?></td>	
				<td><?php echo $aPtype[$item['ptype']] ?></td>		
				<td><?php echo $item['pexchangenum'] ?></td>		
				<td><?php echo $item['pmoneynow'] ?></td>		
				<td><?php echo $item['pcoinsnow'] ?></td>
				<td><?php echo $item['pbankno'] ?></td>		
				<td><?php echo $item['pstatus'] ?></td>							
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
		
		<div rel="payment" class="pagination" targetType="navTab" totalCount="<?php echo $totalCount?>" numPerPage="<?php echo $numPerPage?>" pageNumShown="10" currentPage="<?php echo $currentPage?>"></div>
	</div>
</div>
