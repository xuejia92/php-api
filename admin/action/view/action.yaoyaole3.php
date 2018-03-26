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
        		      <tr>
            			     <td style="font-weight:bold;text-align:center" width="90"><?php echo $key?></td>
            			     <td style="font-weight:bold;text-align:center" width="90"><?php echo $val[$gameid]['dianNum']?>/<?php echo $val[$gameid]['dianCi']?></td>
            			     <td style="font-weight:bold;text-align:center" width="90"><?php echo $val[$gameid]['userNum']?>/<?php echo $val[$gameid]['userCi']?></td>
            			     <td style="font-weight:bold;text-align:center" width="90"><?php echo $val[$gameid]['fafang']?></td>
            			     <td style="font-weight:bold;text-align:center" width="90"><?php echo $val[$gameid]['huishou']?></td>
            			     <td style="font-weight:bold;text-align:center" width="90"><?php echo $val[$gameid]['bzNum']?>/<?php echo $val[$gameid]['bzCi']?></td>
            		  </tr>
        		    <?php else:?>
            			<tr>
            			     <td style="font-weight:bold;text-align:center" width="90"><?php echo $key?></td>
            			     <td style="font-weight:bold;text-align:center" width="90"><?php echo $val['dianNum']?>/<?php echo $val['dianCi']?></td>
            			     <td style="font-weight:bold;text-align:center" width="90"><?php echo $val['userNum']?>/<?php echo $val['userCi']?></td>
            			     <td style="font-weight:bold;text-align:center" width="90"><?php echo $val['fafang']?></td>
            			     <td style="font-weight:bold;text-align:center" width="90"><?php echo $val['huishou']?></td>
            			     <td style="font-weight:bold;text-align:center" width="90"><?php echo $val['bzNum']?>/<?php echo $val['bzCi']?></td>
            			</tr>
        			<?php endif;?>
    			<?php endforeach;?>
			<?php endif;?>
		</tbody>
	</table>
</div>