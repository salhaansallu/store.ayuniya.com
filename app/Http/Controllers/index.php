<?php

namespace App\Http\Controllers;

use App\Models\products;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class index extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        redirectCookie();

        $product = products::inRandomOrder()->limit(8)->get();
        $product->map(function ($subvarient){
            return $subvarient->varient;
        });

        $category = getCategories();

        $app_name = config('app.name');
        return view('index')->with(['title' => $app_name .", World's leading herbal medicine market place. | ".$app_name, 'keyword' => $app_name.", Online shopping, Medicine, Herbal medicine, Health, ".$app_name.".com", 'metaDes' => $app_name." is the world's first fully automated herbal medicine market place.", 'css' => 'home.scss', 'products'=>$product, 'categories'=>$category]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function indexPrivacyPolicy()
    {

        return view('privacy_policy')->with(['css' => 'privacy_policy.scss', ]);
    }

    public function indexreturnPolicy()
    {

        return view('return_policy')->with(['css' => 'privacy_policy.scss', ]);
    }

    public function indextermscondition()
    {
        return view('terms_and_condition')->with(['css' => 'privacy_policy.scss', ]);

    }
}
