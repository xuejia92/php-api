
<div class="pageContent">
<form method="post"  action="?m=action&p=actionlist&act=sort&navTabId=action_actionlist" class="pageForm required-validate" onsubmit="return validateCallback(this, navTabAjaxDone)">
    <div class="panelBar">
    	<ul class="toolBar">
    		<li><div class="buttonActive"><div class="buttonContent"><button type="submit">重新排序</button></div></div></li>
    		<li><a class="add" href="?m=action&p=actionlist&act=setList&navTabId=action_set" target="navTab"><span>新增</span></a></li>
    	</ul>
    </div>

    <table class="table" width="100%" layoutH="138">
        <thead>
            <tr>
                 <th style="text-align:center; font-weight:bold; width:10px">序号</th>
                 <th style="text-align:center; font-weight:bold; width:100px">活动名称</th>
                 <th style="text-align:center; font-weight:bold; width:160px">活动名称</th>
                 <th style="text-align:center; font-weight:bold; width:150px">显示时间</th>
                 <th style="text-align:center; font-weight:bold;">描述</th>
                 <th style="text-align:center; font-weight:bold; width:70px">开关状态</th>
                 <th style="text-align:center; font-weight:bold; width:140px">上线时间</th>
                 <th style="text-align:center; font-weight:bold; width:140px">下线时间</th>
                 <th style="text-align:center; font-weight:bold; width:200px">开启渠道</th>
                 <th style="text-align:center; font-weight:bold; width:100px">客户端屏蔽</th>
                 <th style="text-align:center; font-weight:bold; width:160px">游戏屏蔽</th>
                 <th style="text-align:center; font-weight:bold; width:70px">新活动标识</th>
                 <th style="text-align:center; font-weight:bold; width:80px">操作</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result):?>
            <?php $i = 0;?>
		    <?php foreach ($result as $key=>$value):?>
		          <tr>
		              <td><input type="text" size="5" value="<?php echo $i; $i++?>" name="pos[]"></td>
		              <td><input class="readonly" type="text" value="<?php echo $key?>" name="ids[]"></td>
		              <td style="text-align:center"><?php echo $value['subject']?></td>
		              <td style="text-align:center"><?php echo $value['time']?></td>
		              <td style=""><?php echo $value['desc']?></td>
		              <td style="text-align:center"><?php echo ($value['open']) ? '开启' : '关闭'?></td>
		              <td style="text-align:center"><?php echo $value['start_time']?></td>
		              <td style="text-align:center"><?php echo $value['end_time']?></td>
		              <td style="text-align:center"><?php $closeCid = explode(",", $value['openCid']); foreach ($closeCid as $val){ if ($val){ echo $channel[$val].','; }} ?></td>
		              <td style="text-align:center"><?php $closeCtype = explode(",", $value['closeCtype']); $ctype = Config_Game::$clientTyle; foreach ($closeCtype as $ctypeid){ if ($ctypeid) { echo $ctype[$ctypeid].','; }} ?></td>
		              <td style="text-align:center"><?php $closeGameid = explode(",", $value['closeGameid']); $game = Config_Game::$game; foreach ($closeGameid as $gameid){ if ($gameid) { echo $game[$gameid].','; }} ?></td>
		              <td style="text-align:center"><?php echo($value['new']=='1')? '显示':'不显示'?></td>
		              <td style="text-align:center">
		                  <a class="delete" style="color:red;" href="?m=action&p=actionlist&act=del&id=<?php echo $key?>&navTabId=action_actionlist" target="ajaxTodo" title="你确定要删除吗？" warn="请选择其中一个">删除</a>
		                  |
		                  <a class="delete" href="?m=action&p=actionlist&act=setList&id=<?php echo $key?>&tapid=1&navTabId=action_set" target="navTab">修改</a>
		              </td>
		          </tr>
		    <?php endforeach;?>
		    <?php endif;?>
		</tbody>
	</table>
</form>
</div>
