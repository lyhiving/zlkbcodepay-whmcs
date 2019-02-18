<?php
if (!defined("WHMCS")) {
    die("This file cannot be accessed directly");
}

function zlkbcodepaywx_MetaData()
{
    return array(
        'DisplayName' => '收款宝(微信支付)',
        'APIVersion' => '1.0', // Use API Version 1.0
    );
}

function zlkbcodepaywx_config()  
{
    require_once __DIR__ ."/class/zlkbcodepay/zlkbcodepay.config.php";
    $config = new zlkbcodepay_config();
    return $config->get_configuration(1);
}

function zlkbcodepaywx_link($params)
{
    require_once __DIR__ ."/class/zlkbcodepay/zlkbcodepay.link.php";
    $link = new zlkbcodepay_link();
    return $link->get_paylink($params,1);
}