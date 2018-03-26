<?php if ($_REQUEST['actionid']==1 && $_REQUEST['catid'] != 3):?>
<div class="pageHeader">
    <form method="post" action="?m=action&p=list&act=modify" onsubmit="return validateCallback(this, navTabAjaxDone)">
        <td style="padding-right: 20px;">开奖结果设置：</td>
        <td style="text-align: center;padding-right: 40px;">
            <select name="type">
                <option value="dog">狗</option>
                <option value="bird">鸟</option>
                <option value="fox">狐狸</option>
                <option value="giraffe">长颈鹿</option>
                <option value="panda">熊猫</option>
                <option value="sheep">羊</option>
            </select>
        </td>
        <td style="padding-right: 20px;">当前开奖结果：</td>
        <td style="text-align: center;"><?php echo $his ? $his : '暂未开奖或设置' ?></td>
        <div>
            <td>
                <div class="buttonActive buttonActiveHover">
                    <div class="buttonContent">
                        <button type="submit">修改</button>
                    </div>
                </div>
            </td>
        </div>
    </form>
</div>
<?php endif;?>
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
		        <?php if ($_REQUEST['actionid'] == 1):?>
		            <?php if ($_REQUEST['catid'] == 3):?>
		                <?php foreach ($result as $key=>$val):?>
		                    <tr>
                                 <td style="font-weight:bold;text-align:center" width="30"><?php echo $key?></td>
                                 <td style="text-align:center" width="30"><?php echo $val['num']?></td>
                                      <?php foreach ($val['who'] as $d):?>
                                          <td style="text-align:center" width="30"><?php echo $d['id'].'('.$d['number'].')' ? $d['id'].'('.$d['number'].')' : '0'?></td>
                                      <?php endforeach;?>
                                 </td>
		                    </tr>
		                <?php endforeach;?>
		            <?php else :?>
            		    <?php foreach ($result as $key=>$val):?>
                			<tr>
                			     <td style="font-weight:bold;text-align:center" width="90"><?php echo $key?></td>
                			     <?php if ($_REQUEST['gameid']):?>
                    			     <?php $gameid = $_REQUEST['gameid']?>
                    			         <?php if ($_REQUEST['ctype']):?>
                    			             <?php $ctype = $_REQUEST['ctype']?>
                    			             <td style="text-align:center" width="90"><?php echo $val[$gameid][$ctype]['dog'] ? $val[$gameid][$ctype]['dog'] : '0'?></td>
                                             <td style="text-align:center" width="90"><?php echo $val[$gameid][$ctype]['bird'] ? $val[$gameid][$ctype]['bird'] : '0'?></td>
                                             <td style="text-align:center" width="90"><?php echo $val[$gameid][$ctype]['fox'] ? $val[$gameid][$ctype]['fox'] : '0'?></td>
                                             <td style="text-align:center" width="90"><?php echo $val[$gameid][$ctype]['giraffe'] ? $val[$gameid][$ctype]['giraffe'] : '0'?></td>
                                             <td style="text-align:center" width="90"><?php echo $val[$gameid][$ctype]['panda'] ? $val[$gameid][$ctype]['panda'] : '0'?></td>
                                             <td style="text-align:center" width="90"><?php echo $val[$gameid][$ctype]['sheep'] ? $val[$gameid][$ctype]['sheep'] : '0'?></td>
                        			     <?php else:?>
                            			     <td style="text-align:center" width="90"><?php echo $val[$gameid]['dog'] ? $val[$gameid]['dog'] : '0'?></td>
                                             <td style="text-align:center" width="90"><?php echo $val[$gameid]['bird'] ? $val[$gameid]['bird'] : '0'?></td>
                                             <td style="text-align:center" width="90"><?php echo $val[$gameid]['fox'] ? $val[$gameid]['fox'] : '0'?></td>
                                             <td style="text-align:center" width="90"><?php echo $val[$gameid]['giraffe'] ? $val[$gameid]['giraffe'] : '0'?></td>
                                             <td style="text-align:center" width="90"><?php echo $val[$gameid]['panda'] ? $val[$gameid]['panda'] : '0'?></td>
                                             <td style="text-align:center" width="90"><?php echo $val[$gameid]['sheep'] ? $val[$gameid]['sheep'] : '0'?></td>
                    			         <?php endif;?>
                			     <?php else:?>
                                     <td style="text-align:center" width="90"><?php echo $val['dog'] ? $val['dog'] : '0'?></td>
                                     <td style="text-align:center" width="90"><?php echo $val['bird'] ? $val['bird'] : '0'?></td>
                                     <td style="text-align:center" width="90"><?php echo $val['fox'] ? $val['fox'] : '0'?></td>
                                     <td style="text-align:center" width="90"><?php echo $val['giraffe'] ? $val['giraffe'] : '0'?></td>
                                     <td style="text-align:center" width="90"><?php echo $val['panda'] ? $val['panda'] : '0'?></td>
                                     <td style="text-align:center" width="90"><?php echo $val['sheep'] ? $val['sheep'] : '0'?></td>
                                 <?php endif;?>
                			</tr>
            			<?php endforeach;?>
        			<?php endif;?>
    			<?php endif;?>
        		<?php if ($_REQUEST['actionid'] == 2):?>
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
                             <td style="text-align:center" width="90"><?php echo $val['hat'] ? $val['hat'] : '0'?></td>
                             <td style="text-align:center" width="90"><?php echo $val['clothes'] ? $val['clothes'] : '0'?></td>
                             <td style="text-align:center" width="90"><?php echo $val['boots'] ? $val['boots'] : '0'?></td>
                             <td style="text-align:center" width="90"><?php echo $val['socks'] ? $val['socks'] : '0'?></td>
                         <?php endif;?>
        			</tr>
        			<?php endforeach;?>
        		<?php endif;?>
			<?php endif;?>
		</tbody>
	</table>
</div>