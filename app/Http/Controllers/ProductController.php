<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Category;
use App\Models\Product;

class ProductController extends Controller
{
    public function addproduct(){
        $categories = Category::All()->pluck('category_name', 'category_name');

        return view('admin.addproduct')->with('categories', $categories);;
    }

    public function products(){
        $products = Product::All();

        return view('admin.products')->with('products', $products);
    }

    public function saveproduct(Request $request){
        $this->validate($request, ['product_name'=>'required',
                                   'product_price' => 'required',
                                   'product_category' => 'required',
                                   'product_image' => 'image|nullable|max:1999' ]);

        if($request->hasfile('product_image'))
        {
            $fileNameWithExt = $request->file('product_image')->getClientOriginalName();

             $ext = $request->file('product_image')->getClientOriginalExtension();

             $fileName = pathinfo($fileNameWithExt, PATHINFO_FILENAME);

             $fileNameToStore = $fileName.'_'.time().'.'.$ext;   
             
             $path = $request->file('product_image')->storeAs('public/product_images', $fileNameToStore);
        }
        else
        {
            $fileNameToStore = 'noimage.jpg';
        }

        $product = new Product();
        $product->product_name = $request->input('product_name');
        $product->product_price = $request->input('product_price');
        $product->product_category = $request->input('product_category');
        $product->product_image = $fileNameToStore;
        $product->status = 1;
        $product->save();

        return back()->with('status', 'Product has been added!');
    }

    
    public function editproduct($id){
        $product = Product::find($id);

        $categories = Category::All()->pluck('category_name', 'category_name');

        return view('admin.editproduct')->with('product', $product)->with('categories', $categories);        
    }

    public function updateproduct(Request $request){

        $this->validate($request, ['product_name'=>'required',
                                   'product_price' => 'required',
                                   'product_category' => 'required',
                                   'product_image' => 'image|nullable|max:1999' ]);

       $product = Product::find($request->input('id'));
       $product->product_name = $request->input('product_name');
       $product->product_price = $request->input('product_price');
       $product->product_category = $request->input('product_category');

       if($request->hasfile('product_image'))
        {
            $fileNameWithExt = $request->file('product_image')->getClientOriginalName();

             $ext = $request->file('product_image')->getClientOriginalExtension();

             $fileName = pathinfo($fileNameWithExt, PATHINFO_FILENAME);

             $fileNameToStore = $fileName.'_'.time().'.'.$ext;   
             
             $path = $request->file('product_image')->storeAs('public/product_images', $fileNameToStore);

             if($product->product_image != 'noimage.jpg')
             {
                Storage::delete('public/product_images'. $product->product_image);
             }

             $product->product_image = $fileNameToStore;
        }

       $product->update(); 
       return redirect('/products')->with('status', 'Product has been updated!');
    }

    public function deleteproduct($id){
        $product = Product::find($id);

        if($product->product_image != 'noimage.jpg')
        {
           Storage::delete('public/product_images'. $product->product_image);
        }
         
        $product->delete();

        return back()->with('status', 'Product has been deleted!'); 
    }

    public function activateproduct($id)
    {
        $product = Product::find($id);

        $product->status = 1;
        $product->update();

        return back()->with('status', 'Product has been activated!'); 
    }
    
    public function unactivateproduct($id)
    {
        $product = Product::find($id);

        $product->status = 0;
        $product->update();

        return back()->with('status', 'Product has been unactivated!'); 
    }

    public function viewproductbycategory($category_name)
    {
        $products = Product::All()->where('product_category', $category_name)->where('status' , 1);

        $categories = Category::All();


        return view('client.shop')->with('categories', $categories)->with('products', $products);
    }
}
