<?php

namespace App\Http\Controllers;

use App\Category;
use Illuminate\Http\Request;
use App\MyProduct;
use App\User;
use Illuminate\Support\Facades\Auth;

class MyProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //check if login
        if( Auth::check() ){
            $myproducts = MyProduct::all()->toArray();
            return view('myproducts.index', compact('myproducts'));
        }

        //return
        return view('auth.login');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create( $category_id = null )
    {
        if( Auth::check() ) {
            //return view('myproducts.create');

            $categories = null;
            if(!$category_id){
                $categories = Category::all();    // Subject
            }
            $users = User::all();  //Students
            return view('myproducts.create',
                         ['category_id'=>$category_id,
                             'categories'=>$categories,
                             'users'=>$users]);
        }

        return view('auth.login');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $myproduct = $this->validate(request(), [
            'name' => 'required',
            'price' => 'required|numeric',
            'category_id' => 'required',
            'user_id' => 'required',
        ]);

        MyProduct::create($myproduct);

        return back()->with('success', 'Product has been added');; //session
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $myproduct = MyProduct::find($id);

        return view('myproducts.show', ['myproduct'=>$myproduct]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if( Auth::check() ) {
            $myproduct = MyProduct::find($id);
            $categories = Category::all();
            //return view('myproducts.edit', compact('myproduct', 'id'));
            return view('myproducts.edit',
                ['myproduct'=>$myproduct, 'id'=>$id, 'categories'=>$categories]);
        }
        return view('auth.login');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $product = MyProduct::find($id);
        $this->validate(request(), [
            'name' => 'required',
            'price' => 'required|numeric',
            'category_id' => 'required'
        ]);
        $product->name = $request->get('name');
        $product->price = $request->get('price');
        $product->category_id = $request->get('category_id');
        $product->save();
        return redirect('myproducts')->with('success','Product has been updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $myproduct = MyProduct::find($id);
        $myproduct->delete();
        return redirect('myproducts')->with('success','Product has been deleted');
    }
}
