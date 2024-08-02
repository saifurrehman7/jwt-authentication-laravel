<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Contracts\Session\Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;


class ApiUserController extends Controller
{
    public function authenticateapi(Request $request)
    {
        $credentials = $request->only('email', 'password');
        $info = DB::table('users')->where('email', '=', $request->email)->first();

        try {
            if (!$token = JWTAuth::attempt($credentials)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid credentials'
                ], 401);
            }
        } catch (JWTException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Could not create token'
            ], 500);
        }

        if (Auth::attempt($credentials)) {
            // Store user data
            $data = [
                "id" => $info->id,
                "name" => $info->name,
                "email" => $info->email,
                "role_id" => $info->role_id,
                "token" => $token, // Use the generated JWT token here
            ];
            // $request->session()->put('user_account_session', $data);
            // Update the user's remember token in the database
            DB::table('users')->where('email', $request->email)->update(['remember_token' => $token]);

            return response()->json([
                'success' => true,
                'message' => 'Logged in successfully',
                'data' => $data
            ], 200);
        }

        return response()->json([
            'success' => false,
            'message' => 'The provided credentials do not match our records'
        ], 401);
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation errors',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = User::create([
            'name' => $request->get('name'),
            'email' => $request->get('email'),
            'password' => Hash::make($request->get('password')),
        ]);

        $token = JWTAuth::fromUser($user);
        $lastInsertedId = $user->id;
        $updated_token = DB::table('users')->where('id', $lastInsertedId)->update(['remember_token' => $token]);

        if ($user && $updated_token) {
            return response()->json([
                'success' => true,
                'message' => 'User registered successfully',
                'data' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'token' => $token
                ]
            ], 201);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong'
            ], 500);
        }
    }

    public function viewProducts()
    {
        $getProducts = DB::table('all_products')->get();

        return response()->json([
            'success' => true,
            'data' => $getProducts
        ], 200);
    }
    public function changeUserRole(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'up_user_id' => 'required|integer',
            'up_user_role' => 'required|integer'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()
            ], 400);
        }

        $user_id = $request->up_user_id;
        $user_role = $request->up_user_role;

        if ($user_id == 1) {
            return response()->json([
                'success' => false,
                'message' => 'Superadmin role cannot be changed.'
            ], 403);
        } else {
            $details = array('role_id' => $user_role);
            $data = DB::table('users')->where('id', $user_id)->update($details);

            if ($data) {
                return response()->json([
                    'success' => true,
                    'message' => 'Permission updated.'
                ], 200);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Something went wrong.'
                ], 500);
            }
        }
    }
    public function viewUserList()
    {
        $getUsers = DB::table('users')->select('id', 'name', 'email')->get();

        return response()->json([
            'success' => true,
            'data' => $getUsers,
        ], 200);
    }
    public function viewProductPage()
    {
        $getUP = DB::table('all_products')->select('id', 'product_name', 'product_title', 'product_price')->get();

        return response()->json([
            'success' => true,
            'data' => $getUP,
        ], 200);
    }

    public function deleteProduct(Request $request)
    {
        $id = $request->id;
        $data = DB::table('all_products')->where('id', '=', $id)->delete();

        if ($data) {
            return response()->json([
                'success' => true,
                'message' => 'Product deleted successfully.'
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong.'
            ], 500);
        }
    }

    public function addProduct(Request $request)
    {
        // Validate the request
        $request->validate([
            'product_name' => 'required',
            'product_title' => 'required',
            'product_price' => 'required|numeric',
        ]);

        // Get the current timestamp
        $current_date = Carbon::now();

        // Retrieve input values
        $product_name = $request->input('product_name');
        $product_title = $request->input('product_title');
        $product_price = $request->input('product_price');
        $added_by = $request->product_added_by; // Assuming you have authenticated user

        // Prepare the data for insertion
        $detail = [
            'product_name' => $product_name,
            'product_title' => $product_title,
            'product_price' => $product_price,
            'added_by' => $added_by,
            'created_at' => $current_date,
            'updated_at' => $current_date
        ];

        // Insert data into the database
        $data = DB::table('all_products')->insert($detail);

        // Return response based on insertion result
        if ($data) {
            return response()->json(['success' => 'Product Added Successfully'], 201);
        } else {
            return response()->json(['error' => 'Something went wrong'], 500);
        }
    }
    public function logout(Request $request)
    {
        $user_id = $request->id;
        if ($user_id) {
            DB::table('users')->where('id', '=', $user_id)->update(['remember_token' => 'null']);
            Auth::logout();
            return response()->json([
                'success' => true,
                'message' => 'You have been logged out.'
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'You are not logged out.'
            ]);
        }
    }
    public function editProduct(Request $request)
    {
        $current_date = date("Y.m.d h:i:s");
        $product_id = $request->input('product_id');
        $product_name = $request->input('product_name');
        $product_title = $request->input('product_title');
        $product_price = $request->input('product_price');
        $added_by = $request->input('added_by');

        $detail = [
            'product_name' => $product_name,
            'product_title' => $product_title,
            'product_price' => $product_price,
            'added_by' => $added_by,
            'created_at' => $current_date,
            'updated_at' => $current_date
        ];

        $data = DB::table('all_products')->where('id', '=', $product_id)->update($detail);

        if ($data) {
            return response()->json([
                'success' => true,
                'message' => 'Product updated successfully.'
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong.'
            ], 500);
        }
    }
    public function deleteUser(Request $request)
    {
        $id = $request->id;
        // Check if the id is 1 (superadmin id)
        if ($id == 1) {
            return response()->json(['error' => 'Cannot delete superadmin.'], 403);
        } else {
            // Proceed with deletion for other ids
            $data = DB::table('users')->where('id', $id)->delete();

            if ($data) {
                return response()->json(['success' => 'User Deleted Successfully'], 200);
            } else {
                return response()->json(['error' => 'Something went wrong'], 500);
            }
        }
    }
}
