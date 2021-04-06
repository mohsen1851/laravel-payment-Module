<?php


namespace Mohsen\Payment\Http\Controllers;


use App\Http\Controllers\Controller;
use Mohsen\Payment\Http\Requests\StoreSettlementRequest;
use Mohsen\Payment\Http\Requests\UpdateSettlementRequest;
use Mohsen\Payment\Models\Settlement;
use Mohsen\Payment\Repositories\SettlementRepo;
use Mohsen\User\Models\User;
use phpDocumentor\Reflection\DocBlock\Tags\Uses;
use PHPUnit\TextUI\XmlConfiguration\UpdateSchemaLocationTo93;

class SettlementController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        /** @var User $user */
        if ($user->can("manage", Settlement::class)) {
            $settlements = SettlementRepo::paginate();
        } elseif ($user->can("store", Settlement::class)) {
            $settlements = SettlementRepo::paginate($user);
        }else{
            abort(403);
        }

        return view("Payment::settlement.index", compact('settlements'));
    }

    public function create()
    {
        $this->authorize('store', Settlement::class);
        $lastSettlement = SettlementRepo::findLastRequest(auth()->user());
        if ($lastSettlement and $lastSettlement->status == Settlement::STATUS_PENDING) {
            newFeedback("ناموفق", "شما از قبل درخواست تسویه نشده ای دارید", "error");
            return redirect()->route('settlements.index');
        }
        return view("Payment::settlement.create");
    }

    public function store(StoreSettlementRequest $request)
    {
        $this->authorize('store', Settlement::class);
        $lastSettlement = SettlementRepo::findLastRequest(auth()->user());
        if ($lastSettlement and $lastSettlement->status == Settlement::STATUS_PENDING) {
            newFeedback("ناموفق", "شما از قبل درخواست تسویه نشده ای دارید", "error");
            return redirect()->route('settlements.index');
        }
        SettlementRepo::store($request->validated());
        newFeedback();
        return redirect()->route('settlements.index');
    }

    public function edit(Settlement $settlement)
    {
        $this->authorize('manage', Settlement::class);
        $lastSettlement = SettlementRepo::findLastRequest($settlement->user);
        if (!($lastSettlement->id == $settlement->id)) {
            newFeedback("ناموفق", "شما فقط میتوانید آخرین درخواست را ویرایش نمائید", "error");
            return redirect()->route('settlements.index');
        }
        return view("Payment::settlement.edit", compact('settlement'));
    }

    public function update(UpdateSettlementRequest $request, Settlement $settlement)
    {
        $this->authorize('manage', Settlement::class);
        $lastSettlement = SettlementRepo::findLastRequest($settlement->user);
        if (!($lastSettlement->id == $settlement->id)) {
            newFeedback("ناموفق", "شما فقط میتوانید آخرین درخواست را ویرایش نمائید", "error");
            return redirect()->route('settlements.index');
        }
        SettlementRepo::update($settlement, $request->validated());
        return redirect()->route('settlements.index');
    }
}
