<?php

namespace App\Http\Controllers;

use App\Models\LeaveRequest;
use Illuminate\Http\Request;

class AdminLeaveController extends Controller
{
    public function index()
    {
        $query = LeaveRequest::with('user')->latest();

        if (request('status')) {
            $query->where('status', request('status'));
        }

        if (request('department')) {
            $query->whereHas('user', function ($q) {
                $q->where('department', request('department'));
            });
        }

        $leaves = $query->paginate(15);

        return view('admin.leaves.index', compact('leaves'));
    }

    public function show($id)
    {
        $leave = LeaveRequest::with(['user', 'approver'])->findOrFail($id);

        return view('admin.leaves.show', compact('leave'));
    }

    public function approve(Request $request, $id)
    {
        $leave = LeaveRequest::findOrFail($id);

        // $this->authorize('approve', $leave);

        $request->validate([
            'admin_notes' => ['nullable', 'string', 'max:2000'],
        ]);

        $leave->approve(auth()->id(), $request->input('admin_notes'));

        // TODO: kirim notifikasi ke karyawan

        return redirect()->route('admin.leaves.show', $leave->id)
            ->with('success', 'Pengajuan cuti disetujui.');
    }

    public function reject(Request $request, $id)
    {
        $leave = LeaveRequest::findOrFail($id);

        $this->authorize('approve', $leave);

        $request->validate([
            'admin_notes' => ['required', 'string', 'max:2000'],
        ]);

        $leave->reject(auth()->id(), $request->input('admin_notes'));

        // TODO: kirim notifikasi ke karyawan

        return redirect()->route('admin.leaves.show', $leave->id)
            ->with('success', 'Pengajuan cuti ditolak.');
    }

    public function statistics()
    {
        $stats = [
            'pending'  => LeaveRequest::where('status', 'pending')->count(),
            'approved' => LeaveRequest::where('status', 'approved')->count(),
            'rejected' => LeaveRequest::where('status', 'rejected')->count(),
            'byType'   => LeaveRequest::selectRaw('leave_type, COUNT(*) as total')
                            ->groupBy('leave_type')
                            ->pluck('total', 'leave_type'),
            'byDept'   => LeaveRequest::selectRaw('users.department, COUNT(*) as total')
                            ->join('users', 'leave_requests.user_id', '=', 'users.id')
                            ->groupBy('users.department')
                            ->pluck('total', 'users.department'),
        ];

        return view('admin.statistics', compact('stats'));
    }
}
