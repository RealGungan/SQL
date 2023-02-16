<?php
function testPaymentInfo($payment_info)
{
    if (
        strlen($payment_info) == 7
        && ctype_alpha(substr($payment_info, 0, 2))
        && is_numeric(substr($payment_info, 2))
    ) {
        return true;
    } else {
        return false;
    }
}
