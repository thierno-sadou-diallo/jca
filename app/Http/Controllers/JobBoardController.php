<?php

namespace App\Http\Controllers;

use App\Models\JobPosting;
use Illuminate\Http\Request;
use Illuminate\View\View;

class JobBoardController extends Controller
{
    public function index(Request $request): View
    {
        $query = $request->string('q')->toString();
        $sector = $request->string('sector')->toString();
        $country = $request->string('country')->toString();

        $jobs = JobPosting::query()
            ->where('status', 'published')
            ->when($query, fn ($builder) => $builder->where(function ($inner) use ($query): void {
                $inner->where('title', 'like', "%{$query}%")
                    ->orWhere('description', 'like', "%{$query}%")
                    ->orWhere('company_name', 'like', "%{$query}%");
            }))
            ->when($sector, fn ($builder) => $builder->where('sector', $sector))
            ->when($country, fn ($builder) => $builder->where('country', $country))
            ->latest('published_at')
            ->paginate(9)
            ->withQueryString();

        return view('jobs.index', [
            'jobs' => $jobs,
            'query' => $query,
            'sector' => $sector,
            'country' => $country,
            'sectors' => JobPosting::where('status', 'published')->whereNotNull('sector')->distinct()->orderBy('sector')->pluck('sector'),
            'countries' => JobPosting::where('status', 'published')->whereNotNull('country')->distinct()->orderBy('country')->pluck('country'),
        ]);
    }
}
