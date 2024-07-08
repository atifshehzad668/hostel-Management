<?php

namespace App\Http\Controllers;

use App\Models\Fee;
use Illuminate\Http\Request;
use App\Models\RecivedPayment;

class RecivedPaymentController extends Controller
{
    public function store(Request $request)
    {

        $fee = Fee::find($request->fee_id);
        if (!$fee) {
            return response()->json([
                'status' => false,
                'message' => 'Fee record not found'
            ], 404);
        }


        if ($request->paid_amount == $request->total_amount) {
            $fee->status = 'Paid';
        } elseif ($request->paid_amount < $request->total_amount) {
            $fee->status = 'Partially-paid';
        }


        $fee->save();
        $payments = new RecivedPayment();
        $payments->registration_id = $request->registration_id;
        $payments->total_amount = $request->total_amount;
        $payments->paid_amount = $request->paid_amount;
        $payments->name = $request->name;
        $payments->father_name = $request->father_name;
        if ($request->paid_amount == $request->total_amount) {
            $payments->status = 'Paid';
        } elseif ($request->paid_amount < $request->total_amount) {
            $payments->status = 'Partially-paid';
        }
        $payments->save();

        $request->session()->flash('success', 'Payment Paid Successfully');
        return response()->json([
            'status' => true,
            'message' => 'Payment paid successfully'
        ]);
    }


    public function feedetail(Request $request, $id)
    {
        $fee = Fee::with('registration')->find($id);
        if (!$fee) {
            return response()->json(['error' => 'Fee not found'], 404);
        }

        // Return necessary data for the modal
        return response()->json([
            'fee_id' => $fee->id,
            'registration_id' => $fee->registration_id,
            'name' => $fee->registration->name,
            'father_name' => $fee->registration->father_name,
            'total_amount' => $fee->amount,
        ]);
    }
}