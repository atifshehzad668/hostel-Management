<?php

namespace App\Http\Controllers;

use DataTables;
use App\Models\Room;
use App\Models\Floor;
use App\Models\Registration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RegistrationController extends Controller
{
    public function create()
    {
        $floors = Floor::all();
        return view('admin.registration.create', get_defined_vars());
    }


    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'father_name' => 'required',
            'cnic' => 'required|numeric',
            'floor_id' => 'required',
            'room_id' => 'required',
            'address' => 'required',
            'registration_date' => 'required',
            'phone_no' => 'required|numeric',
            'whatsapp_no' => 'required|numeric',
            'dob' => 'required',
            'email' => 'required|email',
        ]);

        if ($validator->passes()) {
            $registration = new Registration();
            $registration->name = $request->name;
            $registration->father_name = $request->father_name;
            $registration->cnic = $request->cnic;
            $registration->floor_id = $request->floor_id;
            $registration->room_id = $request->room_id;
            $registration->address = $request->address;
            $registration->amount = $request->amount;
            $registration->registration_date = $request->registration_date;
            $registration->phone_no = $request->phone_no;
            $registration->whatsapp_no = $request->whatsapp_no;
            $registration->dob = $request->dob;
            $registration->email = $request->email;
            $registration->status = $request->status;

            $registration->save();
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $file) {
                    $registration->addMedia($file)
                        ->usingName('Spatie Media Library')
                        ->toMediaCollection('images');
                }
            }

            $request->session()->flash('success', 'Student Register Successfully');
            return response()->json([
                'status' => true,
                'message' => 'Student Register successfully'
            ]);
        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }


    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Registration::query()->with(['media', 'floor', 'room']);
            return Datatables::of($data)


                // ->addColumn('floor.floor_name', function ($row) {
                //     return $row->floor ? $row->floor->floor_name : 'N/A';
                // })
                // ->addColumn('room.room_name', function ($row) {
                //     return $row->room ? $row->room->room_name : 'N/A';
                // })
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $editUrl = route('registration.edit', $row->id);
                    $viewUrl = route('registration.view', $row->id);
                    // $feeUrl = route('fee.generate', ['user_id' => $row->id]);

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


                    $deleteBtn = '<a href="#" onclick="deleteRegistration(' . $row->id . ')" class="text-danger w-4 h-4 mr-1">
                                    <svg class="filament-link-icon w-4 h-4 mr-1"
                                        xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                        fill="currentColor" aria-hidden="true">
                                        <path fill-rule="evenodd"
                                            d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z"
                                            clip-rule="evenodd"></path>
                                    </svg>
                                </a>';


                    $viewBtn = '<a href="' . $viewUrl . '">
                                <svg class="filament-link-icon w-4 h-4 mr-1"
                                    xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                    fill="currentColor" aria-hidden="true">
                                    <path fill-rule="evenodd"
                                        d="M10 2a8 8 0 100 16 8 8 0 000-16zM9 5a1 1 0 00-1 1v4a1 1 0 002 0V6a1 1 0 00-1-1zm1 8a1 1 0 11-2 0 1 1 0 012 0zm1-4a1 1 0 000-2H8a1 1 0 000 2h3z"
                                        clip-rule="evenodd"></path>
                                </svg>
                            </a>';


                    $btn = $editBtn . $deleteBtn . $viewBtn;

                    return $btn;
                })

                ->rawColumns(['images', 'action'])
                ->make(true);
        }
        return view('admin.registration.list');
    }

    public function edit(Request $request, $id)
    {
        $registration = Registration::find($id);
        $floors = Floor::all();
        $rooms = Room::where('floor_id', $registration->floor_id)->get();
        return view('admin.registration.edit', get_defined_vars());
    }
    public function update(Request $request, $id)
    {
        $registration = Registration::find($id);
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'father_name' => 'required',
            'cnic' => 'required|numeric',
            'floor_id' => 'required',
            'room_id' => 'required',
            'address' => 'required',
            'registration_date' => 'required',
            'phone_no' => 'required|numeric',
            'whatsapp_no' => 'required|numeric',
            'dob' => 'required',
            'email' => 'required|email',
        ]);

        if ($validator->passes()) {

            $registration->name = $request->name;
            $registration->father_name = $request->father_name;
            $registration->cnic = $request->cnic;
            $registration->floor_id = $request->floor_id;
            $registration->room_id = $request->room_id;
            $registration->amount = $request->amount;
            $registration->address = $request->address;
            $registration->registration_date = $request->registration_date;
            $registration->phone_no = $request->phone_no;
            $registration->whatsapp_no = $request->whatsapp_no;
            $registration->dob = $request->dob;
            $registration->email = $request->email;
            $registration->status = $request->status;

            $registration->save();
            if ($request->hasFile('images')) {
                $registration->clearMediaCollection('images');
                foreach ($request->file('images') as $file) {
                    $registration->addMedia($file)
                        ->usingName('Spatie Media Library')
                        ->toMediaCollection('images');
                }
            }

            $request->session()->flash('success', 'Student Udpdated Successfully');
            return response()->json([
                'status' => true,
                'message' => 'Student Udpdated successfully'
            ]);
        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }




    public function destroy(Request $request, $registration_id)
    {
        $registration = Registration::find($registration_id);
        if (empty($registration)) {
            $request->session()->flash('success', 'registration Not Found');
            return response()->json([
                'status' => false,
                'message' => 'Floor Not Found'
            ]);
        }

        $registration->delete();

        $request->session()->flash('success', 'Registration Deleted Successfully');
        return response()->json([
            'status' => true,
            'message' => 'Registration Deleted Successfully'
        ]);
    }


    public function view($id)
    {
        $registration = Registration::find($id);
        return view('admin.registration.view', get_defined_vars());
    }



    public function filter(Request $request)
    {
        $rooms = Room::where(['floor_id' => $request->floor])->get();

        return response()->json(['rooms' => $rooms]);
    }
}