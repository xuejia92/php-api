
<form id="pagerForm" action=""></form>
<div class="pageHeader">
	<div class="searchBar">
		<table class="searchContent">
			<tr>
				<td style="padding-right: 3px;">客户端类型：</td>
				<td>	
					<select id="ctype" name="ctype" class="sel">
						<option value="0">所有</option>
						<?php foreach (Config_Game::$clientTyle as $ctype=>$typename):?>
							<option <?php if($ctype == $_REQUEST['ctype']):?> selected="selected" <?php endif;?> value="<?php echo $ctype?>"><?php echo $typename;?></option>
						<?php endforeach;?>
					</select>				
				</td>
			    <td style="padding-right: 3px;">渠道类型：</td>
				<td>
					<select id="vertype" name="vertype" class="sel">
						<option value="0">所有</option>
						<?php foreach (Config_Game::$channelVertype as $vertype=>$vertypename):?>
							<option <?php if($vertype == $_REQUEST['vertype']):?> selected="selected" <?php endif;?> value="<?php echo $vertype?>"><?php echo $vertypename;?></option>
						<?php endforeach;?>
					</select>
				</td>
			</tr>
		</table>
	</div>
</div>
<div class="pageContent">
	<table class="table" layoutH="118" targetType="dialog" width="100%">
		<thead>
			<tr>
				<th width="30"><input id='s_all' type="checkbox" class="checkboxCtrl" group="cid" /></th>
				<th orderfield="cid">编号</th>
				<th orderfield="cname">渠道名</th>
			</tr>
		</thead>
		<tbody id="tb">
		<?php foreach ($aCid as $k=>$v):?>
			<tr>
				<td><input onclick="checkBox()" <?php if($aChannel && in_array($v['cid'], $aChannel)):?>checked<?php endif;?> type="checkbox" name="cid" value="{cid:'<?php echo $v['cid']?>', cname:'<?php echo $v['cname']?>'}"/></td>
				<td><?php echo $v['cid']?></td>
				<td><?php echo $v['cname']?></td>
			</tr>
		<?php endforeach;?>
		</tbody>
	</table>
	<div class="formBar">
    	<form rel="pagerForm" method="post" action="" onsubmit="return dwzSearch(this, 'dialog');">
			<ul>
				<li><div class="button"><div class="buttonContent"><button type="button" multLookup="cid" warn="请选择渠道商">选择</button></div></div></li>
			</ul>
    	</form>
	</div>
</div>

<script type="text/javascript">

function checkBox(){
	if($("[name=cid]").length == $("[name=cid]:checked").length){
		$("#s_all").attr("checked",true);
	}else{
		$("#s_all").attr("checked",false);
	}
}

checkBox();

$(document).ready(function(){
    var arr = <?php echo isset($aChannel) ? json_encode($aChannel) : json_encode(array('0'))?>;
	
    Array.prototype.contains = function (obj) {  
        var i = this.length;
        while (i--) {  
            if (this[i] === obj) {  
                return true;  
            }  
        }  
        return false;  
    }
	
    $(".sel").change(function(){
    	var ctype      = $("#ctype").val();
    	var vertype    = $("#vertype").val();

    	$.ajax({
    		url: '?m=setting&p=channel&a=sort&id=<?php echo $id?>&source=notice',
    		type: 'GET',
    		data:{ctype:ctype,vertype:vertype},
    		dataType: 'json',
    		success: function(result){
        		$("#tb").html('');

    			var obj = eval(result);
    			$(obj).each(function(index) {
    				var val = obj[index];
    				var tmpString = "<tr><td width='27'><input onclick='checkBox()'";
    				var sta =arr.contains(val.cid);
    				if (sta){
    					tmpString += " checked"
    				}
    				tmpString += " type='checkbox' name='cid' value='{cid:&#39;"+val.cid+"&#39;, cname:&#39;"+val.cname+"&#39;}'/></td>";
    				tmpString += "<td width='115'>"+val.cid+"</td><td>"+val.cname+"</td></tr>"
    				
    				$("#tb").append(tmpString);
    			})
    		}
    	});
    });

    
});
</script>