<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\Floor;
use App\Models\Registration;
use Illuminate\Http\Request;
use DataTables;

class FeeController extends Controller
{
    public function create(Request $request)
    {
        $registrations = Registration::all();
        return view('admin.fee.create', get_defined_vars());
    }
    // public function index(Request $request)
    // {
    //     if ($request->ajax()) {
    //         $query = Registration::query()->with(['floor', 'room']);

    //         // Filter by floor_id if provided
    //         if ($request->filled('floor_id')) {
    //             $query->where('floor_id', $request->input('floor_id'));
    //         }

    //         // Filter by user name if provided
    //         if ($request->filled('user')) {
    //             $query->whereHas('name', function ($subquery) use ($request) {
    //                 $subquery->where('name', 'like', '%' . $request->input('user') . '%');
    //             });
    //         }

    //         // Filter by room name if provided
    //         if ($request->filled('serach_by_room')) {
    //             $query->whereHas('room', function ($subquery) use ($request) {
    //                 $subquery->where('room_name', 'like', '%' . $request->input('serach_by_room') . '%');
    //             });
    //         }

    //         $data = DataTables::of($query)
    //             ->addColumn('action', function ($row) {
    //                 // Add your action column HTML here
    //                 return '<a href="#" class="btn btn-sm btn-info">Edit</a>';
    //             })
    //             ->rawColumns(['action'])
    //             ->make(true);

    //         return $data;
    //     }

    //     $floors = Floor::all();

    //     return view('admin.fee.index', compact('floors'));
    // }



    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = Registration::query()->with(['floor', 'room']);

            // Filter by floor_id if provided
            if ($request->filled('floor_id')) {
                $query->where('floor_id', $request->input('floor_id'));
            }

            // Filter by user name if provided
            if ($request->filled('user')) {
                $query->where('name', 'like', '%' . $request->input('user') . '%');
            }

            // Filter by room name if provided
            if ($request->filled('serach_by_room')) {
                $query->whereHas('room', function ($subquery) use ($request) {
                    $subquery->where('room_name', 'like', '%' . $request->input('serach_by_room') . '%');
                });
            }

            $data = DataTables::of($query)
                ->addColumn('action', function ($row) {
                    // Add your action column HTML here
                    return '<a href="#" class="btn btn-sm btn-info">Edit</a>';
                })
                ->rawColumns(['action'])
                ->make(true);

            return $data;
        }

        $floors = Floor::all();

        return view('admin.fee.index', compact('floors'));
    }
}