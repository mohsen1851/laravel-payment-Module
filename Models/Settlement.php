<?php


namespace Mohsen\Payment\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Mohsen\User\Models\User;

class Settlement extends Model
{
    use HasFactory;

    protected $guarded = [];

    const STATUS_PENDING = "pending";
    const STATUS_SETTLED = "settled";
    const STATUS_REJECTED = "rejected";
    const STATUS_CANCELED = "canceled";
    const Statuses = [self::STATUS_PENDING, self::STATUS_CANCELED, self::STATUS_REJECTED, self::STATUS_SETTLED];

    protected $casts = [
        "from" => "json",
        "to" => "json"
    ];

    public function user()
    {
      return  $this->belongsTo(User::class);
    }
}
