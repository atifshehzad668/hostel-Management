<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\ExpenseHead;
use App\Models\Transection;
use Illuminate\Http\Request;
use DataTables;
use Illuminate\Support\Facades\Validator;

class ExpenseController extends Controller
{
    public function create()
    {
        $expenseHeads = ExpenseHead::all();
        return view('admin.expense.create', get_defined_vars());
    }
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'expense_head_id' => 'required',
            'amount' => 'required|numeric',
            'date' => 'required|date',
            'description' => 'required',
        ]);

        if ($validator->passes()) {
            // Create a new expense
            $expense = new Expense();
            $expense->expense_head_id = $request->expense_head_id;
            $expense->amount = $request->amount;
            $expense->paying_date = $request->date;
            $expense->description = $request->description;

            $expense->save();

            // Create a corresponding transection
            $transection = new Transection();
            $transection->transection_type_id = $expense->id; // Reference to expense_id
            $transection->amount = $expense->amount;
            $transection->transection_date = $expense->paying_date;
            $transection->description = $expense->description;
            $transection->transection_type = 'Expense';
            $transection->status = 'debit';
            $transection->save();

            $request->session()->flash('success', 'Expense  Added Successfully');
            return response()->json([
                'status' => true,
                'message' => 'Expense  added successfully'
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
            $data = Expense::with('expense_head')->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $editUrl = route('expense.edit', $row->id);
                    $deleteUrl = route('expense.destroy', $row->id);

                    $btn = '<a href="' . $editUrl . '" class="edit btn btn-primary btn-sm mr-2">Edit</a>';
                    $btn .= '<a href="javascript:void(0);" onclick="delete_expense(' . $row->id . ')" class="delete btn btn-danger btn-sm">Delete</a>';

                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('admin.expense.list');
    }
    // public function trashindex(Request $request)
    // {
    //     if ($request->ajax()) {
    //         $data = Expense::onlyTrashed()->with('expense_head')->get();
    //         return Datatables::of($data)
    //             ->addIndexColumn()
    //             ->addColumn('action', function ($row) {
    //                 $restoreUrl = route('expense.restore', $row->id);
    //                 $deleteUrl = route('expense.permanentdelete', $row->id);


    //                 $btn = '<a href="javascript:void(0);" onclick="delete_permanentDelete(' . $row->id . ')" class="delete btn btn-danger btn-sm">Delete</a>';
    //                 $btn .= '<a href="javascript:void(0);" onclick="restore_expense(' . $row->id . ')" class="delete btn btn-success btn-sm">Restore</a>';

    //                 return $btn;
    //             })
    //             ->rawColumns(['action'])
    //             ->make(true);
    //     }

    //     return view('admin.expense.trash-list');
    // }





    public function edit(Request $request, $id)
    {
        $expense = Expense::find($id);
        $expenseHeads = ExpenseHead::all();
        return view('admin.expense.edit', get_defined_vars());
    }


    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'expense_head_id' => 'required',
            'amount' => 'required|numeric',
            'date' => 'required|date',
            'description' => 'required',
        ]);

        if ($validator->passes()) {

            $expense = Expense::find($id);
            $expense->expense_head_id = $request->expense_head_id;
            $expense->amount = $request->amount;
            $expense->paying_date = $request->date;
            $expense->description = $request->description;
            $expense->save();


            $transection = Transection::where('transection_type_id', $expense->id)->first();

            $transection->amount = $expense->amount;
            $transection->transection_date = $expense->paying_date;
            $transection->description = $expense->description;
            $transection->status = 'debit';
            $transection->save();

            $request->session()->flash('success', 'Expense updated Successfully');
            return response()->json([
                'status' => true,
                'message' => 'Expense updated successfully'
            ]);
        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }


    public function destroy(Request $request, $expense_id)
    {
        $expense = Expense::find($expense_id);
        if (empty($expense)) {
            $request->session()->flash('success', 'Expense Not Found');
            return response()->json([
                'status' => false,
                'message' => 'expense Not Found'
            ]);
        }
        $transection = Transection::where('transection_type_id', $expense_id)->first();
        if ($transection) {
            $transection->delete();
        }

        $expense->delete();

        $request->session()->flash('success', 'Expense Deleted Successfully');
        return response()->json([
            'status' => true,
            'message' => 'Expense Deleted Successfully'
        ]);
    }
    // public function permanentDelete(Request $request, $expense_id)
    // {
    //     $expense = Expense::find($expense_id);
    //     if (empty($expense)) {
    //         $request->session()->flash('success', 'Expense Not Found');
    //         return response()->json([
    //             'status' => false,
    //             'message' => 'expense Not Found'
    //         ]);
    //     }
    //     $transection = Transection::where('transection_type_id', $expense_id)->first();
    //     if ($transection) {
    //         $transection->forceDelete();
    //     }

    //     $expense->forceDelete();

    //     $request->session()->flash('success', 'Expense Permanent Deleted Successfully');
    //     return response()->json([
    //         'status' => true,
    //         'message' => 'Expense Permanent Deleted Successfully'
    //     ]);
    // }
    // public function restore(Request $request, $expense_id)
    // {
    //     $expense = Expense::withTrashed()->find($expense_id);
    //     if (empty($expense)) {
    //         $request->session()->flash('success', 'Expense Not Found');
    //         return response()->json([
    //             'status' => false,
    //             'message' => 'Expense Not Found'
    //         ]);
    //     }

    //     $expense->restore();

    //     $transaction = Transection::withTrashed()->where('transection_type_id', $expense_id)->first();
    //     if ($transaction) {
    //         $transaction->restore();
    //     }

    //     $request->session()->flash('success', 'Expense Restored Successfully');
    //     return response()->json([
    //         'status' => true,
    //         'message' => 'Expense Restored Successfully'
    //     ]);
    // }
}