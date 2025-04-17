<?php

$payment_amount = 7000;
$remaining_balance = "5375.00";
$is_create_credit = true;

var_dump($payment_amount);
var_dump((float)$remaining_balance);

echo ($payment_amount > (float)$remaining_balance && $is_create_credit) ? 'true' : 'false';