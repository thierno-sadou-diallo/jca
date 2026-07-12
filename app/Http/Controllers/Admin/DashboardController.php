<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Appointment;
use App\Models\CooperationProject;
use App\Models\Document;
use App\Models\Faq;
use App\Models\HumanitarianProgram;
use App\Models\ImmigrationCase;
use App\Models\JobApplication;
use App\Models\JobPosting;
use App\Models\LeadRequest;
use App\Models\Message;
use App\Models\Partner;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        return view('admin.dashboard', [
            'stats' => [
                'leads' => LeadRequest::count(),
                'clients' => User::where('role', 'client')->count(),
                'admins' => User::where('role', 'admin')->count(),
                'newLeads' => LeadRequest::where('status', 'new')->count(),
                'publishedJobs' => JobPosting::where('status', 'published')->count(),
                'closedLeads' => LeadRequest::where('status', 'closed')->count(),
                'unreadMessages' => Message::where('recipient_id', auth()->id())->whereNull('read_at')->count(),
                'pendingDocuments' => Document::where('status', Document::STATUS_PENDING)->count(),
                'activeCases' => ImmigrationCase::whereNotIn('status', [
                    ImmigrationCase::STATUS_COMPLETED,
                    ImmigrationCase::STATUS_REJECTED,
                ])->count(),
                'newApplications' => JobApplication::where('status', JobApplication::STATUS_NEW)->count(),
                'publishedArticles' => Article::where('status', 'published')->count(),
                'publishedFaqs' => Faq::where('is_published', true)->count(),
                'featuredPartners' => Partner::where('is_featured', true)->count(),
                'activeCooperationProjects' => CooperationProject::where('status', 'active')->count(),
                'activeHumanitarianPrograms' => HumanitarianProgram::where('status', 'active')->count(),
                'pendingPayments' => Payment::where('status', Payment::STATUS_PENDING)->count(),
                'upcomingAppointments' => Appointment::where('starts_at', '>=', now())->whereIn('status', [
                    Appointment::STATUS_CONFIRMED,
                    Appointment::STATUS_RESCHEDULED,
                    Appointment::STATUS_REQUESTED,
                ])->count(),
            ],
            'latestLeads' => LeadRequest::latest()->limit(8)->get(),
            'latestJobs' => JobPosting::latest()->limit(6)->get(),
            'latestActivity' => DB::table('activity_logs')->latest()->limit(6)->get(),
        ]);
    }
}
