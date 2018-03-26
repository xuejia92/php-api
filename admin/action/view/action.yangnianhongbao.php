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
                			     <td style="font-weight:bold;text-align:center" width="90"><?php echo $val[$gameid][$ctype]['renshu']?>/<?php echo $val[$gameid][$ctype]['renci']?></td>
                			     <td style="font-weight:bold;text-align:center" width="90"><?php echo $val[$gameid][$ctype]['canyur']?>/<?php echo $val[$gameid][$ctype]['canyuc']?></td>
                			     <td style="font-weight:bold;text-align:center" width="90"><?php echo $val[$gameid][$ctype]['fafang']?></td>
                			     <td style="font-weight:bold;text-align:center" width="90">三等奖：<?php echo $val[$gameid][$ctype]['hongbaoR']['1']['186']?>/<?php echo $val[$gameid][$ctype]['hongbaoC']['1']['186']?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;四等奖：<?php echo $val[$gameid][$ctype]['hongbaoR']['1']['187']?>/<?php echo $val[$gameid][$ctype]['hongbaoC']['1']['187']?></td>
                			     <td style="font-weight:bold;text-align:center" width="90">三等奖：<?php echo $val[$gameid][$ctype]['hongbaoR']['2']['186']?>/<?php echo $val[$gameid][$ctype]['hongbaoC']['2']['186']?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;四等奖：<?php echo $val[$gameid][$ctype]['hongbaoR']['2']['187']?>/<?php echo $val[$gameid][$ctype]['hongbaoC']['2']['187']?></td>
                			     <td style="font-weight:bold;text-align:center" width="90">三等奖：<?php echo $val[$gameid][$ctype]['hongbaoR']['3']['186']?>/<?php echo $val[$gameid][$ctype]['hongbaoC']['3']['186']?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;四等奖：<?php echo $val[$gameid][$ctype]['hongbaoR']['3']['187']?>/<?php echo $val[$gameid][$ctype]['hongbaoC']['3']['187']?></td>
                			 </tr>
    		              <?php else:?>
            		          <tr>
                			     <td style="font-weight:bold;text-align:center" width="90"><?php echo $key?></td>
                			     <td style="font-weight:bold;text-align:center" width="90"><?php echo $val[$gameid]['renshu']?>/<?php echo $val[$gameid]['renci']?></td>
                			     <td style="font-weight:bold;text-align:center" width="90"><?php echo $val[$gameid]['canyur']?>/<?php echo $val[$gameid]['canyuc']?></td>
                			     <td style="font-weight:bold;text-align:center" width="90"><?php echo $val[$gameid]['fafang']?></td>
                			     <td style="font-weight:bold;text-align:center" width="90">三等奖：<?php echo $val[$gameid]['hongbaoR']['1']['186']?>/<?php echo $val[$gameid]['hongbaoC']['1']['186']?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;四等奖：<?php echo $val[$gameid]['hongbaoR']['1']['187']?>/<?php echo $val[$gameid]['hongbaoC']['1']['187']?></td>
                			     <td style="font-weight:bold;text-align:center" width="90">三等奖：<?php echo $val[$gameid]['hongbaoR']['2']['186']?>/<?php echo $val[$gameid]['hongbaoC']['2']['186']?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;四等奖：<?php echo $val[$gameid]['hongbaoR']['2']['187']?>/<?php echo $val[$gameid]['hongbaoC']['2']['187']?></td>
                			     <td style="font-weight:bold;text-align:center" width="90">三等奖：<?php echo $val[$gameid]['hongbaoR']['3']['186']?>/<?php echo $val[$gameid]['hongbaoC']['3']['186']?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;四等奖：<?php echo $val[$gameid]['hongbaoR']['3']['187']?>/<?php echo $val[$gameid]['hongbaoC']['3']['187']?></td>
                			 </tr>
                	   <?php endif;?>
        		    <?php else:?>
            			<tr>
            			     <td style="font-weight:bold;text-align:center" width="90"><?php echo $key?></td>
            			     <td style="font-weight:bold;text-align:center" width="90"><?php echo $val['renshu']?>/<?php echo $val['renci']?></td>
            			     <td style="font-weight:bold;text-align:center" width="90"><?php echo $val['canyur']?>/<?php echo $val['canyuc']?></td>
            			     <td style="font-weight:bold;text-align:center" width="90"><?php echo $val['fafang']?></td>
            			     <td style="font-weight:bold;text-align:center" width="90">三等奖：<?php echo $val['hongbaoR']['1']['186']?>/<?php echo $val['hongbaoC']['1']['186']?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;四等奖：<?php echo $val['hongbaoR']['1']['187']?>/<?php echo $val['hongbaoC']['1']['187']?></td>
            			     <td style="font-weight:bold;text-align:center" width="90">三等奖：<?php echo $val['hongbaoR']['2']['186']?>/<?php echo $val['hongbaoC']['2']['186']?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;四等奖：<?php echo $val['hongbaoR']['2']['187']?>/<?php echo $val['hongbaoC']['2']['187']?></td>
            			     <td style="font-weight:bold;text-align:center" width="90">三等奖：<?php echo $val['hongbaoR']['3']['186']?>/<?php echo $val['hongbaoC']['3']['186']?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;四等奖：<?php echo $val['hongbaoR']['3']['187']?>/<?php echo $val['hongbaoC']['3']['187']?></td>
            			</tr>
        			<?php endif;?>
    			<?php endforeach;?>
			<?php endif;?>
		</tbody>
	</table>
</div>