<?php


namespace Mohsen\Payment\Contracts;


use Mohsen\Payment\Models\Payment;

interface GatewayInterface
{
    public function request($amount,$description);

    public function verify(Payment $payment);

    public function redirect();

    public function getName();
}
