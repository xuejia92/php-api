<style type="text/css">
.msg_input {
    background: none repeat scroll 0 0 #1E3301;
	background-color:transparent;
    border: medium none;
    color: #FFFFFF;
    height: 18px;
    padding: 0 2px;
    width: 110px;
}
</style>

<div id="msgbox" style="margin:auto;position:absolute;z-index:1;left:790px;top:572px;background-image: url('abc.gif');display:none;">
	<input id="input_toflash" class="msg_input" onkeydown="if(event.keyCode == 13){sendMsg();}" onFocus="if(this.value == '在此输入聊天信息'){this.value=''}" type="text" value="" maxlength='90' name="input">
</div>
<script type="text/javascript">
	/**
	 * 返回聊天框
	 */
	
	function showChatDiv(param){
		var isshow = param.isshow;
		var type = param.type;
		debug( 'showChatDiv', param );
		if(isshow == 1){
			if(type == "ansy"){
				$('#input_toflash').val('')
				$("#msgbox").css("top","518px");
			}else{
				$('#input_toflash').val('在此输入聊天信息')
				$("#msgbox").css("top","572px");
			}
			$('#msgbox').show();
		}else{
			$('#msgbox').hide();
		}
	}
	
	/**
	 * 发送聊天内容
	 */
	
	function sendMsg(){
		var Msg = $('#input_toflash').val();
		if(Msg == ''){
			return -1;
		}
		getFlashMovieObject('flashBox').onChatInput(Msg);
		$('#input_toflash').val('');
	}
	
	
</script>