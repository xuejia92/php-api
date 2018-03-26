
<div class="pageHeader">
	<form onsubmit="return navTabSearch(this);" action="?m=data&p=version&navTabId=<?php echo $_REQUEST['navTabId'] ?>" method="post">
	<div class="searchBar">
		<table class="searchContent">		
			<tr>
				<td>时间：</td>			
				<td>
					<input type="hidden" name='gameid' value="<?php echo $gameid?>">					
					<input id="date" style="float:left" type="text" value="<?php echo $_REQUEST['date'] ? $_REQUEST['date'] : date("Y-m-d",strtotime("-1 days"))  ?>" name=date class="date" dateFmt="yyyy-MM-dd" readonly="true"/>
					<a style="float:left" class="inputDateButton" href="javascript:;">选择</a>
				</td>
				<td>客户端：</td>			
				<td>
					<select class="combox" name="ctype">
					<option value="">所有</option>
						<?php foreach ($aCtype as $ctype=>$clientName):?>
							<option <?php if($ctype == $_REQUEST['ctype']):?> selected="selected" <?php endif;?> value="<?php echo $ctype?>"><?php echo $clientName;?></option>
						<?php endforeach;?>
					</select>
				</td>	
				<td>渠道：</td>			
				<td>
					<select class="combox" name="cid">
						<option value="">所有</option>
						<?php foreach ($aCid as $cid=>$cname):?>
							<option <?php if($cid == $_REQUEST['cid']):?> selected="selected" <?php endif;?> value="<?php echo $cid?>"><?php echo $cname;?></option>
						<?php endforeach;?>
					</select>
				</td>			
			</tr>
		</table>

		<div class="subBar">
			<ul>
				<li><div class="buttonActive"><div class="buttonContent"><button>检索</button></div></div></li>
			</ul>
		</div>
	</div>
	</form>
</div>
<div class="pageContent">
	<table class="table" width="100%" layoutH="2000">
		<thead>
			<tr>
				<th style="font-weight:bold;" width="10" >版本号</th>
				<?php foreach($records as $item):?>
				<th style="font-weight:bold;" width="80" ><?php echo $item[0]?></th>
				<?php endforeach;?>
			</tr>
		</thead>
		<tbody>
		<tr >
		<td>人数</td>
		<?php foreach(  $records as $item ):?>
			<td><?php echo $item[1] ?></td>
		<?php endforeach;?>
		</tr>
		</tbody>
	</table>
	
	<div id="v_s_<?php echo $gameid?>"></div>
</div>

<script>

$(function () {
    $("#v_s_<?php echo $gameid?>").highcharts({
        chart: {
            plotBackgroundColor: null,
            plotBorderWidth: null,
            plotShadow: false
        },
        title: {
            text: '版本分布图'
        },
        tooltip: {
    	    pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
        },
        plotOptions: {
            pie: {
                allowPointSelect: true,
                cursor: 'pointer',
                dataLabels: {
                    enabled: true,
                    color: '#000000',
                    connectorColor: '#000000',
                    format: '<b>{point.name}</b>: {point.percentage:.1f} %'
                }
            }
        },
        series: [{
            type: 'pie',
            name: '版本分布',
            data: <?php echo json_encode($records);?>
        }]
    });
});				

</script>


