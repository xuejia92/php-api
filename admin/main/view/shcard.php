<div class="pageContent">	
	<div class="pageFormContent" layoutH="58">
			<div class="unit">
			<?php foreach($members as $mid=>$info):?>
				<img width=60 height=60 src=<?php echo Member::factory()->getIcon(0, $mid,'icon')?>>用户ID：<?php echo $mid;?>&nbsp;（<?php echo $mnicks[$mid]?>）
				<div style="float:right;margin-right:100px;">
				<?php foreach($info as $k=>$poker):?>
					<?php if($k == 0 && $_GET['gameid'] == 6):?>
					手牌：				
					<?php endif;?>
					
					<?php if($k == 2 && $_GET['gameid'] == 6):?>
					最佳牌组合：
					<?php endif;?>
					<img width=55 height=55 src="statics/poker/<?php echo $poker?>.png">
				<?php endforeach;?>
				</div>
				<br/>
				<br/>
			<?php endforeach;?>
			</div>
	</div>

</div>

