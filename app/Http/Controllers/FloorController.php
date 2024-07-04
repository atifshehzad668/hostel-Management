<?php

namespace App\Http\Controllers;

use App\Models\Floor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use DataTables;

class FloorController extends Controller
{
    public function create()
    {
        return view('admin.floor.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'floor_name' => 'required',
            'description' => 'required',
            'total_rooms' => 'required',
        ]);

        if ($validator->passes()) {
            $floor = new Floor();
            $floor->floor_name = $request->floor_name;
            $floor->description = $request->description;
            $floor->total_rooms = $request->total_rooms;
            $floor->save();

            $request->session()->flash('success', 'Floor Added Successfully');
            return response()->json([
                'status' => true,
                'message' => 'Floor added successfully'
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
            $data = Floor::select('*'); // Select only floor records
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $editUrl = route('floor.edit', $row->id);
                    $deleteUrl = route('floor.destroy', $row->id);

                    $btn = '<a href="' . $editUrl . '" class="edit btn btn-primary btn-sm mr-2">Edit</a>';
                    $btn .= '<a href="javascript:void(0);" onclick="deletefloor(' . $row->id . ')" class="delete btn btn-danger btn-sm">Delete</a>';

                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('admin.floor.list');
    }

    public function destroy(Request $request, $floor_id)
    {
        $floor = Floor::find($floor_id);
        if (empty($floor)) {
            $request->session()->flash('success', 'Floor Not Found');
            return response()->json([
                'status' => false,
                'message' => 'Floor Not Found'
            ]);
        }

        $floor->delete();

        $request->session()->flash('success', 'Floor Deleted Successfully');
        return response()->json([
            'status' => true,
            'message' => 'Floor Deleted Successfully'
        ]);
    }


    public function edit(Request $request, $id)
    {
        $floor = Floor::find($id);
        if (empty($floor)) {
            return redirect()->route('floor.index');
        }
        return view('admin.floor.edit', get_defined_vars());
    }

    public function update(Request $request, $id)
    {
        $floor = Floor::find($id);
        $validator = Validator::make($request->all(), [
            'floor_name' => 'required',
            'description' => 'required',
            'total_rooms' => 'required',
        ]);

        if ($validator->passes()) {

            $floor->floor_name = $request->floor_name;
            $floor->description = $request->description;
            $floor->total_rooms = $request->total_rooms;
            $floor->save();

            $request->session()->flash('success', 'Floor Updated Successfully');
            return response()->json([
                'status' => true,
                'message' => 'Floor Updated successfully'
            ]);
        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }
}
