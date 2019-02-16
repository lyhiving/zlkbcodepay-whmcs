<?php
if (!defined("WHMCS")) {
	die("This file cannot be accessed directly");
}

class zlkbcodepay_config{   
	function get_configuration (){
		global $CONFIG;
		$extra_config = [
			"app_id" => ["FriendlyName" => "应用ID", "Type" => "text", "Size" => "30"],
			"app_secret" => ["FriendlyName" => "应用secret", "Type" => "text",  "Size" => "60"],
			"overtime" => ["FriendlyName" => "超时时间(推荐填写300)", "Type" => "text",  "Size" => "10"],
			"type" => [
				'FriendlyName' => '',
				'Type' => 'dropdown',
				'Options' => [
					'zlkbcodepay' => "</option></select><div class='alert alert-info' role='alert' id='zlkbcodepay_notice' style='margin-bottom: 0px;'>您可能需要：<a type='button' class='btn btn-primary' href='https://codepay.zlkb.net/member/login/' target='_blank'><span class='glyphicon glyphicon-new-window'></span>收款宝平台</a><br/><span style='color:red'>特别注意：</span><br/>本接口为收费接口</div><script>$('#zlkbcodepay_notice').prev().hide();</script><select style='display:none'>"
				]
			]			
		];
				
		$base_config = ["FriendlyName" => ['Type' => 'System','Value' => '收款宝']];
		
		$config = array_merge($base_config,$extra_config);
		$config["author"] = [
			'FriendlyName' => '',
			'Type' => 'dropdown',
			'Options' => [
				'zlkbcodepay' => "</option></select><div class='alert alert-success' role='alert' id='zlkbcodepay_author' style='margin-bottom: 0px;'>该插件由 <a href='https://github.com/zlkbdotnet/zlkbcodepay-whmcs' target='_blank'><span class='glyphicon glyphicon-new-window'></span>资料空白</a> 开发<br/><span class='glyphicon glyphicon-ok'></span> 支持 WHMCS 5/6/7 , 当前WHMCS 版本 ".$CONFIG["Version"]."<br/><span class='glyphicon glyphicon-ok'></span> 仅支持 PHP 5.4 以上的环境 , 当前PHP版本 ".phpversion()."</div><script>$('#zlkbcodepay_author').prev().hide();</script><style>* {font-family: Microsoft YaHei Light , Microsoft YaHei}</style><select style='display:none'>"
			]
		];
		return $config;
	}
}
