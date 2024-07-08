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
            $request->session()->flash('error', "{$userName}'s fees for {$monthName} {$year} have already been Issue.");

            return response()->json([
                'status' => false,
                'message' => "{$userName}'s fees for {$monthName} {$year} have already been issue."
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
                    $editUrl = route('user_fee.edit', $row->id);
                    $feeUrl = route('fee.generate', $row->id);
                    $editBtn = '<a href="' . $editUrl . '">
                    <svg class="filament-link-icon w-4 h-4 mr-1"
                        xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                        fill="currentColor" aria-hidden="true">
                        <path
                            d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z">
                        </path>
                    </svg>
                </a>';


                    //     $deleteBtn = '<a href="#" onclick="deleteFee(' . $row->id . ')" class="text-danger w-4 h-4 mr-1">
                    //     <svg class="filament-link-icon w-4 h-4 mr-1"
                    //         xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                    //         fill="currentColor" aria-hidden="true">
                    //         <path fill-rule="evenodd"
                    //             d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z"
                    //             clip-rule="evenodd"></path>
                    //     </svg>
                    // </a>';

                    $registration_id = '<a href="' . $feeUrl . '" class="received_payment btn btn-success" value="pay" data-id="' . $row->id . '">' . "Pay" . '</a>';




                    $btn = $editBtn  . $registration_id;

                    return $btn;
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
            $query = Fee::with(['registration' => function ($q) {
                $q->with(['floor', 'room']);
            }]);

            if ($request->filled('floor_id')) {
                $query->whereHas('registration', function ($q) use ($request) {
                    $q->where('floor_id', $request->input('floor_id'));
                });
            }

            if ($request->filled('search_by_room')) {
                $query->whereHas('registration.room', function ($q) use ($request) {
                    $q->where('room_name', 'like', '%' . $request->input('search_by_room') . '%');
                });
            }

            if ($request->filled('user')) {
                $query->whereHas('registration', function ($q) use ($request) {
                    $q->where('name', 'like', '%' . $request->input('user') . '%');
                });
            }

            return DataTables::of($query)
                ->addColumn('action', function ($row) {
                    $editUrl = route('user_fee.edit', $row->id);

                    $editBtn = '<a href="' . $editUrl . '">
                                    <svg class="filament-link-icon w-4 h-4 mr-1"
                                        xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                        fill="currentColor" aria-hidden="true">
                                        <path
                                            d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z">
                                        </path>
                                    </svg>
                                </a>';

                    $deleteBtn = '<a href="#" onclick="deleteFee(' . $row->id . ')" class="text-danger w-4 h-4 mr-1">
                                    <svg class="filament-link-icon w-4 h-4 mr-1"
                                        xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                        fill="currentColor" aria-hidden="true">
                                        <path fill-rule="evenodd"
                                            d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z"
                                            clip-rule="evenodd"></path>
                                    </svg>
                                </a>';

                    $btn = $editBtn . $deleteBtn;

                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        $floors = Floor::all();
        return view('admin.fee.studentfee', compact('floors'));
    }


    public function user_fee_edit(Request $request, $id)
    {
        $fee = Fee::with('registration')->find($id);

        return view('admin.fee.edit', get_defined_vars());
    }
    public function user_fee_update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [

            'fee_date' => 'required|date',
            'amount' => 'required|numeric',

            'status' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }

        // Retrieve user details


        // $feeDate = Carbon::parse($request->fee_date);
        // $monthName = $feeDate->format('F');
        // $year = $feeDate->format('Y');

        // $existingFee = Fee::where('registration_id', $request->user_id)
        //     ->whereMonth('fee_date', $feeDate->month)
        //     ->whereYear('fee_date', $year)
        //     ->first();

        // if ($existingFee) {
        //     $request->session()->flash('error', "{$userName}'s fees for {$monthName} {$year} have already been submitted.");

        //     return response()->json([
        //         'status' => false,
        //         'message' => "{$userName}'s fees for {$monthName} {$year} have already been submitted."
        //     ]);
        // }

        $fee = Fee::find($id);
        $fee->registration_id = $request->user_id;
        $fee->fee_date = $request->fee_date;
        $fee->amount = $request->amount;
        $fee->paid_amount = $request->paid_amount;

        $fee->status = $request->status;
        $fee->save();

        $request->session()->flash('success', " Fees Updated successfully.");
        return response()->json([
            'status' => true,
            'message' => " Fees Updated successfully."
        ]);
    }



    public function user_fee_destroy(Request $request, $fee_id)
    {
        $fee = Fee::find($fee_id);
        if (empty($fee)) {
            $request->session()->flash('success', 'Fee Not Found');
            return response()->json([
                'status' => false,
                'message' => 'Fee Not Found'
            ]);
        }

        $fee->delete();

        $request->session()->flash('success', 'Fee Deleted Successfully');
        return response()->json([
            'status' => true,
            'message' => 'Fee Deleted Successfully'
        ]);
    }
}