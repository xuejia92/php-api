<div class="pageHeader">
	<div class="panel collapse" minH="25" defH="25">
		<h1>发送统计</h1>
		<div>
			<p><span style="margin:0 15px;float:left;font-weight:bold;height:20px;line-height:20px">剩余数量：</span><span style="height:20px;line-height:20px;float:left;color:red;font-weight:bold;font-size:16px" ><?php echo $balance?></span></p>
			<p><span style="margin:0 15px;float:left;font-weight:bold;height:20px;line-height:20px">发送总数：</span><span style="height:20px;line-height:20px;float:left;color:red;font-weight:bold;font-size:16px" ><?php echo $item['countAll']?></span></p>	
			<p><span style="margin:0 15px;float:left;font-weight:bold;height:20px;line-height:20px">成功数：</span><span style="height:20px;line-height:20px;float:left;color:red;font-weight:bold;font-size:16px" ><?php echo $item['countSucc']?></span></p>	
			<p><span style="margin:0 15px;float:left;font-weight:bold;height:20px;line-height:20px">异常数：</span><span style="height:20px;line-height:20px;float:left;color:red;font-weight:bold;font-size:16px" ><?php echo $item['countFail']?></span></p>	
			<p><span style="margin:0 15px;float:left;font-weight:bold;height:20px;line-height:20px">验证码业务：</span><span style="height:20px;line-height:20px;float:left;color:red;font-weight:bold;font-size:16px" ><?php echo $item['countIdcode']?></span></p>	
			<p><span style="margin:0 15px;float:left;font-weight:bold;height:20px;line-height:20px">找回账号密码业务：</span><span style="height:20px;line-height:20px;float:left;color:red;font-weight:bold;font-size:16px" ><?php echo $item['countAccount']?></span></p>	
			<p><span style="margin:0 15px;float:left;font-weight:bold;height:20px;line-height:20px">其它业务：</span><span style="height:20px;line-height:20px;float:left;color:red;font-weight:bold;font-size:16px" ><?php echo $item['countOther']?></span></p>				
		</div>
	</div>
	<form onsubmit="return navTabSearch(this);" action="?m=monitor&p=message&act=stat&navTabId=msg_stat" method="post">
	<div class="searchBar">
		<table class="searchContent">
			<tr>
				<td style="padding-right: 3px;">发送类型：</td>
				<td>					
					<select class="combox" name="type">
						<option <?php if($_REQUEST['type'] == 0):?>  selected="selected" <?php endif;?>  value="0">所有</option>
						<option <?php if($_REQUEST['type'] == 1):?>  selected="selected" <?php endif;?>  value="1">验证码</option>
						<option <?php if($_REQUEST['type'] == 2):?>  selected="selected" <?php endif;?>  value="2">账号密码</option>
					</select>
				</td>	
				<td style="padding-right: 3px;">状态：</td>
				<td>					
					<select class="combox" name="status">
						<option <?php if($_REQUEST['status'] == 0):?>   selected="selected" <?php endif;?>  value="0">所有</option>
						<option <?php if($_REQUEST['status'] == 1):?>   selected="selected" <?php endif;?>  value="1">成功</option>
						<option <?php if($_REQUEST['status'] == -1):?>  selected="selected" <?php endif;?>  value="-1">异常</option>
					</select>
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
				<li onclick="switchChart()"><div class="buttonActive"><div class="buttonContent"><button type="button"><a id="switch">关闭图形</a></button></div></div></li>
			</ul>
		</div>
	</div>
	</form>
</div>
<div class="pageContent" style="width:100%;overflow-x:hidden;" layoutH="150">
	<div id="stat_chart"></div>
	<table class="table" width="100%" >
		<thead>
			<tr>
				<th style="font-weight:bold;text-align:center" width="150">时间</th>
				<th style="font-weight:bold;text-align:center" >数量</th>
			</tr>
		</thead>
		<tbody>
		<?php $aDate  = $aCount = array();?>
		<?php foreach($items as $item):?>
		<?php 
			$aDate[]  = date("m-d",strtotime($item['days']));
			$aCount[] = (int)$item['count'];
	    ?>	
			<tr target="id" rel="<?php echo $item['id']?>">				   		
				<td style="text-align:center"><?php echo $item['days'] ?></td>
				<td style="text-align:center"><?php echo $item['count'] ?></td>
			</tr>
		<?php endforeach;?>	
		</tbody>
		<thead>
			<tr>
				<th style="font-weight:bold;text-align:center" width="150">时间</th>
				<th style="font-weight:bold;text-align:center" >数量</th>
			</tr>
		</thead>
	</table>
</div>

<script type="text/javascript">

var flag = 1;

$(function () {
    $('#stat_chart').highcharts({
        title: {
            text: '短信发送报告',
            x: -20 //center
        },
        subtitle: {
            text: "时间: <?php echo $_REQUEST['stime'] ? $_REQUEST['stime'] : date('Y-m-d', mktime(0,0,0,date('n'),1,date('Y'))) ?>——<?php echo $_REQUEST['etime'] ? $_REQUEST['etime'] : date('Y-m-d', mktime(0,0,0,date('n'),1,date('Y'))) ?>",
            x: -20
        },
        xAxis: {
            categories: <?php echo json_encode($aDate)?>
        },
        yAxis: {
            title: {
                text: '数量(条)'
            },
            plotLines: [{
                value: 0,
                width: 1,
                color: '#808080'
            }]
        },
        tooltip: {
            valueSuffix: '条'
        },
        legend: {
            layout: 'vertical',
            align: 'right',
            verticalAlign: 'middle',
            borderWidth: 0
        },
        series: [{
            name: '发送数',
            data: <?php echo json_encode($aCount)?>
        		}]
    });
});

function switchChart(){
	if(flag==1){
		$('#stat_chart').hide();
		$("#switch").text("打开图形");
		flag = 0;
	}else{
		$('#stat_chart').show();
		$("#switch").text("关闭图形");
		flag = 1;
	}
}

</script>
