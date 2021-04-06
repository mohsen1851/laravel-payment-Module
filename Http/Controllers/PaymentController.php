<?php


namespace Mohsen\Payment\Http\Controllers;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Mohsen\Payment\Events\PaymentWasSuccessful;
use Mohsen\Payment\Gateways\Gateway;
use Mohsen\Payment\Models\Payment;
use Mohsen\Payment\Repositories\PaymentRepo;

class PaymentController extends Controller
{
    public function callback(Request $request)
    {
        /** @var Payment $payment */
        $payment = PaymentRepo::findByAuthority($request->Authority);
        $result = resolve(Gateway::class)->verify($payment);
        if (is_array($result)) {
            newFeedback('عملیات ناموفق', $result['message'], 'error');
            PaymentRepo::changeStatus($payment, Payment::STATUS_FAIL);
        } else {
            newFeedback('عملیات موفق', 'پرداخت با موفقیت انجام شد', 'success');
            PaymentRepo::changeStatus($payment, Payment::STATUS_SUCCESS);
            event(new PaymentWasSuccessful($payment));
        }
        return redirect($payment->paymentable->path());
    }

    public function index(Request $request)
    {
        $this->authorize("manage", Payment::class);

        PaymentRepo::searchEmail($request->email);
        PaymentRepo::searchAmount($request->amount);
        PaymentRepo::searchInvoiceId($request->id);
        PaymentRepo::searchAfterDate($request->start_in);
        PaymentRepo::searchBeforeDate($request->end_in);
        $payments=PaymentRepo::paginate();
        $last30DaysSale=PaymentRepo::getLastNDaysSale(30);
        $lastDaysBenefit=PaymentRepo::getLastNDaysBenefit(1);
        $totalSale=PaymentRepo::getLastNDaysSale(null);
        $totalBenefit=PaymentRepo::getLastNDaysBenefit(null);
        return view("Payment::index",compact('payments','last30DaysSale','lastDaysBenefit','totalSale','totalBenefit'));
    }

    public function purchases()
    {
        $payments=auth()->user()->payments()->with('paymentable')->paginate();
        return view('Payment::purchases',compact('payments'));
    }
}
