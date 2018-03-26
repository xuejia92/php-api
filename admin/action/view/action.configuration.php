
<div class="pageContent">
    <table class="table" width="100%" layoutH="138">
        <thead>
            <tr>
                 <th style="text-align:center; font-weight:bold; width:10px">序号</th>
                 <th style="text-align:center; font-weight:bold; width:100px">活动名称</th>
                 <th style="text-align:center; font-weight:bold; width:80px">操作</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result):?>
		    <?php foreach ($result as $key=>$value):?>
		          <tr>
		              <td style="text-align:center;"><?php echo $key?></td>
		              <td style="text-align:center;"><?php echo $value['subject']?></td>
		              <td style="text-align:center;">
		                  <a class="delete" style="color:red;" href="?m=action&p=actionconfig&act=modify&id=<?php echo $value['name']?>&tapid=<?php echo $key?>&navTabId=action_modify" target="navTab">修改</a>
		              </td>
		          </tr>
		    <?php endforeach;?>
		    <?php endif;?>
		</tbody>
	</table>
</div>
