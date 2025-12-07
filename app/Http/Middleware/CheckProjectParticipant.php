<?php

namespace App\Http\Middleware;

use App\Services\CheckParticipant;
use App\Services\GetProjectUrl;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckProjectParticipant
{
    /**
     * Handle an incoming request.
     *
     * @param \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response) $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user_id = Auth::id();
        $project_url = $request->project;

        if (CheckParticipant::project($project_url, $user_id)) {
            return $next($request);
        } else {
            return redirect('/cabinet');
        }
    }
}
