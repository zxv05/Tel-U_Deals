<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Deal;

class TransactionController extends Controller
{
    public function buy($id)
    {
        $deal = Deal::findOrFail($id);

        Transaction::create([
            'user_id' => auth()->user()->IdUser, // PK custom
            'deal_id' => $deal->id,
            'total'   => $deal->harga,
        ]);

        return redirect()->route('deals.index')
            ->with('success', 'Deal berhasil dibeli!');
    }
}
