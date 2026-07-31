<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\Appointment;
use App\Models\ImmigrationCase;
use App\Models\JobApplication;
use App\Models\Message;
use App\Models\Payment;
use App\Models\UserProfile;
use App\Support\AppointmentCalendar;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class PortalDashboardController extends Controller
{
    public function __invoke(): View
    {
        $user = auth()->user();
        $bookingStart = now()->startOfDay();
        $bookingEnd = now()->addMonth()->endOfMonth();
        Message::where('recipient_id', $user->id)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        $slots = DB::table('appointment_slots')
            ->where('status', 'available')
            ->whereBetween('starts_at', [$bookingStart, $bookingEnd])
            ->orderBy('starts_at')
            ->get();

        $profile = $user->profile()->firstOrCreate(
            ['user_id' => $user->id],
            [
                'account_type' => 'client',
                'type_client' => UserProfile::TYPE_PARTICULIER,
                'preferred_language' => app()->getLocale(),
            ],
        );

        $documentsCount = Document::where('user_id', $user->id)->count();
        $requestsCount = DB::table('lead_requests')->where('email', $user->email)->count();
        $appointmentsCount = DB::table('appointments')->where('user_id', $user->id)->count();
        $pendingPaymentsCount = DB::table('payments')
            ->where('user_id', $user->id)
            ->where('status', Payment::STATUS_PENDING)
            ->count();

        $journeySteps = collect([
            [
                'title' => 'Profil calibré',
                'done' => filled($profile->country) || filled($profile->organization_name),
                'text' => 'Complétez votre pays, organisation ou ville pour personnaliser l’accompagnement.',
            ],
            [
                'title' => 'Demande créée',
                'done' => $requestsCount > 0,
                'text' => 'Envoyez une demande claire avec objectif, délais et canal préféré.',
            ],
            [
                'title' => 'Pièces transmises',
                'done' => $documentsCount > 0,
                'text' => 'Ajoutez passeport, CV, diplômes ou preuves utiles pour accélérer l’analyse.',
            ],
            [
                'title' => 'Rendez-vous planifié',
                'done' => $appointmentsCount > 0,
                'text' => 'Choisissez un créneau disponible pour obtenir une feuille de route.',
            ],
            [
                'title' => 'Facturation suivie',
                'done' => $pendingPaymentsCount === 0,
                'text' => 'Consultez les frais ouverts et utilisez le lien de paiement si nécessaire.',
            ],
        ]);

        return view('portal.dashboard', [
            'user' => $user,
            'profile' => $profile,
            'clientTypes' => UserProfile::clientTypes(),
            'notifications' => $user->notifications()->latest()->limit(8)->get(),
            'documents' => Document::where('user_id', $user->id)->latest()->limit(8)->get(),
            'applications' => JobApplication::where(function ($query) use ($user): void {
                $query->where('user_id', $user->id)->orWhere('email', $user->email);
            })->with('jobPosting')->latest()->limit(8)->get(),
            'leads' => DB::table('lead_requests')->where('email', $user->email)->latest()->limit(8)->get(),
            'reviews' => DB::table('testimonials')->where('user_id', $user->id)->latest()->limit(5)->get(),
            'messages' => Message::where(function ($query) use ($user): void {
                $query->where('sender_id', $user->id)->orWhere('recipient_id', $user->id);
            })->with(['sender', 'recipient'])->latest()->limit(8)->get(),
            'immigrationCases' => ImmigrationCase::where('user_id', $user->id)
                ->with(['histories' => fn ($query) => $query->latest()->limit(3)])
                ->latest()
                ->limit(6)
                ->get(),
            'appointments' => Appointment::where('user_id', $user->id)->latest()->limit(6)->get(),
            'payments' => Payment::where('user_id', $user->id)->latest()->limit(6)->get(),
            'journeySteps' => $journeySteps,
            'appointmentSlotOptions' => $slots,
            'appointmentCalendars' => AppointmentCalendar::months($slots),
            'stats' => [
                'documents' => $documentsCount,
                'requests' => $requestsCount,
                'reviews' => DB::table('testimonials')->where('user_id', $user->id)->count(),
                'applications' => JobApplication::where('email', $user->email)->count(),
                'appointments' => $appointmentsCount,
                'payments' => DB::table('payments')->where('user_id', $user->id)->count(),
                'pendingPayments' => $pendingPaymentsCount,
                'immigrationCases' => DB::table('immigration_cases')->where('user_id', $user->id)->count(),
                'unreadMessages' => Message::where('recipient_id', $user->id)->whereNull('read_at')->count(),
                'unreadNotifications' => $user->unreadNotifications()->count(),
            ],
        ]);
    }
}
