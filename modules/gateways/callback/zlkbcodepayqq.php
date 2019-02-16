<?php
// Require libraries needed for gateway module functions.
require_once __DIR__ . '/../../../init.php';
require_once __DIR__ . '/../../../includes/gatewayfunctions.php';
require_once __DIR__ . '/../../../includes/invoicefunctions.php';
require_once __DIR__ . '/../../../modules/gateways/class/zlkbcodepay/zlkbcodepay.class.php';

use Illuminate\Database\Capsule\Manager as Capsule;
function convert_helper($invoiceid,$amount){
    $setting = Capsule::table("tblpaymentgateways")->where("gateway","zlkbcodepayqq")->where("setting","convertto")->first();
    ///系统没多货币 , 直接返回
    if (empty($setting)){ return $amount; }
    
    
    ///获取用户ID 和 用户使用的货币ID
    $data = Capsule::table("tblinvoices")->where("id",$invoiceid)->get()[0];
    $userid = $data->userid;
    $currency = getCurrency( $userid );

    /// 返回转换后的
    return  convertCurrency( $amount , $setting->value  ,$currency["id"] );
}

// Detect module name from filename.
$gatewayModuleName = basename(__FILE__, '.php');

// Fetch gateway configuration parameters.
$gatewayParams = getGatewayVariables($gatewayModuleName);

// Die if module is not active.
if(!$gatewayParams['type']) {
    die("Module Not Activated");
}

if(!empty($_GET) AND isset($_GET['paytime'])){ //同步回调通知
	$zlkbcodepay = new zlkbcodepay();
	//验证签名
	$sign = $zlkbcodepay->signParams($_GET,$gatewayParams['app_secret']);
	if($sign == $_GET['sign']){
		echo '<script>alert("付款成功！");window.location.href = "' . $gatewayParams['systemurl'] . '/cart.php?a=complete' . '";</script>';exit;
	}
	echo '<script>alert("付款失败，请重试！");history.back(-1);</script>';exit;
}
if(!empty($_POST) AND $_POST['paytime']){//异步回调通知
	$zlkbcodepay = new zlkbcodepay();
	//验证签名
	$sign = $zlkbcodepay->signParams($_POST,$gatewayParams['app_secret']);
	if($sign == $_POST['sign']){
		$invoice_id = $_POST['ordersn'];  //商户网站唯一订单号
		$trade_no = $_POST['orderid'];    //支付宝交易号
		$amount = $_POST['money']; //交易金额

        $invoiceid = checkCbInvoiceID($invoice_id,$gatewayParams["name"]);
        $amount = convert_helper( $invoice_id, $amount );
        checkCbTransID($trade_no);
        addInvoicePayment($invoiceid,$trade_no,$amount,"0",$gatewayModuleName);
        logTransaction($gatewayParams['name'], $_POST, "异步回调入账 #" . $invoiceid);
        exit("success");
	}else{
		exit("Verify Signature Failure");
	}
}
echo '<script>history.back(-1);</script>';exit;