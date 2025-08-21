<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;

use function Laravel\Prompts\password;

class AuthController extends Controller
{
    public function login()
    {
        return view('login');
    }

    public function loginSubmit(Request $request)
    {
        //form validation
        $request->validate(
            //rules
            [
                'text_username' => 'required|email',
                'text_password' => 'required|min:6|max:16'
            ],
            //error messages
            [
                'text_username.required' => 'O username é obrigatorio',
                'text_username.email' => 'O username deve ser uma e-mail válido',
                'text_password.required' => 'O password é obrigatorio',
                'text_password.min' => 'A password deve ter pelo menos :min caracteres',
                'text_password.max' => 'A password deve ter maximo :max caracteres'
            ]
        );

        //get user input 
        $username = $request->input('text_username');
        $password = $request->input('text_password');

        //get all the users from database
        //$users = User::all()->toArray();

        //as an object instance oh the model's class
        //$userModel = new User();
        // $users = $userModel->all()->toArray();

        // check if user exist
        $user = User::where('username', $username)
            ->where('deleted_at', NULL)
            ->first();

        if (!$user) {
            return redirect()
                ->back()
                ->withInput()
                ->with('loginError', 'Username incorreto.');
        }

        //check if password is correct
        if (!password_verify($password, $user->password)) {
            return redirect()
                ->back()
                ->withInput()
                ->with('loginError', 'password incorreto.');
        }

        // update last login
        $user->last_login = date('Y-m-d H:i:s');
        $user->save();

        //login user
        session([
            'user'=>[
                'id' => $user->id,
                'username' => $user->username
            ]
            ]);

        //redirect to home
        return redirect()->to('/');
        
    }


    public function logout()
    {
        //logout from the application
        session()->forget('user');
        return redirect()->to('/login');
    }
}
