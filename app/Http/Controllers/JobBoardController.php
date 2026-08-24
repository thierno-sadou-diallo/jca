<?php

namespace App\Http\Controllers;

use App\Models\JobPosting;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class JobBoardController extends Controller
{
    public function index(Request $request): View
    {
        $query = $request->string('q')->toString();
        $sector = $request->string('sector')->toString();
        $country = $request->string('country')->toString();

        $jobs = Schema::hasTable('job_postings')
            ? JobPosting::query()
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
                ->withQueryString()
            : new LengthAwarePaginator(
                new Collection(),
                0,
                9,
                1,
                ['path' => $request->url(), 'query' => $request->query()],
            );

        $sectors = Schema::hasTable('job_postings')
            ? JobPosting::where('status', 'published')->whereNotNull('sector')->distinct()->orderBy('sector')->pluck('sector')
            : collect();

        $countries = Schema::hasTable('job_postings')
            ? JobPosting::where('status', 'published')->whereNotNull('country')->distinct()->orderBy('country')->pluck('country')
            : collect();

        return view('jobs.index', [
            'jobs' => $jobs,
            'query' => $query,
            'sector' => $sector,
            'country' => $country,
            'sectors' => $sectors,
            'countries' => $countries,
        ]);
    }
}
