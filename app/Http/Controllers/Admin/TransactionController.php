<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $q = Payment::with(['order:id,order_number,user_id'])->orderByDesc('id');

        if ($request->filled('status')) {
            $q->where('status', $request->string('status'));
        }
        if ($request->filled('gateway')) {
            $q->where('gateway', $request->string('gateway'));
        }
        if ($request->filled('method')) {
            $q->where('payment_method', 'like', '%'.$request->string('method').'%');
        }
        if ($request->filled('search')) {
            $s = trim($request->string('search'));
            $q->where(function($qq) use ($s){
                $qq->where('transaction_id','like','%'.$s.'%')
                   ->orWhere('amount','like','%'.$s.'%');
            });
        }

        $payments = $q->paginate(20)->appends($request->query());

        return view('admin.transactions.index', compact('payments'));
    }
}
