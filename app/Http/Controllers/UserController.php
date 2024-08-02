<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Contracts\Session\Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;


class UserController extends Controller
{

    public function viewSignUp()
    {
        return view('sign-up');
    }
    public function viewLogIn()
    {
        return view('log-in');
    }
    public function viewUserList()
    {
        $getUsers = DB::table('users')->get();
        // dd( $getUsers);
        return view('user-list', [
            'getUsers' =>  $getUsers,
        ]);
    }
    public function viewProductPage()
    {
        $getProducts = DB::table('all_products')->get();
        // dd( $getProducts);
        return view('product-page', [
            'getProducts' =>  $getProducts,
        ]);
    }
    public function changeUserRole(Request $request)
    {
        $user_id = $request->up_user_id;
        $user_role = $request->up_user_role;

        $details = array(
            'role_id' => $user_role,
        );
        if ($user_id == 1) {
            return redirect()->back()->with('error', 'Superadmin roll cannot be change.');
        } else {
            $data = DB::table('users')->where(['id' => $user_id])->update($details);
            if ($data) {
                return redirect()->back()->with('success', 'Permission updated.');
            } else {
                return redirect()->back()->with('error', 'Something went wrong.');
            }
        }
    } 
  
    public function authenticate(Request $request)
    {
        $credentials = $request->only('email', 'password');
        $info = DB::table('users')->where('email', '=', $request->email)->first();

        try {
            if (!$token = JWTAuth::attempt($credentials)) {
                return redirect()->back()->withErrors(['email' => 'Invalid credentials.'])->withInput();
            }
        } catch (JWTException $e) {
            return redirect()->back()->withErrors(['email' => 'Could not create token.'])->withInput();
        }

        if (Auth::attempt($credentials)) {
            // Store user data in session
            $data = [
                "id" => $info->id,
                "name" => $info->name,
                "email" => $info->email,
                "role_id" => $info->role_id,
                "token" => $token, // Use the generated JWT token here
            ];
            $request->session()->put('user_account_session', $data);

            // Update the user's remember token in the database
            DB::table('users')->where('email', $request->email)->update(['remember_token' => $token]);

            // Redirect to product page
            return redirect()->intended('product-page')->with('success', 'Logged in successfully.');
        }

        return redirect()->back()->withErrors(['email' => 'The provided credentials do not match our records.'])->withInput();
    }

    public function checkAdminSession(Request $request)
    {
        echo "<pre>";
        print_r($request->session()->all());
        echo "<pre>";
    }
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
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
            return redirect('product-page')->with('success', 'Data updated successfully.');
        } else {
            return redirect()->back()->with('error', 'Something went wrong.');
        }
    }
    public function logout(Request $request)
    {
        $user_id = $request->session()->get('user_account_session.id');
        DB::table('users')->where('id','=',$user_id)->update(['remember_token' => 'null']);
        $request->session()->flush();

        Auth::logout();

        return redirect('/log-in')->with('success', 'You have been logged out.');
    }

    public function addProduct(Request $request)
    {

        $request->validate([
            'product_name' => 'required',
            'product_title' => 'required',
            'product_price' => 'required',
        ]);

        $current_date = date("Y.m.d h:i:s");
        $product_name = $request->product_name;
        $product_title = $request->product_title;
        $product_price = $request->product_price;
        $added_by = $request->session()->get('user_account_session.name');

        $detail = array(
            'product_name' => $product_name,
            'product_title' => $product_title,
            'product_price' => $product_price,
            'added_by' => $added_by,
            'created_at' => $current_date,
            'updated_at' => $current_date

        );

        $data = DB::table('all_products')->insert($detail);

        if ($data) {
            return redirect()->back()->with('success', 'Product Added Successfully');
        } else {
            return redirect()->back()->with('error', 'Something went wrong');
        }
    }

    public function  editProduct(Request $request)
    {
        $current_date = date("Y.m.d h:i:s");
        $product_id = $request->up_p_id;
        $product_name = $request->up_p_name;
        $product_title = $request->up_p_title;
        $product_price = $request->up_p_price;
        $added_by = $request->session()->get('user_account_session.name');
        $detail = array(
            'product_name' => $product_name,
            'product_title' => $product_title,
            'product_price' => $product_price,
            'added_by' => $added_by,
            'created_at' => $current_date,
            'updated_at' => $current_date

        );

        $data = DB::table('all_products')->where('id', '=', $product_id)->update($detail);

        if ($data) {
            return redirect()->back()->with('success', 'Product Added Successfully');
        } else {
            return redirect()->back()->with('error', 'Something went wrong');
        }
    }

    public function  deleteProduct($id)
    {

        $data = DB::table('all_products')->where('id', '=', $id)->delete();

        if ($data) {
            return redirect()->back()->with('success', 'Product Deleted Successfully');
        } else {
            return redirect()->back()->with('error', 'Something went wrong');
        }
    }
    public function deleteUser($id)
    {
        // Check if the id is 1 (superadmin id)
        if ($id == 1) {
            return redirect()->back()->with('error', 'Cannot delete superadmin.');
        } else {

            // Proceed with deletion for other ids
            $data = DB::table('users')->where('id', $id)->delete();

            if ($data) {
                return redirect()->back()->with('success', 'User Deleted Successfully');
            } else {
                return redirect()->back()->with('error', 'Something went wrong');
            }
        }
    }

    public function getAuthenticatedUser()
    {
        try {

            if (!$user = JWTAuth::parseToken()->authenticate()) {
                return response()->json(['user_not_found'], 404);
            }
        } catch (Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {

            return response()->json(['token_expired'], $e->getStatusCode());
        } catch (Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {

            return response()->json(['token_invalid'], $e->getStatusCode());
        } catch (Tymon\JWTAuth\Exceptions\JWTException $e) {

            return response()->json(['token_absent'], $e->getStatusCode());
        }

        return response()->json(compact('user'));
    }
}
