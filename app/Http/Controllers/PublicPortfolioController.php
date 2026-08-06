<?php

namespace App\Http\Controllers;

use App\Models\PortfolioItem;

class PublicPortfolioController extends Controller
{
    public function index()
    {
        $items = PortfolioItem::query()
            ->where('is_published', true)
            ->orderBy('sort_order')
            ->latest('event_date')
            ->latest()
            ->paginate(12);

        return view('public.portfolio', compact('items'));
    }
}
