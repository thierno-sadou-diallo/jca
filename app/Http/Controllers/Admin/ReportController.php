<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Document;
use App\Models\ImmigrationCase;
use App\Models\JobApplication;
use App\Models\JobPosting;
use App\Models\LeadRequest;
use App\Models\Message;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ReportController extends Controller
{
    public function index(): View
    {
        return view('admin.reports.index', [
            'kpis' => [
                'clients' => User::where('role', 'client')->count(),
                'leads' => LeadRequest::count(),
                'documents' => Document::count(),
                'cases' => ImmigrationCase::count(),
                'appointments' => Appointment::count(),
                'applications' => JobApplication::count(),
                'jobs' => JobPosting::count(),
                'messages' => Message::count(),
            ],
            'leadStatuses' => $this->countsByStatus(LeadRequest::class),
            'documentStatuses' => $this->countsByStatus(Document::class),
            'caseStatuses' => $this->countsByStatus(ImmigrationCase::class, ImmigrationCase::statuses()),
            'appointmentStatuses' => $this->countsByStatus(Appointment::class, Appointment::statuses()),
            'applicationStatuses' => $this->countsByStatus(JobApplication::class, JobApplication::statuses()),
            'clientTypes' => DB::table('user_profiles')
                ->select('type_client', DB::raw('count(*) as total'))
                ->groupBy('type_client')
                ->orderByDesc('total')
                ->get(),
            'monthlyLeads' => $this->monthlyCounts('lead_requests'),
        ]);
    }

    /**
     * @param class-string $model
     * @param array<string, string> $labels
     * @return array<int, array{label: string, value: int}>
     */
    private function countsByStatus(string $model, array $labels = []): array
    {
        return $model::query()
            ->select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->orderByDesc('total')
            ->get()
            ->map(fn ($row): array => [
                'label' => $labels[$row->status] ?? (string) $row->status,
                'value' => (int) $row->total,
            ])
            ->all();
    }

    /**
     * @return array<int, array{label: string, value: int}>
     */
    private function monthlyCounts(string $table): array
    {
        $months = collect(range(5, 0))->map(fn (int $monthsAgo): Carbon => now()->subMonths($monthsAgo)->startOfMonth());

        return $months->map(function (Carbon $month) use ($table): array {
            return [
                'label' => $month->format('m/Y'),
                'value' => DB::table($table)
                    ->whereBetween('created_at', [$month->copy()->startOfMonth(), $month->copy()->endOfMonth()])
                    ->count(),
            ];
        })->all();
    }
}
