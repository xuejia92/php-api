<script>
function autoclick(param,cid,catid,overview){
	var gameid = "<?php echo $_REQUEST['gameid'] ? $_REQUEST['gameid'] : ''?>";
	var ctype = "<?php echo $_REQUEST['ctype'] ? $_REQUEST['ctype'] : ''?>";
	var stiem = $("#s_"+param).val();
	var etiem = $("#e_"+param).val();
	var pid   = $("#pid_"+param+"  option:selected").val();
	pid   = pid == undefined ? '' : pid; 
	var roomid   = $("#roomid_"+param+"  option:selected").val();
	$("#"+param).attr("href","?m=data&p=show&act=detail&stime="+stiem+"&etime="+etiem+"&cid="+cid+"&pid="+pid+"&catid="+catid+"&navTabId="+param+"&roomid="+roomid+"&ctype="+ctype+"&overview="+overview+"&gameid="+gameid) ;
	$("#"+param).click(); 
}
</script>

<div class="pageHeader">
	<!--  <form onsubmit="return navTabSearch(this);" action="?m=data&p=show&navTabId=<?php echo $_REQUEST['navTabId'] ?>" method="post">-->
	<div class="searchBar">
		<table class="searchContent">		
			<tr>
			<?php if($_REQUEST['catid']==3 || $_REQUEST['catid']==2):?>
				<td style="padding-right: 3px;">房间场次：</td>
				<td>
					<select id="roomid_<?php echo $_REQUEST['navTabId'] ?>" class="combox" name="roomid">
						<option value="">所有</option>
						<?php foreach(Data_Config::$roomconfig[$_REQUEST['gameid']] as $roomid=>$roomname):?>
							<option <?php if($_REQUEST['roomid'] == $roomid):?> selected="selected" <?php endif;?> value="<?php echo $roomid?>"><?php echo $roomname;?></option>
						<?php endforeach;?>
					</select>
				</td>
			<?php endif;?>
			<?php if($_REQUEST['catid']==15):?>
				<td style="padding-right: 3px;">比赛场次：</td>
				<td>
					<select id="roomid_<?php echo $_REQUEST['navTabId'] ?>" class="combox" name="roomid">
						<option value="">所有</option>
						<?php foreach(Data_Config::$matchroomconfig as $roomid=>$roomname):?>
							<option <?php if($_REQUEST['roomid'] == $roomid):?> selected="selected" <?php endif;?> value="<?php echo $roomid?>"><?php echo $roomname;?></option>
						<?php endforeach;?>
					</select>
				</td>
			<?php endif;?>	
				
			<?php if($_REQUEST['cid']):?>
				<td style="padding-right: 3px;">客户端包：</td>
				<td>
					<select id="pid_<?php echo $_REQUEST['navTabId'] ?>" class="combox" name="pid">
						<option value="0">所有</option>
						<?php foreach ($aPid as $p):?>
							<option <?php if($_REQUEST['pid'] == $p['id']):?> selected="selected" <?php endif;?> value="<?php echo $p['id']?>"><?php echo $p['pname'];?></option>
						<?php endforeach;?>
					</select>
				</td>
			<?php endif;?>
				
				<td>开始时间：</td>			
				<td>					
					<input id="s_<?php echo $_REQUEST['navTabId'] ?>" style="float:left" type="text" value="<?php echo $_REQUEST['stime'] ? $_REQUEST['stime'] :  ($_REQUEST['catid'] == 2 ? date("Y-m-d H:i:s",$stime) : $stime)   ?>" name=stime class="date" dateFmt="<?php echo  $_REQUEST['catid']==2 ? 'yyyy-MM-dd HH:mm' : 'yyyy-MM-dd'?>" readonly="true"/>
					<a style="float:left" class="inputDateButton" href="javascript:;">选择</a>
				</td>
				<td>结束时间：</td>	
				<td>					
					<input id="e_<?php echo $_REQUEST['navTabId'] ?>" style="float:left" type="text" value="<?php echo $_REQUEST['etime'] ?  $_REQUEST['etime'] : ($_REQUEST['catid'] == 2 ? date("Y-m-d H:i:s") : date("Y-m-d"))  ?>" name=etime class="date" dateFmt="<?php echo  $_REQUEST['catid']==2 ? 'yyyy-MM-dd HH:mm' : 'yyyy-MM-dd'?>" readonly="true"/>
					<a style="float:left" class="inputDateButton" href="javascript:;">选择</a>
				</td>			
			</tr>
		</table>

		<div class="subBar">
			<ul>
				<li><div class="buttonActive"><div class="buttonContent"><button onclick="return autoclick('<?php echo $_REQUEST['navTabId'] ?>','<?php echo $_REQUEST['cid']?>','<?php echo $_REQUEST['catid']?>','<?php echo $_REQUEST['overview']?>')">检索</button></div></div></li>
			</ul>
		</div>
	</div>
	<!--  </form> -->
</div>
<div class="pageContent">
	<?php if($_REQUEST['catid']==2):?>
		<div id="stat_chart_<?php echo $_REQUEST['gameid'].'_'. $_REQUEST['cid'] . '_' .(int)$_REQUEST['ctype']?>"></div>
	<?php else: ?>
	<table class="table" width="100%" layoutH="138">
		<thead>
			<tr>
			<th  style="font-weight:bold;text-align:center" width="90">时间</th>
			<?php foreach($aItem as $item):?>
				<th style="font-weight:bold;text-align:center;" width="80"><a title="点击查看趋势图" href="?m=data&p=show&act=chart&gameid=<?php echo $_REQUEST['gameid']?>&ctype=<?php echo $_REQUEST['ctype']?>&overview=<?php echo $_REQUEST['overview']?>&pid=<?php echo $_REQUEST['pid']?>&title=<?php echo $item['itemname']; ?>&stime=<?php echo $_REQUEST['stime'] ? $_REQUEST['stime'] : $stime?>&etime=<?php echo $_REQUEST['etime'] ? $_REQUEST['etime'] : date("Y-m-d")?>&cid=<?php echo $_REQUEST['cid']?>&pid=<?php echo $_REQUEST['pid']?>&itemid=<?php echo $item['itemid']?>" target="dialog" rel="<?php echo $item['itemid']?>" max="true" title="<?php echo $item['itemname']?>" width="800" height="480"><?php echo $item['itemname']?></a></th>
			<?php endforeach;?>
			</tr>
		</thead>
		<tbody>
		<?php foreach($aDate as $date):?>
			<tr>
				<td  style="text-align:center"><?php echo $date?></td>
				<?php foreach($aContent[$date] as $content):?>
					<td style="text-align:center"><?php echo $content ? $content : '0' ?></td>
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
    $("#stat_chart_<?php echo $_REQUEST['gameid'].'_'. $_REQUEST['cid'] . '_' .(int)$_REQUEST['ctype']?>").highcharts({
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

