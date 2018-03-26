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
    			     <td style="font-weight:bold;text-align:center" width="90"><?php echo $val['all'] ? $val['all'] : '0'?></td>
    			     <?php $types = $val['which']?>
    			         <td style="text-align:center" width="90"><?php echo $types[1] ? $types[1] : '0'?></td>
    			         <td style="text-align:center" width="90"><?php echo $types[3] ? $types[3] : '0'?></td>
    			         <td style="text-align:center" width="90"><?php echo $types[4] ? $types[4] : '0'?></td>
    			     <?php $frequency = $val['one']?>
			             <td style="text-align:center" width="90"><?php echo $frequency['once'] ? $frequency['once'] : '0'?></td>
			             <td style="text-align:center" width="90"><?php echo $frequency['twice'] ? $frequency['twice'] : '0'?></td>
			             <td style="text-align:center" width="90"><?php echo $frequency['thrice'] ? $frequency['thrice'] : '0'?></td>
    			</tr>
    			<?php endforeach;?>
			<?php endif;?>
		</tbody>
	</table>
</div>