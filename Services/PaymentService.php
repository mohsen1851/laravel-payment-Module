<?php


namespace Mohsen\Payment\Services;


use Mohsen\Payment\Contracts\GatewayInterface;
use Mohsen\Payment\Gateways\Gateway;
use Mohsen\Payment\Models\Payment;
use Mohsen\Payment\Repositories\PaymentRepo;

class PaymentService
{
    public static function generate($amount, $paymentable, $buyer)
    {
        $seller_p=$paymentable->percent;
        $seller_share=($amount/100)*$seller_p;
        $site_share=$amount-$seller_share;
        /** @var GatewayInterface $gateway */
        $gateway=resolve(Gateway::class);
        $invoice_id=$gateway->request($amount,$paymentable->title);
        if(is_array($invoice_id)){
            //todo
            dd($invoice_id);
        }

        PaymentRepo::store([
            'buyer_id' => $buyer->id,
            'paymentable_id'=>$paymentable->id,
            'paymentable_type'=>get_class($paymentable),
            'amount'=>$amount,
            'invoice_id'=>$invoice_id,
            'status'=>Payment::STATUS_PENDING,
            'seller_p'=>$seller_p,
            'seller_share'=>$seller_share,
            'site_share'=>$site_share,
            'gateway'=>$gateway->getName()
        ]);
    }
}
