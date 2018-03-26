<div class="pageHeader">
    <form onsubmit="return navTabSearch(this);" action="?m=data&p=mobile&act=detail&gameid=<?php echo $gameid?>&navTabId=mobile_1_<?php echo $gameid?>" method="post">
	<div class="searchBar">
		<table class="searchContent">
			<tr>
                <td >去重：</td>
				<td>					
					<select class="combox" name="filter">
					   <option <?php if($filter == 0):?> selected="selected" <?php endif;?> value="0">去重</option>
					   <option <?php if($filter == 1):?> selected="selected" <?php endif;?> value="1">不去重</option>
					</select>
				</td>
				
				<td>开始时间：</td>			
				<td>					
					<input style="float:left" type="text" value="<?php echo $_REQUEST['stime'] ? $_REQUEST['stime'] : date("Y-m-d",strtotime("-14 days")) ?>" name=stime class="date" dateFmt="yyyy-MM-dd" readonly="true"/>
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
    <div id="mobile_<?php echo $item?>_chart"></div>
    <table class="table" width="100%">
        <thead>
            <tr>
                <th style="font-weight:bold;text-align:center" width="150">时间</th>
                <th style="font-weight:bold;text-align:center">总下载量</th>
                <th style="font-weight:bold;text-align:center">Android下载量</th>
                <th style="font-weight:bold;text-align:center">IOS下载量</th>
            </tr>
        </thead>
            
        <tbody>
            <?php foreach ($record as $row=>$rows):?>
            <tr>
            <?php 
                  $aDate[]      = $row;
                  $aDet[]       = $rows[$item][0];
                  $aAnDown[]    = $rows[$item][1];
                  $aIDown[]     = $rows[$item][2];
            ?>
                <td style="text-align:center" width="150"><?php echo $row?></td>
                <td style="text-align:center"><?php echo $rows[$item][0]?></td>
                <td style="text-align:center"><?php echo $rows[$item][1]?></td>
                <td style="text-align:center"><?php echo $rows[$item][2]?></td>
            </tr>
            <?php endforeach;?>
        </tbody>
    </table>
</div>

<script type="text/javascript">

var flag = 1;

$(function () {
    $('#mobile_<?php echo $item?>_chart').highcharts({
        title: {
            text: '网页流量',
            x: -20 //center
        },
        subtitle: {
            text: "时间: <?php echo $_REQUEST['stime'] ? $_REQUEST['stime'] : date('Y-m-d', strtotime("-14 day")) ?>——<?php echo $_REQUEST['etime'] ? $_REQUEST['etime'] : date('Y-m-d') ?>",
            x: -20
        },
        xAxis: {
            categories: <?php echo json_encode($aDate)?>
        },
        yAxis: {
            title: {
                text: '次数(次)'
            },
            plotLines: [{
                value: 0,
                width: 1,
                color: '#808080'
            }]
        },
        tooltip: {
            valueSuffix: '次'
        },
        legend: {
            layout: 'vertical',
            align: 'right',
            verticalAlign: 'middle',
            borderWidth: 0
        },
        series: [{
                    name: '详情流量',
                    data: <?php echo json_encode($aDet)?>
    		},{
                	name: 'Android下载量',
                	data: <?php echo json_encode($aAnDown)?>
    	    },{
                	name: 'IOS下载量',
                	data: <?php echo json_encode($aIDown)?>
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