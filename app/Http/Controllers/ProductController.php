<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
//use App\Repositories\ProductRepository;
use App\ {
   Repositories\ProductRepository,
   Models\Product,
   Models\Cart,
   Http\Requests\CartRequest

};

class ProductController extends Controller
{
    /**
     * The Controller instance.
     *
     * @var \Illuminate\Database\Eloquent\Model
     */
    protected $repository;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(ProductRepository $repository)
    //public function __construct()
    {
        //$this->middleware('auth');
        $this->repository = $repository;
    }

    /**
     * Show the application home-page.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    //public function index(Request $request, ProductRepository $repository)
    public function index(Request $request) //$_POST['...'], $_GET['top9'], $_GET['search']
    {
        $products = $this->repository->funcSelect($request); //$_GET['top9'], $_GET['search'] == $request->top9, $request->search
        //roducts = $repository->funcSelect($request);        

        // Ajax response
        if ($request->ajax()) {
            return response()->json([
                'table' => view("product.brick-standard", ['products' => $products])->render(),
            ]);
        } 

        // Submit response
        return view('product.index', compact('products')); //['products' => $products]
    }

    /**
     * Show the application product-page.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function product($id, Product $model_product)
    {
        //$product = $this->repository->funcSelectOne($id); //... 
        $product = $model_product->find($id); //...

        return view('product.product', compact('product')); //['product' => $product]
    }

    /**
     * Show the application cart-page.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function cart(Request $request)
    {
        $carts = $this->repository->fromCart();

        // Ajax response
        if ($request->ajax()) {
            return response()->json([
                'table' => view("product.cart-standard", ['carts' => $carts])->render(),
            ]);
        } 

        return view('product.cart', compact('carts')); //['carts' => $carts]
    }  

    /**
     * Store data to cart.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function tocart(CartRequest $request)
    {
        $this->repository->store($request);

        return redirect(route('cart')); //(url('/cart'))
    } 

    /**
     * Remove all cart.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function clearall(Request $request, Cart $model_cart) //!!!Request
    {
        //$this->repository->destroycart();
        $model_cart->truncate();

        // Ajax response
        if ($request->ajax()) { //!!!if ajax
            return response()->json();
        } 

        return redirect(route('cart')); //!!!if not ajax
    } 

    /**
     * Remove one from cart.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function clearone(Request $request)
    {
        $this->repository->clearone($request); //$request->id
    }   

    /**
     * Mailer for message from sitre.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function mailer(Request $request)
    {
        return $this->repository->mailer($request); //$request->message, $request->contact 
    }                     

}
