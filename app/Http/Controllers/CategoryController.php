<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;

class CategoryController extends Controller
{
    public function addcategory(){
        return view('admin.addcategory');
    }

    public function categories(){
        $categories = Category::All();

        return view('admin.categories')->with('categories', $categories);;
    }

    public function savecategory(Request $request){
        $this->validate($request, ['category_name'=>'required|unique:categories']);

        $category = new Category();
        $category->category_name = $request->input('category_name');
        $category->save();

        return back()->with('status', 'Category has been added!');
    }

    public function editcategory($id){
        $category = Category::find($id);

        return view('admin.editcategory')->with('category', $category);        
    }

    public function updatecategory(Request $request){
       
    }
    
}
