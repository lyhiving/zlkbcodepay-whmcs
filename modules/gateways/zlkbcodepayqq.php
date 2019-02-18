<?php
if (!defined("WHMCS")) {
    die("This file cannot be accessed directly");
}

function zlkbcodepayqq_MetaData()
{
    return array(
        'DisplayName' => '收款宝(QQ)',
        'APIVersion' => '1.0', // Use API Version 1.0
    );
}

function zlkbcodepayqq_config()  
{
    require_once __DIR__ ."/class/zlkbcodepay/zlkbcodepay.config.php";
    $config = new zlkbcodepay_config();
    return $config->get_configuration(3);
}

function zlkbcodepayqq_link($params)
{
    require_once __DIR__ ."/class/zlkbcodepay/zlkbcodepay.link.php";
    $link = new zlkbcodepay_link();
    return $link->get_paylink($params,3);
}