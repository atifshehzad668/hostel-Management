<?php

namespace App\Http\Controllers;

use DataTables;
use App\Models\Expense;
use App\Models\ExpenseHead;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ExpenseHeadController extends Controller
{
    public function create()
    {
        return view('admin.expense_head.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'expense_name' => 'required',
            'description' => 'required',

        ]);

        if ($validator->passes()) {
            $expense_head = new ExpenseHead();
            $expense_head->name = $request->expense_name;
            $expense_head->description = $request->description;

            $expense_head->save();

            $request->session()->flash('success', 'Expense Head Added Successfully');
            return response()->json([
                'status' => true,
                'message' => 'Expense Head added successfully'
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
            $data = ExpenseHead::select('*');
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $editUrl = route('expense_head.edit', $row->id);
                    $deleteUrl = route('expense_head.destroy', $row->id);
                    $relatedExpensesCount = Expense::where('expense_head_id', $row->id)->count();


                    $btn = '<a href="' . $editUrl . '" class="edit btn btn-primary btn-sm mr-2">Edit</a>';
                    $btn .= '<a href="javascript:void(0);" onclick="delete_expense_head(' . $row->id . ', ' . $relatedExpensesCount . ')" class="delete btn btn-danger btn-sm">Delete</a>';


                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('admin.expense_head.list');
    }
    public function trashindex(Request $request)
    {
        if ($request->ajax()) {
            $data = ExpenseHead::onlyTrashed()->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $restoreUrl = route('expense_head.restore', $row->id);
                    $deleteUrl = route('expense_head.permanentdelete', $row->id);


                    $btn = '<a href="javascript:void(0);" onclick="delete_permanentDelete(' . $row->id . ')" class="delete btn btn-danger btn-sm">Delete</a>';
                    $btn .= '<a href="javascript:void(0);" onclick="restore_expense_head(' . $row->id . ')" class="delete btn btn-success btn-sm">Restore</a>';

                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('admin.expense.trash-list');
    }



    public function destroy(Request $request, $expense_head_id)
    {
        $expense_head = ExpenseHead::find($expense_head_id);
        if (empty($expense_head)) {
            $request->session()->flash('error', 'Expense Head Not Found');
            return response()->json([
                'status' => false,
                'message' => 'Expense Head Not Found'
            ]);
        }


        $relatedExpensesCount = Expense::where('expense_head_id', $expense_head_id)->count();

        if ($relatedExpensesCount > 0) {
            $request->session()->flash('error', 'Expense Head is used in other records and cannot be deleted');
            return response()->json([
                'status' => false,
                'message' => 'Expense Head is used in other records and cannot be deleted',
                'related_expenses_count' => $relatedExpensesCount
            ]);
        }

        $expense_head->delete();

        $request->session()->flash('success', 'Expense Head Deleted Successfully');
        return response()->json([
            'status' => true,
            'message' => 'Expense Head Deleted Successfully'
        ]);
    }


    public function edit(Request $request, $id)
    {
        $expenseHead = ExpenseHead::find($id);
        return view('admin.expense_head.edit', get_defined_vars());
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'expense_name' => 'required',
            'description' => 'required',

        ]);

        if ($validator->passes()) {
            $expense_head = ExpenseHead::find($id);
            $expense_head->name = $request->expense_name;
            $expense_head->description = $request->description;

            $expense_head->save();

            $request->session()->flash('success', 'Expense Head Updated Successfully');
            return response()->json([
                'status' => true,
                'message' => 'Expense Head Updated successfully'
            ]);
        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }


    // public function permanentDelete(Request $request, $expense_head_id)
    // {
    //     $expense_head = ExpenseHead::find($expense_head_id);
    //     if (empty($expense_head)) {
    //         $request->session()->flash('success', 'Expense Not Found');
    //         return response()->json([
    //             'status' => false,
    //             'message' => 'expense Not Found'
    //         ]);
    //     }


    //     $expense_head->forceDelete();

    //     $request->session()->flash('success', 'Expense Head Permanent Deleted Successfully');
    //     return response()->json([
    //         'status' => true,
    //         'message' => 'Expense Head Permanent Deleted Successfully'
    //     ]);
    // }
    // public function restore(Request $request, $expense_head_id)
    // {
    //     $expense_head = ExpenseHead::withTrashed()->find($expense_head_id);
    //     if (empty($expense_head_id)) {
    //         $request->session()->flash('success', 'Expense Head Not Found');
    //         return response()->json([
    //             'status' => false,
    //             'message' => 'Expense Head Not Found'
    //         ]);
    //     }

    //     $expense_head->restore();



    //     $request->session()->flash('success', 'Expense Head Restored Successfully');
    //     return response()->json([
    //         'status' => true,
    //         'message' => 'Expense Head Restored Successfully'
    //     ]);
    // }
}