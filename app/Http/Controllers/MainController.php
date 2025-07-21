<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MainController extends Controller
{
   public function index()
   {
    echo "I'm inside the app!";
   }
   
   public function NewNote()
   {
    echo "I'm creat new note!";
   }
}
