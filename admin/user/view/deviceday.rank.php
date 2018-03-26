
<div class="pageHeader">
	<form onsubmit="return navTabSearch(this);" action="?m=user&p=rank&act=getDeviceRankDay&navTabId=devicerankday" method="post">
	<div class="searchBar">
		<table class="searchContent">
			<tr>
				<td>类型：</td>
				<td>	
					<select class="combox" name="type">
						<option <?php if("deviceno" == $_REQUEST['type']):?> selected="selected" <?php endif;?> value="deviceno">机器码</option>
						<option <?php if("ip"  == $_REQUEST['type']):?> selected="selected" <?php endif;?> value="ip">IP</option>
					</select>
				</td>
				<td>时间：</td>			
				<td>					
					<input id="s_<?php echo $_REQUEST['navTabId'] ?>" style="float:left" type="text" value="<?php echo $_REQUEST['time'] ? $_REQUEST['time'] : date("Y-m-d") ?>" name=time class="date" dateFmt="yyyy-MM-dd" readonly="true"/>
					<a style="float:left" class="inputDateButton" href="javascript:;">选择</a>
				</td>				
			</tr>
		</table>

		<div class="subBar">
			<ul>
				<li><div class="buttonActive"><div class="buttonContent"><button type="submit">检索</button></div></div></li>
			</ul>
		</div>
	</div>
	</form>
</div>
<div class="pageContent">
	<div class="panelBar">
	</div>
	<table class="table" width="100%" layoutH="138">
		<thead>
			<tr>
				<th style="font-weight:bold" width="80">Top</th>
				<th style="font-weight:bold" width="80">mid</th>
				<th style="font-weight:bold<?php if($_REQUEST['type']=='deviceno'):?> color:red; <?php endif;?>" width="80">机器码</th>
				<th style="font-weight:bold;<?php if($_REQUEST['type']=='ip'):?> color:red; <?php endif;?>" width="80"> IP</th>
				<th style="font-weight:bold" width="80">数量</th>
			</tr>
		</thead>
		<tbody>
		<?php if($items):?>
		<?php foreach($items as $k=>$item):?>
			<tr target="id" rel="<?php echo $item['id']?>">	
				<td> <?php echo $k + 1 ?> </td>
				<td><a title="用户信息" href="?m=user&p=account&navTabId=u-alist&mid=<?php echo $item['mid'] ?>" target="navTab" style="text-decoration: underline;"><?php echo $item['mid'] ?></a></td>			
				<td>  <a class="button"  height="800" width="800" href="?m=user&p=account&act=getdevices&deviceno=<?php echo $item['deviceno'] ?>" target="dialog" rel="sdf" title="查看与此机器码相同的用户"><span><?php  echo  $item['deviceno']?></span></a>
					<?php if($item['dflag']):?>
					&nbsp;&nbsp;&nbsp;已被封&nbsp;&nbsp;&nbsp;
					<a  class="delete" href="?m=user&p=account&act=resetDeviceNo&deviceno=<?php echo $item['deviceno']?>&navTabId=devicerankday" target="ajaxTodo" title="你确定要解封？" >
					解封&nbsp;&nbsp;&nbsp;
					</a>
					<?php else:?>
					<a style='color:red;float:right' class="delete" href="?m=user&p=account&act=banDeviceNo&navTabId=devicerankday&deviceno=<?php echo $item['deviceno']?>&type=<?php echo $_REQUEST['type']?>" target="ajaxTodo" title="你确定要封这个机器码？" >
					封机器码
					</a>
					<?php endif;?>   
				</td>
				<td><a class="button"  height="800" width="800" href="?m=user&p=account&act=getips&ip=<?php echo $item['ip'] ?>" target="dialog" rel="sdf" title="查看与此IP相同的用户"><span><?php  echo  $item['ip']?></span></a>
					 <?php if($item['ipflag']):?>
					 	&nbsp;&nbsp;&nbsp;已被封&nbsp;&nbsp;&nbsp;
					 	<a  class="delete" href="?m=user&p=account&act=resetbanIp&navTabId=devicerankday&ip=<?php echo $item['ip']?>" target="ajaxTodo" title="你确定解封这个IP码？" >
						解封IP&nbsp;&nbsp;&nbsp; 
					 	</a>
					 	<a style='color:red;float:right' class="delete" href="?m=user&p=account&navTabId=devicerankday&act=banIP&ip=<?php echo $item['ip']?>" target="ajaxTodo" title="你确定要封这个IP码？" >
						再次封IP 
					 	</a>
					 <?php else:?>
					 <a style='color:red;float:right' class="delete" href="?m=user&p=account&navTabId=devicerankday&act=banIP&ip=<?php echo $item['ip']?>" target="ajaxTodo" title="你确定要封这个IP码？" >
						封IP 
					 </a>
					 <?php endif;?>  
					 
				</td>
				<td> <?php echo $item['total'] ?> </td>	
			</tr>
		<?php endforeach;?>	
		<?php endif;;?>
		</tbody>
	</table>
</div>
