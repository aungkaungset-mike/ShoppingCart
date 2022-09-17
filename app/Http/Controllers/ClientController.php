<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Category;
use App\Models\Product;
use App\Models\Slider;
use App\Models\Client;
use App\Models\Order;
use App\Cart;
use Session;

class ClientController extends Controller
{
    public function home()
    {
        $sliders = Slider::All()->where('status', 1);

        $products = Product::All()->where('status', 1);

        return view('client.home')->with('sliders', $sliders)->with('products', $products);
    }

    public function shop()
    {
        $categories = Category::All();

        $products = Product::All()->where('status', 1);

        return view('client.shop')->with('categories', $categories)->with('products', $products);
    }

    public function checkout()
    {
        if(!Session::has('client'))
        {
            return view('client.login');
        }

        if(!Session::has('cart'))
        {
            return view('client.cart');
        }
        return view('client.checkout');
    }

    public function login()
    {
        return view('client.login');
    }

    public function signup()
    {
        return view('client.signup');
    }

    public function logout()
    {
        Session::forget('client');

        return redirect('/shop');

    }

    public function orders()
    {
        $orders = Order::All();

        $orders->transform(function($order, $key)
        {
            $order->cart = unserialize($order->cart);

            return $order;
        });

        return view('admin.orders')->with('orders', $orders);
    }

    public function addToCart($id){
        $product = Product::find($id);

        $oldCart = Session::has('cart')? Session::get('cart'):null;
        $cart = new Cart($oldCart);
        $cart->add($product, $id);
        Session::put('cart', $cart);

        //dd(Session::get('cart'));
        return back();
    }

    public function cart(){
        if(!Session::has('cart')){
            return view('client.cart');
        }

        $oldCart = Session::has('cart')? Session::get('cart'):null;
        $cart = new Cart($oldCart);
        return view('client.cart', ['products' => $cart->items]);
    }

    public function update_qty(Request $request, $id){
        //print('the product id is '.$request->id.' And the product qty is '.$request->quantity);
        $oldCart = Session::has('cart')? Session::get('cart'):null;
        $cart = new Cart($oldCart);
        $cart->updateQty($id, $request->quantity);
        Session::put('cart', $cart);

        //dd(Session::get('cart'));
        return back();
    }

    public function remove_from_cart($id){
        $oldCart = Session::has('cart')? Session::get('cart'):null;
        $cart = new Cart($oldCart);
        $cart->removeItem($id);
       
        if(count($cart->items) > 0){
            Session::put('cart', $cart);
        }
        else{
            Session::forget('cart');
        }

        //dd(Session::get('cart'));
        return redirect('/cart');
    }

    public function createaccount(Request $request)
    {
        $this->validate($request, ['email'=>'email|required|unique:clients',
                                   'password' => 'required|min:4']);

        $client = new Client();
        $client->email = $request->input('email');
        $client->password = bcrypt($request->input('password'));

        $client->save();

        return back()->with('status', 'Acount has been created!');
    }

    public function  accessaccount(Request $request)
    {
        $this->validate($request, ['email'=>'email|required',
                                   'password' => 'required']);

        $client = Client::where('email', $request->input('email'))->first();
        
        if($client)
        {
            if(Hash::check($request->input('password'), $client->password))
            {
                Session::put('client', $client);
                return redirect('/shop');
            }
            else
            {
                return back()->with('status', 'Bad email or password');
            }
        }
        else
        {
            return back()->with('status', 'You dont have an acount');
        }    
    }

    public function postcheckout(Request $request)
    {
        $oldCart = Session::has('cart')? Session::get('cart'):null;
        $cart = new Cart($oldCart);

        $order = new Order();
        $order->name = $request->input('name');
        $order->address = $request->input('address');
        $order->cart =  serialize($cart);

        $order->save();

        Session::forget('cart');

        return redirect('/cart')->with('status', 'Purchase has been accomplished');
    }
}
