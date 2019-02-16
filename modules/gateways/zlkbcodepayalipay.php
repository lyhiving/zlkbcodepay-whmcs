<?php
if (!defined("WHMCS")) {
    die("This file cannot be accessed directly");
}

function zlkbcodepayalipay_MetaData()
{
    return array(
        'DisplayName' => '收款宝(支付宝)',
        'APIVersion' => '1.0', // Use API Version 1.0
    );
}

function zlkbcodepayalipay_config()  
{
    require_once __DIR__ ."/class/zlkbcodepay/zlkbcodepay.config.php";
    $config = new zlkbcodepay_config();
    return $config->get_configuration();
}

function zlkbcodepayalipay_link($params)
{
    require_once __DIR__ ."/class/zlkbcodepay/zlkbcodepay.link.php";
    $link = new zlkbcodepay_link();
    return $link->get_paylink($params,2);
}