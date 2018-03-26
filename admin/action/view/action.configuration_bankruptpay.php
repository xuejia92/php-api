<script type="text/javascript">
 $(".tabsPageContent").css('overflow','auto');
</script>
<div class="pageHeader">
	<h1>破产充值开关</h1>
</div>
<div class="pageContent">
    <form method="post" action="?m=action&p=actionconfig&act=set&tapid=<?php echo $tapid?>&navTabId=action_modify&close=1" class="pageForm required-validate"  onsubmit="return iframeCallback(this, dialogAjaxDone)">
		<div class="pageFormContent"  layoutH="80">
			<!-- <div class="unit">
				<div class="nowrap">
				    <label>渠道屏蔽：</label>
				    <?php $channel = $action['closecid'];
				          foreach ($channel as $id){
				              $channame[] = $cid[$id];
				          }
				          $name = trim(implode(',', $channame),',');
				          $cid  = trim(implode(',', $channel),',');
				    ?>
				    <input name="cid.cname" class="readonly" type="text" size="80" value="<?php echo $name?>" />
				</div>
				<div class="nowrap">
				    <input name="cid.cid" type="text" size="80" value=<?php echo $cid ? $cid : ""?> >
				    <a class="btnLook" href="?m=action&p=actionconfig&act=getChannel&tapid=<?php echo $tapid ? $tapid : ""?>" lookupGroup="cid">查找</a>
				</div>
			</div> -->
			
			<div class="unit">
			     <div class="nowrap">
				    <label>包屏蔽：</label>
				    <input class="readonly" type="text" size="160" value="<?php echo $action['closepid'] ? implode(",", $action['closepid']) : ''?>" />
                	<ul class="tree treeFolder treeCheck expand" style="width:40em">
                        <?php foreach (Config_Game::$game as $gameid=>$gamename):?>
                        <li><a><?php echo $gamename;?></a>
                            <ul>
                                <?php foreach (Config_Game::$clientTyle as $ctype=>$typename):?>
                                <li><a><?php echo $typename;?></a>
                                    <ul>
                                        <?php $channel = Setting_Cid::factory()->get(array('gameid'=>$gameid,'ctype'=>$ctype )) ?>
                            			<?php if($channel):?>
                            				<?php foreach($channel as $aChannel):?>
                            				    <li><a><?php echo $aChannel['cname'];?></a>
                            				        <ul>
                            				        <?php 
                                				        $items = array();
                                				        $items = Setting_Pid::factory()->get(array('cid'=>$aChannel['id'],'gameid'=>$gameid));
                                            		?>
                                            		<?php foreach ($items as $item):?>
                                            		  <li>
                                            		      <a tname="closepid[]" tvalue="<?php echo $item['id'] ?>" <?php if ($action['closepid']){echo in_array($item['id'], $action['closepid']) ? 'checked=true' : '' ;}?>>
                                            		          <?php echo '('.$item['id'].')  '.$item['pname'] ?>
                                        		          </a>
                                    		          </li>
                                            		<?php endforeach;?>
                            				        </ul>
                            				    </li>
                            				<?php endforeach;?>
                            			<?php endif;?>
                                    </ul>
                                </li>
                                <?php endforeach;?>
                            </ul>
                        </li>
                        <?php endforeach;?>
                    </ul>
                </div>
			</div>
		</div>
		
		<div class="formBar">
			<ul>
				<li><div class="buttonActive"><div class="buttonContent"><button type="submit">修改</button></div></div></li>
				<li><div class="button"><div class="buttonContent"><button type="button" class="close">取消</button></div></div></li>
			</ul>
		</div>
    </form>
</div>

<script type="text/javascript">
</script>