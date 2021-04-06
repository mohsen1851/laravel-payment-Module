<?php


namespace Mohsen\Payment\Repositories;


use Carbon\Carbon;
use Mohsen\Payment\Models\Payment;
use Mohsen\RolePermissions\Models\Permission;
use Mohsen\User\Models\User;

class PaymentRepo
{
    public static $query;

    public static function store($data)
    {
        Payment::create([
            'buyer_id' => $data['buyer_id'],
            'paymentable_id' => $data['paymentable_id'],
            'paymentable_type' => $data['paymentable_type'],
            'amount' => $data['amount'],
            'invoice_id' => $data['invoice_id'],
            'status' => $data['status'],
            'seller_p' => $data['seller_p'],
            'seller_share' => $data['seller_share'],
            'site_share' => $data['site_share'],
            'gateway' => $data['gateway']
        ]);
    }

    public static function findByAuthority($Authority)
    {
        return Payment::query()->where('invoice_id', $Authority)->firstOrFail();
    }

    public static function changeStatus(Payment $payment, $status)
    {
        $payment->update(['status' => $status]);
    }

    public static function paginate()
    {
        return self::$query->orderByDesc('payments.created_at')->paginate();
    }


    public static function generateQuery($days = null)
    {
        $query = Payment::query();
        $days ? $query->whereDate('created_at', '>=', now()->addDays(-1 * $days)) : null;
        $query->where('status', Payment::STATUS_SUCCESS);
        return $query;
    }


    public static function getLastNDaysSale($days = null)
    {
        return self::generateQuery($days)
            ->sum('amount');
    }

    public static function getLastNDaysBenefit($days = null)
    {
        return self::generateQuery($days)
            ->sum('site_share');
    }

    public static function searchEmail($email)
    {
        self::$query = Payment::query();
        if (!is_null($email)) {
            self::$query->whereHas('buyer', function ($q) use ($email) {
                $q->where('email', 'like', '%' . $email . '%');
            });
        }
        return self::$query;
    }

    public static function searchAmount($amount)
    {
        if (!is_null($amount)) {
            self::$query->where('amount', $amount);
        }
        return self::$query;
    }

    public static function searchInvoiceId($id)
    {
        if (!is_null($id)) {
            self::$query->where('payments.invoice_id', 'like', '%' . $id . '%');
        }
        return self::$query;
    }

    public static function searchAfterDate($start_in)
    {
        if (!is_null($start_in)) {

            $date = getDateFromJalali($start_in);
            self::$query->whereDate('payments.created_at', '>=', $date);
        }
        return self::$query;
    }

    public static function searchBeforeDate($end_in)
    {
        if (!is_null($end_in)) {
            $date = getDateFromJalali($end_in);
            self::$query->whereDate('payments.created_at', '<=', $date);
        }
        return self::$query;
    }

    public static function totalSells(User $user, $days=null)
    {
        return self::getSellerPayments($user,$days)->sum('amount');
    }

    public static function totalSiteShare(User $user, $days=null)
    {
        return self::getSellerPayments($user,$days)->sum('site_share');
    }

    public static function totalSellerShare(User $user, $days=null)
    {
        return self::getSellerPayments($user,$days)->sum('seller_share');
    }


    public static function checkIsSuperAdmin(User $user, \Illuminate\Database\Eloquent\Builder $query): void
    {
        if (!$user->can(Permission::PERMISSION_SUPER_ADMIN)) {
            $query->whereHas("paymentable", function ($q) use ($user) {
                $q->where('teacher_id', $user->id);
            });
        }
    }

    public static function getSellerPayments(User $user, $days = null)
    {
        $query = Payment::query();
        self::checkIsSuperAdmin($user, $query);
        if (!is_null($days)) {
            $query->whereDate("payments.created_at", ">=", now()->subDays($days));
        }
        return $query->where('status', Payment::STATUS_SUCCESS);
    }
}
