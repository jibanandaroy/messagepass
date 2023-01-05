<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Hash;
use Session;

class CustomAuthController extends Controller
{
     public function login(){
        return view("auth.Login");
     }
     public function registration(){
        return view("auth.registration");
     }
     public function registerUser(Request $request){
        $request->validate([
           'name'=>'required',
           'email'=>'required|email|unique:users',
           'password'=>'required|min:5|max:10'
        ]);
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = hash::make($request->password);
        $res = $user->save();
        if($res){
          return redirect()->route('login');

         
        }else{
            return back()->with('fail', 'Something worng');
        }
     }

     public function loginUser(Request $request)
     {
         $request->validate([
            'email'=>'required|email',
            'password'=>'required|min:5|max:10'
         ]);
         $user= User::where('email', '=', $request->email)->first();
         if($user){
            if(Hash::check($request->password, $user->password)){
               $request->session()->put('loginId', $user->Id);
               return redirect('dashboard');
            }else{
               return back()->with('fail', 'Passwors not matches');
            }
         }else{
            return back()->with('fail', 'This email is not registered.');
         }
     }
     public function dashboard()
     {
         return view('dashboard');
     }
}
