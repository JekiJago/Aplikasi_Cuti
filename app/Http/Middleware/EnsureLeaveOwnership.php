<?php

namespace App\Http\Middleware;

use App\Models\LeaveRequest;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureLeaveOwnership
{
    public function handle(Request $request, Closure $next): Response
    {
        $leaveId = $request->route('leave_request') ?? $request->route('id');

        if ($leaveId) {
            $leave = LeaveRequest::findOrFail($leaveId);

            if ($leave->user_id !== $request->user()->id && !$request->user()->isAdmin()) {
                abort(403, 'You do not own this leave request.');
            }
        }

        return $next($request);
    }
}
