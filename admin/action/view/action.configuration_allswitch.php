<script type="text/javascript">
 $(".tabsPageContent").css('overflow','auto');
</script>
<div class="pageHeader">
	<h1>敏感功能控制</h1>
</div>
<div class="pageContent">
    <form method="post" action="?m=action&p=actionconfig&act=set&tapid=<?php echo $tapid?>&navTabId=action_modify&close=1" class="pageForm required-validate"  onsubmit="return iframeCallback(this, dialogAjaxDone)">
		<div class="pageFormContent"  layoutH="80">
			<div class="unit">
			     <div class="nowrap">
				    <label>包屏蔽：</label>
				    <input class="readonly" type="text" size="160" value="<?php echo $action['closepid'] ? implode(",", $action['closepid']) : ''?>" />
                	<ul class="tree treeFolder treeCheck expand" style="width:35em">
                        <?php foreach (Config_Game::$game as $gameid=>$gamename):?>
                        <li>
                            <a><?php echo $gamename;?></a>
                            <ul>
                            <?php $channel = Setting_Cid::factory()->get(array('gameid'=>$gameid,'ctype'=>2 )) ?>
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
                </div>
			</div>
		</div>
		
		<div class="formBar">
			<ul>
				<li><div class="buttonActive"><div class="buttonContent"><button type="submit"><?php echo $tapid ? "修改" : "增加"?></button></div></div></li>
				<li><div class="button"><div class="buttonContent"><button type="button" class="close">取消</button></div></div></li>
			</ul>
		</div>
    </form>
</div>

<script type="text/javascript">
</script>