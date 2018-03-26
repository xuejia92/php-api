<script>
function autoclick(param,catid){
	var stiem = $("#s_"+param).val();
	var etiem = $("#e_"+param).val();
	var roomid   = $("#roomid_"+param+"  option:selected").val();
	$("#"+param).attr("href","?m=data&p=show&act=overview&stime="+stiem+"&etime="+etiem+"&catid="+catid+"&navTabId="+param+"&roomid="+roomid) ;
	$("#"+param).click(); 
}
</script>

<div class="pageHeader">
	<!--  <form onsubmit="return navTabSearch(this);" action="?m=data&p=show&navTabId=<?php echo $_REQUEST['navTabId'] ?>" method="post">-->
	<div class="searchBar">
		<table class="searchContent">		
			<tr>
				<td>开始时间：</td>			
				<td>					
					<input id="s_<?php echo $_REQUEST['navTabId'] ? $_REQUEST['navTabId'] : $navTabId?>" style="float:left" type="text" value="<?php echo $_REQUEST['stime'] ? $_REQUEST['stime'] :  ($_REQUEST['catid'] == 2 ? date("Y-m-d H:i:s",$stime) : $stime)   ?>" name=stime class="date" dateFmt="<?php echo  $_REQUEST['catid']==2 ? 'yyyy-MM-dd HH:mm' : 'yyyy-MM-dd'?>" readonly="true"/>
					<a style="float:left" class="inputDateButton" href="javascript:;">选择</a>
				</td>
				<td>结束时间：</td>	
				<td>					
					<input id="e_<?php echo $_REQUEST['navTabId'] ? $_REQUEST['navTabId'] : $navTabId?>" style="float:left" type="text" value="<?php echo $_REQUEST['etime'] ?  $_REQUEST['etime'] : ($_REQUEST['catid'] == 2 ? date("Y-m-d H:i:s") : date("Y-m-d"))  ?>" name=etime class="date" dateFmt="<?php echo  $_REQUEST['catid']==2 ? 'yyyy-MM-dd HH:mm' : 'yyyy-MM-dd'?>" readonly="true"/>
					<a style="float:left" class="inputDateButton" href="javascript:;">选择</a>
				</td>			
			</tr>
		</table>

		<div class="subBar">
			<ul>
				<li><div class="buttonActive"><div class="buttonContent"><button onclick="return autoclick('<?php echo $_REQUEST['navTabId'] ? $_REQUEST['navTabId'] : $navTabId?>','<?php echo $_REQUEST['catid']?>')">检索</button></div></div></li>
			</ul>
		</div>
	</div>
	<!--  </form> -->
</div>
<div class="pageContent">
	<?php if($_REQUEST['catid']==2):?>
		<div id="stat_chart_<?php echo $_REQUEST['cid'] . '_' .(int)$_REQUEST['ctype']?>"></div>
	<?php else: ?>
	<table class="table" width="100%" layoutH="138">
		<thead>
			<tr>
			<th  style="font-weight:bold;text-align:center" width="90">时间</th>
			<?php foreach($aItem as $item):?>
				<th style="font-weight:bold;text-align:center;" width="80"><a title="点击查看趋势图" href="?m=data&p=show&act=chart&pid=<?php echo $_REQUEST['pid']?>&title=<?php echo $item['itemname']; ?>&stime=<?php echo $_REQUEST['stime'] ? $_REQUEST['stime'] : $stime?>&etime=<?php echo $_REQUEST['etime'] ? $_REQUEST['etime'] : date("Y-m-d")?>&cid=<?php echo $_REQUEST['cid']?>&pid=<?php echo $_REQUEST['pid']?>&itemid=<?php echo $item['itemid']?>" target="dialog" rel="<?php echo $item['itemid']?>" max="true" title="<?php echo $item['itemname']?>" width="800" height="480"><?php echo $item['itemname']?></a></th>
			<?php endforeach;?>
			</tr>
		</thead>
		<tbody>
		<?php foreach($aDate as $date):?>
			<tr>
				<td  style="text-align:center"><?php echo $date?></td>
				<?php foreach($aContent[$date] as $content):?>
					<td style="text-align:center"><?php echo $content?></td>
				<?php endforeach;?>
			</tr>
		<?php endforeach; ?>
		</tbody>
		
	</table>
	<?php endif;?>
	
	
</div>

<?php if($_REQUEST['catid']==2):?>
	<script type="text/javascript">
	$(function () {
    $("#stat_chart_<?php echo $_REQUEST['cid'] . '_' .(int)$_REQUEST['ctype']?>").highcharts({
        title: {
            text: "在线在玩趋势图",
            x: -20 //center
        },
        subtitle: {
            text: "",
            x: -20
        },
        xAxis: {
            categories: <?php echo json_encode($aTime)?>
        },
        yAxis: {
            title: {
                text: '数量(人)'
            },
            plotLines: [{
                value: 0,
                width: 1,
                color: '#808080'
            }]
        },
        tooltip: {
            valueSuffix: '个'
        },
        legend: {
            layout: 'vertical',
            align: 'right',
            verticalAlign: 'middle',
            borderWidth: 0
        },
        series: [{
            name:  "在线",
            data: <?php echo json_encode($aOnlinesum)?>
        	},
        	{name:  "在玩",
            data: <?php echo json_encode($aPlaysum)?>,	
        	}]
    });
});
</script>
<?php endif;?>

