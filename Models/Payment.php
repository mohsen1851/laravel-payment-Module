<?php


namespace Mohsen\Payment\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Mohsen\Course\Database\factory\CourseFactory;
use Mohsen\Payment\Database\factory\PaymentFactory;
use Mohsen\User\Models\User;

class Payment extends Model
{
    use HasFactory;
    protected $guarded = [];
    const STATUS_PENDING = "pending";
    const STATUS_CANCELED = "canceled";
    const STATUS_SUCCESS = "success";
    const STATUS_FAIL = "fail";

    const Statuses = [self::STATUS_PENDING, self::STATUS_CANCELED, self::STATUS_SUCCESS, self::STATUS_FAIL];
    protected static function newFactory()
    {
        return PaymentFactory::new();
    }
    public function paymentable()
    {
        return $this->morphTo('paymentable');
    }

    public function buyer()
    {
        return $this->belongsTo(User::class,"buyer_id");
    }
}
