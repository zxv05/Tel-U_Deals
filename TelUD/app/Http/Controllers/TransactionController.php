<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Deal;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    public function buy($id)
    {
        $deal = Deal::findOrFail($id);

        Transaction::create([
            'user_id' => Auth::id(), // PK custom
            'deal_id' => $deal->id,
            'total'   => $deal->harga,
        ]);

        return redirect()->route('deals.index')
            ->with('success', 'Deal berhasil dibeli!');
    }
}
