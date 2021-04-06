<?php

namespace Mohsen\Payment\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Mohsen\Payment\Models\Settlement;

class UpdateSettlementRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            "from.*" => "required_if:status," . Settlement::STATUS_SETTLED,
            "to.*" => "required_if:status," . Settlement::STATUS_SETTLED,
            "status"=>['required',Rule::in(Settlement::Statuses)]
        ];
    }

    public function attributes()
    {
        return [
            'from.cart' => 'شماره کارت فرستنده',
            'from.name' => 'نام فرستنده',
            'to.cart' => 'شماره کارت گیرنده',
            'to.name' => 'نام گینده',
        ];
    }
}
