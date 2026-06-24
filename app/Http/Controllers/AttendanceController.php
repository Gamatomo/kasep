<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AttendanceController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        // dd(now());
        
        if ($user->level == 1) {
            // Admin sees all
            $attendances = Attendance::with('user')->orderBy('date', 'desc')->get();
        } else {
            // Kasir sees their own
            $attendances = Attendance::where('user_id', $user->id)->orderBy('date', 'desc')->get();
        }

        return view('attendance.index', compact('attendances'));
    }

    public function clockOut(Request $request, $id)
    {
        $request->validate([
            'earnings' => 'nullable|numeric',
        ]);

        $attendance = Attendance::findOrFail($id);
        
        if ($attendance->user_id != Auth::id() && Auth::user()->level != 1) {
            abort(403);
        }

        $clock_out = date('H:i:s');
        $attendance->clock_out = $clock_out;
        $attendance->earnings = $request->earnings;

        $expected_revenue = \App\Models\Penjualan::where('id_user', $attendance->user_id)
            ->whereDate('created_at', $attendance->date)
            ->whereTime('created_at', '>=', $attendance->clock_in)
            ->whereTime('created_at', '<=', $clock_out)
            ->sum('bayar');

        $attendance->system_revenue = $expected_revenue;
        $attendance->save();

        return redirect()->route('attendance.index')->with('success', 'Clock out successful!');
    }
}
