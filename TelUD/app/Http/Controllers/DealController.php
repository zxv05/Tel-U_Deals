<?php

namespace App\Http\Controllers;

use App\Models\Deal;
use Illuminate\Http\Request;

class DealController extends Controller
{
    public function index()
    {
        return view('admin.deals.index', [
            'deals' => Deal::all()
        ]);
    }

    public function create()
    {
        return view('admin.deals.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required',
            'harga' => 'required|numeric',
        ]);

        Deal::create($request->all());

        return redirect()->route('admin.deals');
    }
}
