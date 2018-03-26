
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
        			             <td style="text-align:center" width="90"><?php echo $val[$gameid][$ctype]['shouru']?></td>
        			             <td style="text-align:center" width="90"><?php echo $val[$gameid][$ctype]['fafang']?></td>
        			             <td style="text-align:center" width="90"><?php echo $val[$gameid][$ctype]['dianjis']?>/<?php echo $val[$gameid][$ctype]['dianjil']?></td>
        			             <td style="text-align:center" width="90"><?php echo $val[$gameid][$ctype]['canyus']?>/<?php echo $val[$gameid][$ctype]['canyul']?></td>
        			             <td style="text-align:center" width="90"><?php echo $val[$gameid][$ctype]['chengg']?></td>
        			             <td style="text-align:center" width="90"><?php echo $val[$gameid][$ctype]['mount']?></td>
        			             <td style="text-align:center" width="90"><?php echo $val[$gameid][$ctype]['1.1']?></td>
        			             <td style="text-align:center" width="90"><?php echo $val[$gameid][$ctype]['1.2']?></td>
        			             <td style="text-align:center" width="90"><?php echo $val[$gameid][$ctype]['1.3']?></td>
        			             <td style="text-align:center" width="90"><?php echo $val[$gameid][$ctype]['1.4']?></td>
        			             <td style="text-align:center" width="90"><?php echo $val[$gameid][$ctype]['2']?></td>
        			             <td style="text-align:center" width="90"><?php echo $val[$gameid][$ctype]['3']?></td>
            			         <?php else:?>
            			         <td style="text-align:center" width="90"><?php echo $val[$gameid]['shouru']?></td>
            			         <td style="text-align:center" width="90"><?php echo $val[$gameid]['fafang']?></td>
        			             <td style="text-align:center" width="90"><?php echo $val[$gameid]['dianjis']?>/<?php echo $val[$gameid]['dianjil']?></td>
        			             <td style="text-align:center" width="90"><?php echo $val[$gameid]['canyus']?>/<?php echo $val[$gameid]['canyul']?></td>
        			             <td style="text-align:center" width="90"><?php echo $val[$gameid]['chengg']?></td>
        			             <td style="text-align:center" width="90"><?php echo $val[$gameid]['mount']?></td>
        			             <td style="text-align:center" width="90"><?php echo $val[$gameid]['1.1']?></td>
        			             <td style="text-align:center" width="90"><?php echo $val[$gameid]['1.2']?></td>
        			             <td style="text-align:center" width="90"><?php echo $val[$gameid]['1.3']?></td>
        			             <td style="text-align:center" width="90"><?php echo $val[$gameid]['1.4']?></td>
        			             <td style="text-align:center" width="90"><?php echo $val[$gameid]['2']?></td>
        			             <td style="text-align:center" width="90"><?php echo $val[$gameid]['3']?></td>
    			             <?php endif;?>
    			     <?php else:?>
			             <td style="text-align:center" width="90"><?php echo $val['shouru']?></td>
			             <td style="text-align:center" width="90"><?php echo $val['fafang']?></td>
			             <td style="text-align:center" width="90"><?php echo $val['dianjis']?>/<?php echo $val['dianjil']?></td>
			             <td style="text-align:center" width="90"><?php echo $val['canyus']?>/<?php echo $val['canyul']?></td>
			             <td style="text-align:center" width="90"><?php echo $val['chengg']?></td>
			             <td style="text-align:center" width="90"><?php echo $val['mount']?></td>
			             <td style="text-align:center" width="90"><?php echo $val['1.1']?></td>
			             <td style="text-align:center" width="90"><?php echo $val['1.2']?></td>
			             <td style="text-align:center" width="90"><?php echo $val['1.3']?></td>
			             <td style="text-align:center" width="90"><?php echo $val['1.4']?></td>
			             <td style="text-align:center" width="90"><?php echo $val['2']?></td>
			             <td style="text-align:center" width="90"><?php echo $val['3']?></td>
                     <?php endif;?>
    			</tr>
    			<?php endforeach;?>
			<?php endif;?>
		</tbody>
	</table>
</div>