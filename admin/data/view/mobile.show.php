<div class="pageHeader">
    <form onsubmit="return navTabSearch(this);" action="?m=data&p=mobile&act=&navTabId=mobile_1" method="post">
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
			    <td style="padding-right: 3px;">客户端类型：</td>
				<td>					
					<select class="combox" name="ctype">
					<option value="">所有</option>
						<?php foreach ($aCtype as $ctype=>$clientName):?>
							<option <?php if($ctype == $_REQUEST['ctype']):?> selected="selected" <?php endif;?> value="<?php echo $ctype?>"><?php echo $clientName;?></option>
						<?php endforeach;?>
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
    <div id="mobile_chart"></div>
    <table class="table" width="100%">
        <thead>
            <tr>
                <th style="font-weight:bold;text-align:center" width="150">时间</th>
                <th style="font-weight:bold;text-align:center">主页点击量</th>
                <th style="font-weight:bold;text-align:center">五张详情页点击量</th>
                <th style="font-weight:bold;text-align:center">斗地主详情页点击量</th>
                <th style="font-weight:bold;text-align:center">斗牛详情页点击量</th>
                <th style="font-weight:bold;text-align:center">德州详情页点击量</th>
            </tr>
        </thead>
            
        <tbody>
            <?php $ct = $_REQUEST['ctype'] ? $_REQUEST['ctype'] : 0;?>
            <?php foreach ($record as $row=>$rows):?>
                <tr>
                <?php 
                      $aDate[]      = $row;
                      $aMain[]      = $rows[99][$ct];
                      $aWzDown[]    = $rows[100][$ct];
                      $aLlDown[]    = $rows[101][$ct];
                      $aBfDown[]    = $rows[102][$ct];
                      $aTxDown[]    = $rows[103][$ct];
                ?>
                    <td style="text-align:center" width="150"><?php echo $row?></td>
                    <td style="text-align:center"><?php echo $rows[99][$ct]?></td>
                    <td style="text-align:center"><?php echo $rows[100][$ct]?></td>
                    <td style="text-align:center"><?php echo $rows[101][$ct]?></td>
                    <td style="text-align:center"><?php echo $rows[102][$ct]?></td>
                    <td style="text-align:center"><?php echo $rows[103][$ct]?></td>
                </tr>
            <?php endforeach;?>
        </tbody>
    </table>
</div>

<script type="text/javascript">

var flag = 1;

$(function () {
    $('#mobile_chart').highcharts({
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
                    name: '首页流量',
                    data: <?php echo json_encode($aMain)?>
    		},{
                	name: '五张下载点击量',
                	data: <?php echo json_encode($aWzDown)?>
    	    },{
                	name: '斗地主下载点击量',
                	data: <?php echo json_encode($aLlDown)?>
    	    },{
                	name: '斗牛下载点击量',
                	data: <?php echo json_encode($aBfDown)?>
    	    },{
                	name: '德州下载点击量',
                	data: <?php echo json_encode($aTxDown)?>
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