<?php

namespace App\Http\Controllers;

use DataTables;
use Carbon\Carbon;
use App\Models\Staff;
use App\Models\Transection;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class StaffController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Staff::all();
            return Datatables::of($data)


                // ->addColumn('floor.floor_name', function ($row) {
                //     return $row->floor ? $row->floor->floor_name : 'N/A';
                // })
                // ->addColumn('room.room_name', function ($row) {
                //     return $row->room ? $row->room->room_name : 'N/A';
                // })
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $editUrl = route('staff.edit', $row->id);



                    // Edit button
                    $editBtn = '<a href="' . $editUrl . '">
                                    <svg class="filament-link-icon w-4 h-4 mr-1"
                                        xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                        fill="currentColor" aria-hidden="true">
                                        <path
                                            d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z">
                                        </path>
                                    </svg>
                                </a>';


                    $deleteBtn = '<a href="#" onclick="deleteStaff(' . $row->id . ')" class="text-danger w-4 h-4 mr-1">
                                    <svg class="filament-link-icon w-4 h-4 mr-1"
                                        xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                        fill="currentColor" aria-hidden="true">
                                        <path fill-rule="evenodd"
                                            d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z"
                                            clip-rule="evenodd"></path>
                                    </svg>
                                </a>';
                    $pay =  '<a href="" class="btn btn-sm btn-success pay_button" data-id="' . $row->id . '">Pay</a>';




                    $btn = $editBtn . $deleteBtn . $pay;

                    return $btn;
                })

                ->rawColumns(['images', 'action'])
                ->make(true);
        }
        return view('admin.staff.list');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.staff.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'father_name' => 'required',
            'cnic' => 'required|numeric',
            'address' => 'required',
            'phone_no' => 'required|numeric',
            'email' => 'required|email',
        ]);

        if ($validator->passes()) {
            $staff = new Staff();
            $staff->name = $request->name;
            $staff->father_name = $request->father_name;
            $staff->cnic = $request->cnic;
            $staff->address = $request->address;
            $staff->phone_no = $request->phone_no;
            $staff->email = $request->email;
            $staff->status = $request->status;

            $staff->save();
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $file) {
                    $staff->addMedia($file)
                        ->usingName('Spatie Media Library')
                        ->toMediaCollection('images');
                }
            }

            $request->session()->flash('success', 'Staff Register Successfully');
            return response()->json([
                'status' => true,
                'message' => 'Staff Register successfully'
            ]);
        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Staff $staff)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, $id)
    {
        $staff = Staff::find($id);
        return view('admin.staff.edit', get_defined_vars());
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'father_name' => 'required',
            'cnic' => 'required|numeric',
            'address' => 'required',
            'phone_no' => 'required|numeric',
            'email' => 'required|email',
        ]);

        if ($validator->passes()) {
            $staff = Staff::find($id);
            $staff->name = $request->name;
            $staff->father_name = $request->father_name;
            $staff->cnic = $request->cnic;
            $staff->address = $request->address;
            $staff->phone_no = $request->phone_no;
            $staff->email = $request->email;
            $staff->status = $request->status;

            $staff->save();
            if ($request->hasFile('images')) {
                $staff->clearMediaCollection('images');
                foreach ($request->file('images') as $file) {
                    $staff->addMedia($file)
                        ->usingName('Spatie Media Library')
                        ->toMediaCollection('images');
                }
            }

            $request->session()->flash('success', 'Staff Updated Successfully');
            return response()->json([
                'status' => true,
                'message' => 'Staff Updated successfully'
            ]);
        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, $staff_id)
    {
        $staff = Staff::find($staff_id);
        if (empty($staff)) {
            $request->session()->flash('success', 'Staff Not Found');
            return response()->json([
                'status' => false,
                'message' => 'Staff Not Found'
            ]);
        }

        $staff->delete();

        $request->session()->flash('success', 'Staff Deleted Successfully');
        return response()->json([
            'status' => true,
            'message' => 'Staff Deleted Successfully'
        ]);
    }









    public function get_staff(Request $request)
    {
        $id = $request->input('id');
        $staff = Staff::where("id", $id)->first();
        return response()->json(['staff' => $staff]);
    }


    public function pay_staff(Request $request)
    {
        $staff = Staff::find($request->id);

        if ($staff) {



            Transection::create([
                'transection_type_id' => $staff->id,
                'amount' => $request->paid_amount,
                'transection_date' => Carbon::now(),
                'description' => 'staff payment',
                'transection_type' => 'Staff',
                'status' => 'debit',
            ]);
            session()->flash('success', 'staff Paid Successfully');
            return response()->json(['status' => true, 'message' => 'staff Paid Successfully.']);
        }

        return response()->json(['status' => false, 'message' => 'staff not found.'], 404);
    }
}