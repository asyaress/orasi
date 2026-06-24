<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\OrasiIlmiah;

class AdminArsipController extends Controller
{
    public function index()
    {
        $items = OrasiIlmiah::query()
            ->orderByDesc('tanggal_pelaksanaan')
            ->orderByDesc('id')
            ->get();

        return view('admin.arsip.index', compact('items'));
    }
}
