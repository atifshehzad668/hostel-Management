<?php

namespace App\Http\Controllers;

use DataTables;
use Carbon\Carbon;
use App\Models\Fee;
use App\Models\Room;
use App\Models\Floor;
use App\Models\Registration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class FeeController extends Controller
{
    public function generate_fee(Request $request, $id)
    {
        $registration = Registration::find($id);
        return view('admin.fee.create', compact('registration'));
    }

    public function store_fee(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:registrations,id',
            'fee_date' => 'required|date',
            'amount' => 'required|numeric',
            'paid_amount' => 'required|numeric',
            'status' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }

        // Retrieve user details
        $user = Registration::findOrFail($request->user_id);
        $userName = $user->name;

        $feeDate = Carbon::parse($request->fee_date);
        $monthName = $feeDate->format('F');
        $year = $feeDate->format('Y');

        $existingFee = Fee::where('registration_id', $request->user_id)
            ->whereMonth('fee_date', $feeDate->month)
            ->whereYear('fee_date', $year)
            ->first();

        if ($existingFee) {
            $request->session()->flash('error', "{$userName}'s fees for {$monthName} {$year} have already been submitted.");

            return response()->json([
                'status' => false,
                'message' => "{$userName}'s fees for {$monthName} {$year} have already been submitted."
            ]);
        }

        $fee = new Fee();
        $fee->registration_id = $request->user_id;
        $fee->fee_date = $request->fee_date;
        $fee->amount = $request->amount;
        $fee->paid_amount = $request->paid_amount;
        $fee->status = $request->status;
        $fee->save();

        $request->session()->flash('success', "{$userName}'s {$monthName} {$year} fees submitted successfully.");
        return response()->json([
            'status' => true,
            'message' => "{$userName}'s {$monthName} {$year} fees submitted successfully."
        ]);
    }


    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = Registration::with(['floor', 'room']);

            if ($request->filled('floor_id')) {
                $query->where('floor_id', $request->input('floor_id'));
            }

            if ($request->filled('search_by_room')) {
                $query->whereHas('room', function ($subquery) use ($request) {
                    $subquery->where('room_name', 'like', '%' . $request->input('search_by_room') . '%');
                });
            }

            if ($request->filled('user')) {
                $query->where('name', 'like', '%' . $request->input('user') . '%');
            }

            return DataTables::of($query)
                ->addColumn('action', function ($row) {
                    $url = route('fee.generate', $row->id);
                    return '<a href="' . $url . '" class="btn btn-sm btn-info">Fee</a>';
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        $floors = Floor::all();

        return view('admin.fee.index', compact('floors'));
    }


    public function user_fee_list(Request $request)
    {
        if ($request->ajax()) {
            $query = Fee::with(['registration']);

            return DataTables::of($query)
                ->addColumn('action', function ($row) {
                    // Add your action button here if needed
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('admin.fee.studentfee');
    }
}