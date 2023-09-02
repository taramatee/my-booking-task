<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Redirect;

class postController extends Controller
{
    $validatedData = $request->validate([

        'name' => 'required',
    ], [
        'name.required' => 'Name field is required.',
    ]);


    return Http::back()->withErrors(['msg' => 'The Message']);
}
