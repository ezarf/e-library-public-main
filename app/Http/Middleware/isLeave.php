<?php

namespace App\Http\Middleware;

use App\Models\Leave;
use Closure;
use Filament\Notifications\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class isLeave
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();
        $today = now()->toDateString();

        $hasLeave = Leave::where('user_id', $user->id)
                ->where('start_date', '<=', $today)
                ->where('end_date', '>=', $today)
                ->whereIn('status', ['approved', 'pending'])
                ->exists();
        
        if ($hasLeave) {
            Notification::make()
            ->danger()
            ->title('Presensi ditolak!')
            ->body('Anda sedang cuti atau sedang mengajukan cuti.')
            ->send();

            return redirect('dashboard/attendances');
        }

        return $next($request);
    }
}
