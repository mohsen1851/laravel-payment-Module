<?php


namespace Mohsen\Payment\Database\factory;


use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Http\UploadedFile;
use Mohsen\Category\Models\Category;
use Mohsen\Course\Models\Course;
use Mohsen\Media\Services\MediaFileService;
use Mohsen\Payment\Gateways\Gateway;
use Mohsen\Payment\Models\Payment;
use Mohsen\RolePermissions\Models\Permission;
use Mohsen\User\Models\User;
use phpDocumentor\Reflection\Types\Null_;

class PaymentFactory extends Factory
{

    protected $model = Payment::class;

    public function definition()
    {
        $student_id = User::query()->inRandomOrder()->first()->id;
        $paymentable = Course::query()->inRandomOrder()->first();
        return [
            'buyer_id' => $student_id,
            'paymentable_id' => $paymentable->id,
            'paymentable_type' => get_class($paymentable),
            'amount' => $paymentable->price,
            'invoice_id' => random_int(10000000000, 99999999999),
            'status' => $this->faker->randomElement(Payment::Statuses),
            'seller_p' => $paymentable->percent,
            'seller_share' => $paymentable->amount * $paymentable->percent,
            'site_share' => $paymentable->amount - ($paymentable->amount * $paymentable->percent),
            'gateway' => resolve(Gateway::class)->getName()
        ];
    }
}
