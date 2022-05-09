<?php

namespace App\Http\Controllers;

use App\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Cashier\Cashier;
use \Stripe\Stripe;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products = Product::latest()->paginate(5);
  
        return view('products.index',compact('products'))
            ->with('i', (request()->input('page', 1) - 1) * 5);
    }
   
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('products.create');
    }
  
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'detail' => 'required',
            'price'=>'required'
        ]);
  
        $product = new Product();
        $product->name =$request->name;
        $product->detail= $request->detail;
        $product->price=$request->price;
        $stripe = new \Stripe\StripeClient(
            \config('services.stripe.secret')
        );

        $prod=$stripe->products->create([
            'name' => $request->name,
        ]);
        $plan=$stripe->plans->create([
            'amount' => $request->price*100,
            'currency' => 'inr',
            'interval' => 'month',
            'product' => $prod->id,
        ]);
        $product->stripe_plan_id=$plan->id;
        $product->save();
        return redirect()->route('products.index')
                        ->with('success','Product created successfully.');
    }
    public function retrievePlans() {
        $key = \config('services.stripe.secret');
        $stripe = new \Stripe\StripeClient($key);
        $plansraw = $stripe->plans->all();
        $plans = $plansraw->data;
        
        foreach($plans as $plan) {
            $prod = $stripe->products->retrieve(
                $plan->product,[]
            );
            $plan->product = $prod;
        }
        return $plans;
    }
    /**
     * Display the specified resource.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        $plans = Product::where('id',$product->id)->first();
        $user = Auth::user();
        return view('products.show', [
            'user'=>$user,
            'intent' => $user->createSetupIntent(),
            'plan' => $plans->stripe_plan_id
            ,'product'=>$product
        ]);
      
    }
   
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        return view('products.edit',compact('product'));
    }
  
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required',
            'detail' => 'required',
            'price'=>'required'
        ]);
  
        $product->update($request->all());
  
        return redirect()->route('products.index')
                        ->with('success','Product updated successfully');
    }
  
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        $product->delete();
  
        return redirect()->route('products.index')
                        ->with('success','Product deleted successfully');
    }
}
