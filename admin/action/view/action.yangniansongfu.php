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
            			     <td style="font-weight:bold;text-align:center" width="90"><?php echo $val[$gameid]['renshu']?>/<?php echo $val[$gameid]['renci']?></td>
            			     <td style="font-weight:bold;text-align:center" width="90"><?php echo $val[$gameid]['canyur']?>/<?php echo $val[$gameid]['canyuc']?></td>
            			     <td style="font-weight:bold;text-align:center" width="90"><?php echo $val[$gameid]['fafang']?></td>
            			     <td style="font-weight:bold;text-align:center" width="90"><?php echo $val[$gameid]['888']?></td>
            			     <td style="font-weight:bold;text-align:center" width="90"><?php echo $val[$gameid]['8888']?></td>
            			     <td style="font-weight:bold;text-align:center" width="90"><?php echo $val[$gameid]['100']?></td>
            			     <td style="font-weight:bold;text-align:center" width="90"><?php echo $val[$gameid]['6']?></td>
            		  </tr>
        		    <?php else:?>
            			<tr>
            			     <td style="font-weight:bold;text-align:center" width="90"><?php echo $key?></td>
            			     <td style="font-weight:bold;text-align:center" width="90"><?php echo $val['renshu']?>/<?php echo $val['renci']?></td>
            			     <td style="font-weight:bold;text-align:center" width="90"><?php echo $val['canyur']?>/<?php echo $val['canyuc']?></td>
            			     <td style="font-weight:bold;text-align:center" width="90"><?php echo $val['fafang']?></td>
            			     <td style="font-weight:bold;text-align:center" width="90"><?php echo $val['888']?></td>
            			     <td style="font-weight:bold;text-align:center" width="90"><?php echo $val['8888']?></td>
            			     <td style="font-weight:bold;text-align:center" width="90"><?php echo $val['100']?></td>
            			     <td style="font-weight:bold;text-align:center" width="90"><?php echo $val['6']?></td>
            			</tr>
        			<?php endif;?>
    			<?php endforeach;?>
			<?php endif;?>
		</tbody>
	</table>
</div>