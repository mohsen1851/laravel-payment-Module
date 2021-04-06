<?php


namespace Mohsen\Payment\Providers;


use Mohsen\Course\Listeners\RegisterUserInTheCourse;
use Mohsen\Payment\Events\PaymentWasSuccessful;
use Mohsen\User\Listeners\AddSellerShareToHisProfile;

class EventServiceProvider extends \Illuminate\Foundation\Support\Providers\EventServiceProvider
{
    protected $listen = [
        PaymentWasSuccessful::class => [
            RegisterUserInTheCourse::class,
            AddSellerShareToHisProfile::class
        ]
    ];

}
