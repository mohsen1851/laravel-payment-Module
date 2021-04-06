@extends('Dashboard::master')
@section('content')
    <div class="row no-gutters  ">
        <div class="col-3 padding-20 border-radius-3 bg-white margin-left-10 margin-bottom-10">
            <p>کل فروش ۳۰ روز گذشته سایت </p>
            <p>{{number_format($last30DaysSale)}}تومان</p>
        </div>
        <div class="col-3 padding-20 border-radius-3 bg-white margin-left-10 margin-bottom-10">
            <p>درامد خالص ۳۰ روز گذشته سایت</p>
            <p>{{number_format($lastDaysBenefit)}} تومان</p>
        </div>
        <div class="col-3 padding-20 border-radius-3 bg-white margin-left-10 margin-bottom-10">
            <p>کل فروش سایت</p>
            <p>{{number_format($totalSale)}} تومان</p>
        </div>
        <div class="col-3 padding-20 border-radius-3 bg-white margin-bottom-10">
            <p> کل درآمد خالص سایت</p>
            <p>{{number_format($totalBenefit)}} تومان</p>
        </div>

        <div class="col-12 margin-left-10 margin-bottom-15 border-radius-3">
            <div class="d-flex flex-space-between item-center flex-wrap padding-30 border-radius-3 bg-white">
                <p class="margin-bottom-15">همه تراکنش ها</p>
                <div class="t-header-search">
                    <form action="">
                        <div class="t-header-searchbox font-size-13">
                            <input type="text" class="text search-input__box font-size-13" placeholder="جستجوی تراکنش">
                            <div class="t-header-search-content ">
                                <input type="text" class="text" name="email" value="{{request('email')}}"
                                       placeholder="ایمیل">
                                <input type="text" class="text" name="amount" value="{{request('amount')}}"
                                       placeholder="مبلغ به تومان">
                                <input type="text" class="text" name="id" value="{{request('id')}}" placeholder="شماره">
                                <input type="text" class="text" name="start_in" value="{{request('start_in')}}"
                                       placeholder="از تاریخ : 1399/10/11">
                                <input type="text" class="text margin-bottom-20" name="end_in"
                                       value="{{request('end_in')}}" placeholder="تا تاریخ : 1399/10/12">
                                <input class="btn btn-webamooz_net" type="submit" value="جستجو">
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <p class="box__title">تراکنش ها</p>
            <div class="table__box">
                <table class="table">
                    <thead role="rowgroup">
                    <tr role="row" class="title-row">
                        <th>شناسه</th>
                        <th>شماره تراکنش</th>
                        <th>نام</th>
                        <th>ایمیل</th>
                        <th>مبلغ(تومان)</th>
                        <th>درآمد مدرس</th>
                        <th>درآمد سایت</th>
                        <th>نام دوره</th>
                        <th>تاریخ و ساعت</th>
                        <th>وضعیت</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($payments as $payment)
                        <tr role="row" class="">
                            <td><a href="">{{$payment->id}}</a></td>
                            <td>{{$payment->invoice_id}}</td>
                            <td>{{$payment->buyer->name}}</td>
                            <td>{{$payment->buyer->email}}</td>
                            <td>{{number_format($payment->amount)}}</td>
                            <td>{{number_format($payment->seller_share)}}</td>
                            <td>{{number_format($payment->site_share)}}</td>
                            <td>{{$payment->paymentable->title}}</td>
                            <td>{{jdate($payment->created_at)}}</td>
                            <td class="@if($payment->status==\Mohsen\Payment\Models\Payment::STATUS_SUCCESS) text-success @else text-error @endif">@lang($payment->status)</td>
                        </tr>
                    @endforeach


                    </tbody>
                </table>
            </div>
        </div>
    </div>

@endsection

@section('css')
    <link rel="stylesheet" href="/panel/css/jquery_toast_plugin.css"/>
@endsection
@section('js')
    <script src="/panel/js/jquery_toast_plugin.js"></script>
    @include('Common::layouts.feedbacks')
    <script>
        function deleteItem(event, route) {
            if (confirm('آیا از حذف این آیتم اطمینان دارید؟')) {
                $.post(route, {
                    _method: "delete",
                    _token: "{{csrf_token()}}"
                })
                    .done(function (response) {
                            event.target.closest('tr').remove();
                            $.toast({
                                heading: 'عملیات موفق',
                                text: response.message,
                                showHideTransition: 'slide',
                                icon: 'success'
                            })
                        }
                    )
                    .fail(function (response) {
                            $.toast({
                                heading: 'عملیات ناموفق',
                                text: 'خطایی رخ داد!',
                                showHideTransition: 'slide',
                                icon: 'warning'
                            })
                        }
                    )
            }
        }


        function updateConfirmationStatus(event, route, message, status) {
            if (confirm(message)) {
                $.post(route, {
                    _method: "PATCH",
                    _token: "{{csrf_token()}}"
                })
                    .done(function (response) {
                            $(event.target).closest('tr').find('td#verified').text(status);
                            $.toast({
                                heading: 'عملیات موفق',
                                text: response.message,
                                showHideTransition: 'slide',
                                icon: 'success'
                            })
                        }
                    )
                    .fail(function (response) {
                            $.toast({
                                heading: 'عملیات ناموفق',
                                text: 'خطایی رخ داد!',
                                showHideTransition: 'slide',
                                icon: 'warning'
                            })
                        }
                    )
            }
        }
    </script>
@endsection

@section('breadcrumb')
    <li><a href="#" title="ترامنش ها">تراکنش ها</a></li>
@endsection

