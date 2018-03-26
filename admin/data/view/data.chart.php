
<div class="pageContent"  layoutH="150">
	<div id="stat_chart"></div>
</div>

<script type="text/javascript">

$(function () {
    $('#stat_chart').highcharts({
        title: {
            text: "<?php echo $_REQUEST['title']?>",
            x: -20 //center
        },
        subtitle: {
            text: "包：<?php echo  $_REQUEST['pid'] ? $pname : '所有' ?>    |  时间: <?php echo $_REQUEST['stime'] ? $_REQUEST['stime'] : date('Y-m-d', mktime(0,0,0,date('n'),1,date('Y'))) ?>——<?php echo $_REQUEST['etime'] ? $_REQUEST['etime'] : date('Y-m-d', mktime(0,0,0,date('n'),1,date('Y'))) ?>",
            x: -20
        },
        xAxis: {
            categories: <?php echo json_encode($aDate)?>
        },
        yAxis: {
            title: {
                text: '数量(个)'
            },
            plotLines: [{
                value: 0,
                width: 1,
                color: '#808080'
            }]
        },
        tooltip: {
            valueSuffix: ''
        },
        legend: {
            layout: 'vertical',
            align: 'right',
            verticalAlign: 'middle',
            borderWidth: 0
        },
        series: [{
            name:  "<?php echo $_REQUEST['title']?>",
            data: <?php echo json_encode($aContent)?>
        		}]
    });
});

</script>
