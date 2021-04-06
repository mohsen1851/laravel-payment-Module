@extends('Dashboard::master')
@section('content')
    <div class="main-content">
        <div class="tab__box">
            <div class="tab__items">
                <a class="tab__item is-active" href="checkouts.html"> همه تسویه ها</a>
                <a class="tab__item " href="checkouts.html">تسویه های جدید</a>
                <a class="tab__item " href="?status={{\Mohsen\Payment\Models\Settlement::STATUS_SETTLED}}">تسویه های
                    واریز شده</a>
                <a class="tab__item " href="{{route('settlements.create')}}">درخواست تسویه جدید</a>
            </div>
        </div>
        <div class="bg-white padding-20">
            <div class="t-header-search">
                <form action="" onclick="event.preventDefault();">
                    <div class="t-header-searchbox font-size-13">
                        <input type="text" class="text search-input__box font-size-13"
                               placeholder="جستجوی در تسویه حساب ها">
                        <div class="t-header-search-content ">
                            <input type="text" class="text" placeholder="شماره کارت">
                            <input type="text" class="text" placeholder="شماره">
                            <input type="text" class="text" placeholder="تاریخ">
                            <input type="text" class="text" placeholder="ایمیل">
                            <input type="text" class="text margin-bottom-20" placeholder="نام و نام خانوادگی">
                            <btutton class="btn btn-webamooz_net">جستجو</btutton>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="table__box">
            <table class="table">
                <thead role="rowgroup">
                <tr role="row" class="title-row">
                    <th>شناسه تسویه</th>
                    <th>درخواست کننده</th>
                    <th>مبدا</th>
                    <th>مقصد</th>
                    <th>شماره کارت</th>
                    <th>تاریخ درخواست واریز</th>
                    <th>تاریخ واریز شده</th>
                    <th>مبلغ (تومان )</th>
                    <th>وضعیت</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                @foreach($settlements as $settlement)
                    <tr role="row">
                        <td><a href="">{{$settlement->transaction_id}}</a></td>
                        <td><a href="">{{$settlement->user->name}}</a></td>
                        <td><a href="">{{$settlement->from?$settlement->from['name']:"-"}}</a></td>
                        <td><a href="">{{$settlement->to?$settlement->to['name']:"-"}}</a></td>
                        <td><a href="">{{$settlement->to?$settlement->to['cart']:"-"}}</a></td>
                        <td><a href="">{{$settlement->created_at->diffForHumans()}}</a></td>
                        <td><a href="">{{$settlement->settled_at?$settlement->settled_at->diffForHumans():"-"}}</a></td>
                        <td><a href="">{{$settlement->amount}}</a></td>
                        <td><a href="">@lang($settlement->status)</a></td>
                        @can(\Mohsen\RolePermissions\Models\Permission::PERMISSION_MANAGE_SETTLEMENTS)
                            <td>
                                <a href="{{route("settlements.edit",$settlement->id)}}" class="item-edit "
                                   title="ویرایش"></a>
                            </td>
                        @endCan
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
@section('breadcrumb')
    <li><a href="#" title="درخواست تسویه">درخواست های تسویه</a></li>
@endsection

@section('css')
    <link rel="stylesheet" href="/panel/css/jquery_toast_plugin.css"/>
@endsection
@section('js')
    <script src="/panel/js/jquery_toast_plugin.js"></script>
    @include('Common::layouts.feedbacks')
@endsection

