
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
    			<tr>
    			     <td style="font-weight:bold;text-align:center" width="90"><?php echo $key?></td>
    			     <?php if ($_REQUEST['gameid']):?>
        			     <?php $gameid = $_REQUEST['gameid']?>
        			         <?php if ($_REQUEST['ctype']):?>
        			             <?php $ctype = $_REQUEST['ctype']?>
        			             <td style="text-align:center" width="90"><?php echo $join[$key][$gameid][$ctype] ? $join[$key][$gameid][$ctype] : '0'?></td>
        			             <td style="text-align:center" width="90"><?php echo $val[$gameid][$ctype]['hat'] ? $val[$gameid][$ctype]['hat'] : '0'?></td>
                                 <td style="text-align:center" width="90"><?php echo $val[$gameid][$ctype]['clothes'] ? $val[$gameid][$ctype]['clothes'] : '0'?></td>
                                 <td style="text-align:center" width="90"><?php echo $val[$gameid][$ctype]['boots'] ? $val[$gameid][$ctype]['boots'] : '0'?></td>
                                 <td style="text-align:center" width="90"><?php echo $val[$gameid][$ctype]['socks'] ? $val[$gameid][$ctype]['socks'] : '0'?></td>
            			     <?php else:?>
            			         <td style="text-align:center" width="90"><?php echo $join[$key][$gameid] ? $join[$key][$gameid] : '0'?></td>
                			     <td style="text-align:center" width="90"><?php echo $val[$gameid]['hat'] ? $val[$gameid]['hat'] : '0'?></td>
                                 <td style="text-align:center" width="90"><?php echo $val[$gameid]['clothes'] ? $val[$gameid]['clothes'] : '0'?></td>
                                 <td style="text-align:center" width="90"><?php echo $val[$gameid]['boots'] ? $val[$gameid]['boots'] : '0'?></td>
                                 <td style="text-align:center" width="90"><?php echo $val[$gameid]['socks'] ? $val[$gameid]['socks'] : '0'?></td>
        			         <?php endif;?>
    			     <?php else:?>
    			         <td style="text-align:center" width="90"><?php echo $join[$key] ? $join[$key] : '0'?></td>
			             <?php foreach ($val as $re):?>
    			             <td style="text-align:center" width="90"><?php echo $re ? $re : '0'?></td>
                             <!-- <td style="text-align:center" width="90"><?php echo $val['hat'] ? $val['hat'] : '0'?></td>
                             <td style="text-align:center" width="90"><?php echo $val['clothes'] ? $val['clothes'] : '0'?></td>
                             <td style="text-align:center" width="90"><?php echo $val['boots'] ? $val['boots'] : '0'?></td>
                             <td style="text-align:center" width="90"><?php echo $val['socks'] ? $val['socks'] : '0'?></td> -->
                         <?php endforeach;?>
                     <?php endif;?>
    			</tr>
    			<?php endforeach;?>
			<?php endif;?>
		</tbody>
	</table>
</div>