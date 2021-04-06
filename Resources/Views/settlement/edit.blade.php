@extends('Dashboard::master')
@section('content')
    <div class="main-content">
        <form action="{{route('settlements.update',$settlement->id)}}" method="post"
              class="padding-30 bg-white font-size-14">@csrf @method("patch")
            <x-input-Box type="text" name="from[name]"
                         inputValue="{{$settlement->from?$settlement->from ['name']:null}}" errorKey="from.name"
                         placeholder="نام صاحب حساب فرستنده" class="text"/>
            <x-input-Box type="text" name="from[cart]"
                         inputValue="{{$settlement->from?$settlement->from ['cart']:null}}" errorKey="from.cart"
                         placeholder="شماره کارت فرستنده" class="text"/>
            <x-input-Box type="text" name="to[name]" inputValue="{{$settlement->to?$settlement->to['name']:null}}"
                         errorKey="to.name"
                         placeholder="نام صاحب حساب گیرنده" class="text"/>
            <x-input-Box type="text" name="to[cart]" inputValue="{{$settlement->to?$settlement->to['cart']:null}}"
                         errorKey="to.cart"
                         placeholder="شماره کارت گیرنده" class="text"/>
            <x-input-Box type="text" name="amount" inputValue="{{$settlement->amount}}" placeholder="مبلغ به تومان"
                         class="text"/>
            <x-select name="status">
                @foreach(\Mohsen\Payment\Models\Settlement::Statuses as $status)
                    <option value="{{$status}}" @if($settlement->status==$status)selected @endif>@lang($status)</option>
                @endforeach
            </x-select>
            <button type="submit" class="btn btn-webamooz_net">درخواست تسویه</button>
        </form>
    </div>

@endsection
@section('breadcrumb')
    <li><a href="#" title="ویرایش درخواست تسویه">ویرایش درخواست تسویه</a></li>
@endsection

