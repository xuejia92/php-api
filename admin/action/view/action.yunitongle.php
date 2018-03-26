<div class="pageHeader">
    <form onsubmit="return navTabSearch(this);" action="?m=action&p=list&act=detail&actionid=10&navTabId=action_10_<?php echo $gameid?>" method="post">
	<div class="searchBar">
		<table class="searchContent">
			<tr>
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
			</ul>
		</div>
	</div>
	</form>
</div>
<div class="pageContent">
	<table class="table" width="100%" layoutH="138">
		<thead>
			<tr>
			<?php foreach ($item as $ite):?>
			     <th style="font-weight:bold;text-align:center" width="90"><?php echo $ite ?></th>
			<?php endforeach;?>
			</tr>
		</thead>
		<tbody>
		    <?php if ($result):?>
    		    <?php foreach ($result as $key=>$val):?>
    		        <?php if ($_REQUEST['gameid']):?>
    		              <?php $gameid = $_REQUEST['gameid'];?>
    		              <?php if ($_REQUEST['ctype']):?>
    		                  <?php $ctype = $_REQUEST['ctype'];?>
    		                  <tr>
                			     <td style="font-weight:bold;text-align:center" width="90"><?php echo $key?></td>
                			     <td style="font-weight:bold;text-align:center" width="90"><?php echo $val[$gameid][$ctype]['userNum']?>/<?php echo $val[$gameid][$ctype]['userCi']?></td>
                			     <td style="font-weight:bold;text-align:center" width="90"><?php echo $val[$gameid][$ctype]['canyuNum']?>/<?php echo $val[$gameid][$ctype]['canyuCi']?></td>
                			     <td style="font-weight:bold;text-align:center" width="90"><?php echo $val[$gameid][$ctype]['fafang']?></td>
                			     <td style="font-weight:bold;text-align:center" width="90"><?php echo $val[$gameid][$ctype]['iPhoneNum']?>/<?php echo $val[$gameid][$ctype]['iPhoneCi']?></td>
                			     <td style="font-weight:bold;text-align:center" width="90"><?php echo $val[$gameid][$ctype]['iPadNum']?>/<?php echo $val[$gameid][$ctype]['iPadCi']?></td>
                			     <td style="font-weight:bold;text-align:center" width="90"><?php echo $val[$gameid][$ctype]['paiNum']?>/<?php echo $val[$gameid][$ctype]['paiCi']?></td>
                			     <td style="font-weight:bold;text-align:center" width="90"><?php echo $val[$gameid][$ctype]['moneyNum']?>/<?php echo $val[$gameid][$ctype]['moneyCi']?></td>
                			 </tr>
    		              <?php else:?>
            		          <tr>
                			     <td style="font-weight:bold;text-align:center" width="90"><?php echo $key?></td>
                			     <td style="font-weight:bold;text-align:center" width="90"><?php echo $val[$gameid]['userNum']?>/<?php echo $val[$gameid]['userCi']?></td>
                			     <td style="font-weight:bold;text-align:center" width="90"><?php echo $val[$gameid]['canyuNum']?>/<?php echo $val[$gameid]['canyuCi']?></td>
                			     <td style="font-weight:bold;text-align:center" width="90"><?php echo $val[$gameid]['fafang']?></td>
                			     <td style="font-weight:bold;text-align:center" width="90"><?php echo $val[$gameid]['iPhoneNum']?>/<?php echo $val[$gameid]['iPhoneCi']?></td>
                			     <td style="font-weight:bold;text-align:center" width="90"><?php echo $val[$gameid]['iPadNum']?>/<?php echo $val[$gameid]['iPadCi']?></td>
                			     <td style="font-weight:bold;text-align:center" width="90"><?php echo $val[$gameid]['paiNum']?>/<?php echo $val[$gameid]['paiCi']?></td>
                			     <td style="font-weight:bold;text-align:center" width="90"><?php echo $val[$gameid]['moneyNum']?>/<?php echo $val[$gameid]['moneyCi']?></td>
            			     </tr>
                	   <?php endif;?>
        		    <?php else:?>
            			<tr>
            			     <td style="font-weight:bold;text-align:center" width="90"><?php echo $key?></td>
            			     <td style="font-weight:bold;text-align:center" width="90"><?php echo $val['userNum']?>/<?php echo $val['userCi']?></td>
            			     <td style="font-weight:bold;text-align:center" width="90"><?php echo $val['canyuNum']?>/<?php echo $val['canyuCi']?></td>
            			     <td style="font-weight:bold;text-align:center" width="90"><?php echo $val['fafang']?></td>
            			     <td style="font-weight:bold;text-align:center" width="90"><?php echo $val['iPhoneNum']?>/<?php echo $val['iPhoneCi']?></td>
            			     <td style="font-weight:bold;text-align:center" width="90"><?php echo $val['iPadNum']?>/<?php echo $val['iPadCi']?></td>
            			     <td style="font-weight:bold;text-align:center" width="90"><?php echo $val['paiNum']?>/<?php echo $val['paiCi']?></td>
            			     <td style="font-weight:bold;text-align:center" width="90"><?php echo $val['moneyNum']?>/<?php echo $val['moneyCi']?></td>
            			</tr>
        			<?php endif;?>
    			<?php endforeach;?>
			<?php endif;?>
		</tbody>
	</table>
</div>