<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Auth, Validator;

class UserController extends Controller
{
    public function __construct () {

    }

    public function profile () {
      return view('users.profile');
    }

    public function updateProfile (Request $request) {
      $user = User::find(Auth::user()->id);

      $message = [
          'medicineName.required'  => 'Medicine Name is required.',
          'pricePerPiece.required' => 'Price is required.',
          'bulkPrice.required'     => 'Bulk Price is required.',
          'supplierPrice.required' => 'Supplier`s Price is required.',
          'quantityStock.required' => 'Quantity is required.',
          'pricePerPiece.numeric'  => 'Price must be valid number.',
          'bulkPrice.numeric'      => 'Bulk Price must be valid number.',
          'supplierPrice.numeric'  => 'Supplier`s Price must be valid number.',
          'quantityStock.numeric'  => 'Quantity must be valid number.',
      ];

      $rules = [
        'medicineName'  => 'required',
        'pricePerPiece' => 'required|numeric',
        'bulkPrice'     => 'required|numeric',
        'supplierPrice' => 'required|numeric',
        'quantityStock' => 'required|numeric'
      ];

      $validator = Validator::make($request->all(), $rules, $message);
      if ($validator->fails()) {
        $json['validator_error'] = $validator->messages();
        $json['is_successful'] = false;
      } else {
        if ($request->has('password') && $request->has('confirm_password') && $request->input('password') !== null) {
          $password = $request->input('password');
          $user->password = bcrypt($password);
        }
      }

      $user->save();
      $json['request'] = $request->all();
      $json['user'] = $user;
      return response()->json($json);
    }
}
