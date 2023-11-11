<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Validator;

class CustomerController extends Controller
{
    public function index()
    {
        $customers = Customer::orderBy('id', 'desc')->get();
        $respondsCode = 404;

        if ($customers->count() > 0) {
            $data = [
                'status' => 200,
                'customers' => $customers
            ];
            $respondsCode = 200;
        } else {
            $data = [
                'status' => 404,
                'customers' => "No Customer Data Found"
            ];
            $respondsCode = 404;
        }

        return response()->json($data, $respondsCode);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'address' => 'required',
            'phoneNumber' => 'required',
            'idCard' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'message' => $validator->messages(),
            ], 422);
        } else {
            $customer = Customer::create([
                'name' => $request->name,
                'address' => $request->address,
                'phoneNumber' => $request->phoneNumber,
                'idCard' => $request->idCard,
            ]);
            $customer->save();
        }

        if ($customer) {
            return response()->json([
                'status' => 200,
                'message' => 'Customer Added Succesfully!'
            ], 200);
        } else {
            return response()->json([
                'status' => 500,
                'message' => 'Something Went Wrong!'
            ], 500);
        }

    }

    public function show($id)
    {
        $customer = Customer::find($id);

        if ($customer == null) {
            return response()->json([
                "status" => 404,
                "message" => "Customer with ID $id not found."
            ], 404);
        } else {
            return response()->json([
                "status" => 200,
                "customers" => $customer
            ], 200);
        }
    }

    public function edit($id)
    {
        $customer = Customer::find($id);
        if ($customer == null) {
            return response()->json([
                "status" => 404,
                "message" => "Customer with ID $id not found."
            ], 404);
        } else {
            return response()->json([
                "status" => 200,
                "customers" => $customer,
            ], 200);
        }
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'address' => 'required',
            'phoneNumber' => 'required',
            'idCard' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'message' => $validator->messages(),
            ], 422);
        } else {
            $customer = Customer::find($id);

            if ($customer) {
                $customer->update([
                    'name' => $request->name,
                    'address' => $request->address,
                    'phoneNumber' => $request->phoneNumber,
                    'idCard' => $request->idCard,
                ]);

                return response()->json([
                    'status' => 200,
                    'message' => 'Customer Updated Successfully!'
                ], 200);
            } else {
                return response()->json([
                    'status' => 404,
                    'message' => "Customer with ID $id not found."
                ], 404);
            }
        }
    }

    public function destroy($id)
    {
        $customer = Customer::find($id);
        if ($customer) {
            $customer->delete();
            return response()->json([
                "status" => 200,
                "message" => "Customer Deleted Sucessfully!"
            ], 200);
        } else {
            return response()->json([
                "status" => 404,
                "message" => "Customer with ID $id not found."
            ], 404);
        }
    }


}
