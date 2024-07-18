<?php

namespace App\Http\Controllers;

use DataTables;
use Carbon\Carbon;
use App\Models\Fee;
use App\Models\Room;
use App\Models\Floor;
use App\Models\Registration;
use App\Models\Transection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class FeeController extends Controller
{

    public function create()
    {
        return view('admin.fee.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'date' => 'required|date'
        ]);

        $date = Carbon::parse($request->date);
        $month = $date->month;
        $year = $date->year;
        $startOfMonth = $date->startOfMonth();

        $existingFees = Fee::whereYear('fee_date', $year)
            ->whereMonth('fee_date', $month)
            ->exists();

        if ($existingFees) {
            $errorMessage = "Fees for the selected month and year ({$month}/{$year}) have already been generated.";
            session()->flash('error', $errorMessage);

            return response()->json([
                'status' => false,
                'message' => $errorMessage
            ]);
        }

        $registrations = Registration::all();


        foreach ($registrations as $registration) {
            Fee::create([
                'registration_id' => $registration->id,
                'fee_date' => $startOfMonth,
                'amount' => $registration->amount,
                'paid_amount' => 0,
                'status' => 'Unpaid',
                'deleted_at' => null,
            ]);
        }

        $successMessage = "Fees generated successfully for the selected month and year ({$month}/{$year}).";
        session()->flash('success', $successMessage);

        return response()->json([
            'status' => true,
            'message' => $successMessage
        ]);
    }



    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = Fee::with('registration');

            if ($request->has('name') && !empty($request->name)) {
                $query->whereHas('registration', function ($q) use ($request) {
                    $q->where('name', 'like', '%' . $request->name . '%');
                });
            }

            if ($request->has('start_date') && $request->has('end_date') && !empty($request->start_date) && !empty($request->end_date)) {
                $start_date = Carbon::parse($request->start_date)->startOfDay();
                $end_date = Carbon::parse($request->end_date)->endOfDay();
                $query->whereBetween('fee_date', [$start_date, $end_date]);
            }

            return Datatables::of($query)
                ->addIndexColumn()
                ->addColumn('registration_name', function ($row) {
                    return $row->registration->name;
                })
                ->addColumn('action', function ($row) {

                    return '<a href="" class="btn btn-sm btn-success pay_button" data-id="' . $row->id . '">Pay</a>';
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('admin.fee.create');
    }

    public function get_fee(Request $request)
    {
        $id = $request->input('id');
        $fee = Fee::where("id", $id)->first();
        return response()->json(['fee' => $fee]);
    }

    public function pay_fee(Request $request)
    {
        $fee = Fee::find($request->id);

        if ($fee) {
            $fee->paid_amount = $request->paid_amount;
            $fee->registration_id = $request->registration_id;
            $fee->fee_date = $request->fee_date;
            $fee->amount = $request->amount;

            if ($request->paid_amount < $request->amount) {
                $fee->status = 'Partially-Paid';
            } elseif ($request->paid_amount == $request->amount) {
                $fee->status = 'Paid';
            }

            $fee->save();
            Transection::create([
                'transection_type_id' => $fee->id,
                'amount' => $request->paid_amount,
                'transection_date' => Carbon::now(),
                'description' => 'Fee payment',
                'transection_type' => 'Fees',
                'status' => 'credit',
            ]);
            session()->flash('success', 'Fee Paid Successfully');
            return response()->json(['status' => true, 'message' => 'Fee Paid Successfully.']);
        }

        return response()->json(['status' => false, 'message' => 'Fee not found.'], 404);
    }
}