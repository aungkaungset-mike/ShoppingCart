<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function addcategory(){
        return view('admin.addcategory');
    }

    public function categories(){
        return view('admin.categories');
    }

    public function savecategory(Request $request){
        return view('admin.categories');
    }
    
}
