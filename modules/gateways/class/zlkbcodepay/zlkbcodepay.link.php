<?php
if (!defined("WHMCS")) {
	die("This file cannot be accessed directly");
}

class zlkbcodepay_link 
{
	public function get_paylink($params,$paymethod)
	{
		if (!function_exists("openssl_open"))
		{
			return '<span style="color:red">Fatal Error:管理员未开启openssl组件<br/>正常情况下该组件必须开启<br/>请开启openssl组件解决该问题</span>';
		}
		if (!function_exists("scandir"))
		{
			return '<span style="color:red">Fatal Error:管理员未开启scandir PHP函数<br/>支付宝Sdk 需要使用该函数<br/>请修改php.ini下的disable_function来解决该问题</span>';
		}
		if (empty($params['app_id']))
		{
			return "管理员未配置 应用ID , 无法使用该支付接口";
		} 
		if (empty($params['app_secret']))
		{
			return "管理员未配置 app_secret  , 无法使用该支付接口";
		}	
		if (empty($params['overtime']))
		{
			return "管理员未配置 overtime  , 无法使用该支付接口";
		}	
		return $this->Pay($params,$paymethod);
	}
	
	public function Pay($params,$paymethod)
	{
		require_once __DIR__ ."/zlkbcodepay.class.php";
		
		if($paymethod=="1"){
			$notifyurl = $params['systemurl'].'/modules/gateways/callback/zlkbcodepaywx.php';
		}elseif($paymethod=="2"){
			$notifyurl = $params['systemurl'].'/modules/gateways/callback/zlkbcodepayalipay.php';
		}else{
			$notifyurl = $params['systemurl'].'/modules/gateways/callback/zlkbcodepayqq.php';
		}
		
		//API请求,创建订单
		$params = array(
			'paymethod'=>$paymethod,
			'orderid'=>$params['invoiceid'],
			'subject'=>"Billing"."-".$params['invoiceid'],
			'money'=>$params['amount'],
			'returnurl'=>$notifyurl,
			'notifyurl'=>$notifyurl,
		);
		//配置
		$payconfig = array(
			'app_id'=>$params['app_id'], //官网应用id
			'app_secret'=>$params['app_secret'],//官网应用secret
			'overtime'=>$params['overtime'], //超时时间
		);
		$zlkbcodepay = new zlkbcodepay();
		$result = $zlkbcodepay->pay($payconfig,$params);
		if(!empty($result)){
			if($result['code']!="1"){
				return $result['msg'];
			}else{
				$image_url = $params['systemurl'].'/modules/gateways/class/zlkbcodepay/images/'.$paymethod.'.jpg';
				$html_tpl = '
				<!--
					可用变量
					$qr_url   - 支付链接
				-->
				<script src="https://cdn.jsdelivr.net/npm/jquery@3.3.1/dist/jquery.min.js"></script>
				<script>
					//3秒后自动跳转
					setTimeout(function(){
						location.href = {$qr_url};
					},3000);
				</script>
				<a href= "{$qr_url}" ><img src="'.$image_url.'" style="width:120px;"></a>';
				$html = str_replace('{$qr_url}',$result['data']['payurl'],$html_tpl);
				return $html;
			}
		}else{
			return "创建订单失败";
		}	
	}	
}
