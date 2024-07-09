<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\Floor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use DataTables;

class RoomController extends Controller
{
    public function create()
    {
        $floors = Floor::all();
        return view('admin.rooms.create', get_defined_vars());
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'room_name' => 'required',
            'floor_id' => 'required',
            'room_type' => 'required',
            'no_of_seats' => 'required',
            'status' => 'required|in:Active,Inactive',
        ]);

        if ($validator->passes()) {
            $room = new Room();

            $room->room_name = $request->room_name;
            $room->floor_id = $request->floor_id;
            $room->room_type = $request->room_type;
            $room->no_of_seats = $request->no_of_seats;
            $room->status = $request->status;

            $room->save();

            $request->session()->flash('success', 'Room Added Successfully');
            return response()->json([
                'status' => true,
                'message' => 'Room Added successfully'
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
            $data = Room::query()->with('floor');
            return Datatables::of($data)
                ->addColumn('DT_RowIndex', function ($row) {
                    // This will add a sequential index starting from 1
                    static $index = 1;
                    return $index++;
                })
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $editUrl = route('rooms.edit', $row->id);
                    $deleteUrl = route('rooms.destroy', $row->id);

                    $btn = '<a href="' . $editUrl . '" class="edit btn btn-primary btn-sm mr-2">Edit</a>';
                    $btn .= '<a href="javascript:void(0);" onclick="deleteroom(' . $row->id . ')" class="delete btn btn-danger btn-sm">Delete</a>';

                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('admin.rooms.list');
    }


    public function edit(Request $request, $id)
    {
        $room = Room::find($id);
        $floors = Floor::all();
        if (empty($room)) {
            return redirect()->route('room.index');
        }
        return view('admin.rooms.edit', get_defined_vars());
    }
    public function update(Request $request, $id)
    {
        $room = Room::find($id);

        $validator = Validator::make($request->all(), [
            'room_name' => 'required',
            'floor_id' => 'required',
            'room_type' => 'required',
            'no_of_seats' => 'required',
            'status' => 'required|in:Active,Inactive',
        ]);

        if ($validator->passes()) {

            $room->room_name = $request->room_name;
            $room->floor_id = $request->floor_id;
            $room->room_type = $request->room_type;
            $room->no_of_seats = $request->no_of_seats;
            $room->status = $request->status;

            $room->save();

            $request->session()->flash('success', 'Room Updated Successfully');
            return response()->json([
                'status' => true,
                'message' => 'Room Updated successfully'
            ]);
        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }


    public function destroy(Request $request, $id)
    {
        $room = Room::find($id);
        if (!$room) {
            $request->session()->flash('error', 'Room Not Found');
            return response()->json([
                'status' => false,
                'message' => 'Room Not Found'
            ]);
        }

        $room->delete();

        $request->session()->flash('success', 'Room Deleted Successfully');
        return response()->json([
            'status' => true,
            'message' => 'Room Deleted Successfully'
        ]);
    }
}