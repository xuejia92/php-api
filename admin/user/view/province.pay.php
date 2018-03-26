
<div class="pageHeader">
	<form onsubmit="return navTabSearch(this);" action="?m=user&p=provincepay&navTabId=<?php echo $_REQUEST['navTabId'] ?>" method="post">
	<div class="searchBar">
		<table class="searchContent">		
			<tr>
				<td>时间：</td>			
				<td>					
					<input style="float:left" class="date" name="date" type="text" value="<?php echo $_REQUEST['date'] ? $_REQUEST['date'] : date("Y-m-d",strtotime("-1 days"))?>" dateFmt="yyyy-MM-dd" readonly="true"/>
					<a style="float:left"  class="inputDateButton" href="javascript:;">选择</a>
				</td>
				
				<td >支付渠道：</td>
				<td>
					<select class="combox" name="pmode">
						<option value="">所有</option>
						<?php foreach ($aPmode as $pmode=>$pchannel):?>
							<option <?php if($pmode == $_REQUEST['pmode']):?> selected="selected" <?php endif;?> value="<?php echo $pmode?>"><?php echo $pchannel;?></option>
						<?php endforeach;?>
					</select>
				</td>
				
				<td >游戏：</td>
				<td>					
					<select class="combox" name="gameid">
					<option value="">所有</option>
						<?php foreach ($aGame as $gameid=>$gamename):?>
							<option <?php if($gameid == $_REQUEST['gameid']):?> selected="selected" <?php endif;?> value="<?php echo $gameid?>"><?php echo $gamename;?></option>
						<?php endforeach;?>
					</select>
				</td>		
			</tr>
		</table>

		<div class="subBar">
			<ul>
				<li><div class="buttonActive"><div class="buttonContent"><button onclick="return autoclick('<?php echo $_REQUEST['navTabId'] ? $_REQUEST['navTabId'] : $navTabId?>','<?php echo $_REQUEST['catid']?>')">检索</button></div></div></li>
			</ul>
		</div>
	</div>
	</form> 
</div>

<div class="pageContent">
	<div layoutH="108" style='height:1200px;' id="stat"></div>
</div>

<script type="text/javascript">


$(function () {                                                                
    $('#stat').highcharts({                                           
        chart: {                                                           
            type: 'bar',
            reflow: true,
            height:1000                  
        },                                                               
        title: {                                                           
            text: '支付省份分布图'                    
        },                                                                 
        subtitle: {                                                        
            text: '点乐游戏'                                  
        },                                                                 
        xAxis: {                                                           
            categories: <?php echo json_encode($items['province'])?>,
            title: {                                                       
                text: null                                                 
            }                                                              
        },                                                                 
        yAxis: {                                                           
            min: 0,                                                        
            title: {                                                       
                text: '单位 (元)',                             
                align: 'high'                                              
            },                                                             
            labels: {                                                      
                overflow: 'justify'                                        
            }                                                              
        },                                                                 
        tooltip: {                                                         
            valueSuffix: '元'                                       
        },                                                                 
        plotOptions: {                                                     
            bar: {                                                         
                dataLabels: {                                              
                    enabled: true                                          
                }                                                          
            }                                                              
        },                                                                 
        legend: {                                                          
            layout: 'vertical',                                            
            align: 'right',                                                
            verticalAlign: 'top',                                          
            x: -40,                                                        
            y: 100,                                                        
            floating: true,                                                
            borderWidth: 1,                                                
            backgroundColor: '#FFFFFF',                                    
            shadow: true                                                   
        },                                                                 
        credits: {                                                         
            enabled: false                                                 
        },                                                                 
        series: <?php echo json_encode($items['series']) ?>                                                              
    });                                                                    
});     

</script>

<script type="text/javascript">
$(window).resize(function() {
	var width_frm = $(document.body).width();
	var height_frm = $(document.body).height();
		var width_div = width_frm/2;
	var height_div = height_frm/2;
	
	$('#stat').css("height", height_div);
	$('#stat').css("width", width_div);
});

</script>
