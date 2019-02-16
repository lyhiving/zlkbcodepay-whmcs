<?php
//收款宝核心类
class zlkbcodepay
{
	private $apiHost="https://codepay.zlkb.net/api/order";
	
	//处理请求
	public function pay($payconfig,$params)
	{
		try{
			$config =array(
				'version'=>1,
				'paymethod'=>$params['paymethod'],
				'appid'=>$payconfig['app_id'],
				'ordersn'=>$params['orderid'],
				'subject'=>$params['subject'],
				'money'=>(float)$params['money'],
				'overtime'=>$payconfig['overtime'],
				'return_url' => $params['returnurl'],
				'notify_url' => $params['notifyurl'],
			);
			$config['sign'] = $this->signParams($config,$payconfig['app_secret']);
			$curl_data =  $this->_curlPost($this->apiHost,$config);
			$curl_data = json_decode($curl_data,true);
			if(is_array($curl_data)){
				if($curl_data['code']<1){
					return array('code'=>1002,'msg'=>$curl_data['msg'],'data'=>'');
				}else{
					return array('code'=>1,'msg'=>'success','data'=>$curl_data['data']);
				}
			}else{
				return array('code'=>1001,'msg'=>"支付接口请求失败",'data'=>'');
			}
		} catch (\Exception $e) {
			return array('code'=>1000,'msg'=>$e->getMessage(),'data'=>'');
		}
	}
	
	
	//异步处理返回
	public function notify($payconfig)
	{
		if(!empty($_POST)){
			$params = $_POST;
			$newsign = $this->signParams($params,$payconfig['app_secret']);
			
			if ($newsign != $params['sign']) { //不合法的数据 KEY密钥为你的密钥
				return 'error|Notify: auth fail';
			} else { //合法的数据
				//业务处理
				$config = array('tradeid'=>$params['orderid'],'paymoney'=>$params['money'],'orderid'=>$params['ordersn']);
				//开始处理
				
				
				
				//处理完成
				return "success";
			}
		}else{
			return 'error|Notify: empty';
		}
	}
	
	
	private function _curlPost($url,$params){
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL,$url);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_TIMEOUT,300); //设置超时
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE); // https请求 不验证证书和hosts
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
		$result = curl_exec($ch);
		curl_close($ch);
		return $result;	
	}
	
	public function signParams($params,$secret){
		$sign = $signstr = "";
		if(!empty($params)){
			ksort($params);
			reset($params);
			
			foreach ($params AS $key => $val) {
				if ($key == 'sign') continue;
				if ($signstr != '') {
					$signstr .= "&";
				}
				$signstr .= "$key=$val";
			}
			$sign = md5($signstr.$secret);
		}
		return $sign;
	}	
	
}