@extends('Dashboard::master')
@section('content')
    <div class="table__box">
        <table class="table">
            <thead>
            <tr class="title-row">
                <th>عنوان دوره</th>
                <th>تاریخ پرداخت</th>
                <th>مقدار پرداختی</th>
                <th>وضعیت پرداخت</th>
            </tr>
            </thead>
            <tbody>
            @foreach($payments as $payment)
                <tr>
                    <td>{{$payment->paymentable->title}}</td>
                    <td>{{jdate($payment->created_at)}}</td>
                    <td>{{number_format($payment->amount)}} تومان</td>
                    <td class="@if($payment->status==\Mohsen\Payment\Models\Payment::STATUS_SUCCESS) text-success @else text-error @endif">
                        @lang($payment->status)</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    <div class="pagination">
        {{$payments->render()}}
    </div>

@endsection


@section('breadcrumb')
    <li><a href="#" title="خرید  ها">خرید ها </a></li>
@endsection

