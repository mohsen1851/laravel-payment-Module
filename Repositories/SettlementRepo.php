<?php


namespace Mohsen\Payment\Repositories;


use Mohsen\Payment\Models\Settlement;
use Mohsen\User\Models\User;

class SettlementRepo
{
    public static function store($data)
    {
        Settlement::query()->create([
            "to" => ["cart" => $data["cart"], "name" => $data["name"]], "amount" => $data["amount"], "user_id" => auth()->id()
        ]);
        auth()->user()->update([
            "balance" => auth()->user()->balance - $data["amount"]
        ]);
    }

    public static function paginate(User $user = null)
    {
        $query = Settlement::query();
        if (!is_null($user)) {
            $query->where('user_id', $user->id);
        }
        return $query->orderByDesc('id')->paginate();
    }

    public static function update(Settlement $settlement, $data)
    {
        if (in_array($data['status'], [Settlement::STATUS_REJECTED, Settlement::STATUS_CANCELED]) and
            !in_array($settlement->status, [Settlement::STATUS_REJECTED, Settlement::STATUS_CANCELED])
        ) {
            auth()->user()->update([
                "balance" => auth()->user()->balance + $settlement->amount
            ]);
        }

        if (in_array($data['status'], [Settlement::STATUS_SETTLED, Settlement::STATUS_PENDING]) and
            !in_array($settlement->status, [Settlement::STATUS_SETTLED, Settlement::STATUS_PENDING])
        ) {
            auth()->user()->update([
                "balance" => auth()->user()->balance - $settlement->amount
            ]);
        }
        $settlement->update([
            "to" => ["cart" => $data['to']["cart"], "name" => $data['to']["name"]],
            "from" => ["cart" => $data['from']["cart"], "name" => $data['from']["name"]],
            'status' => $data['status']
        ]);
    }

    public static function findLastRequest(User $user)
    {
        return Settlement::query()->orderByDesc('id')->first();
    }
}
