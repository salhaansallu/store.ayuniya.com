<?php

namespace App\Http\Controllers;

use App\Models\Categories;
use App\Models\products;
use App\Models\SubCategories;
use App\Models\varients;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class product extends Controller
{
    public function index($id, $name){
        redirectCookie();

        $cat_id = 0;
        $category_id = 0;
        $app_name = config('app.name');

        $product = products::where('id', '=', $id)->get();
        $product->map(function ($subvarient){
            return $subvarient->varient;
        });

        if ($product->count() > 0) {

            foreach($product as $pro) {
                $cat_id = $pro->category;
            }
    
            $subcategory = SubCategories::where('id', '=', $cat_id)->get();
    
            foreach($subcategory as $sub) {
                $subcategory = $sub->sub_category_name;
                $category_id = $sub->category_id;
            }
    
            $category = Categories::where('id', '=', $category_id)->get();
    
            foreach($category as $cat) {
                $category = $cat->category_name;
            }
    
            $cat = array(
                $category, $category_id,
            );
    
            $sub = array(
                $subcategory, $cat_id,
            );
    
            $includes = products::where('category', '=', $cat[1])->inRandomOrder()->limit(6)->get();
            $includes->map(function ($incsub){
                return $incsub->varient;
            });
        }
        else {
            return redirect('/shop');
        }

        //dd($includes->toArray());
        return view('product')->with(['title'=>$product[0]->product_name.' - '.$sub[0].' - '.config('app.name'), 'keyword' => $app_name.", ". str_replace(" ", ", ", $product[0]->product_name) .", ".$app_name.".com", 'metaDes' => $product[0]->product_name." - ".$sub[0]." - ".$app_name.".com", 'css'=>'product.scss', 'products'=>$product, 'subcategory' => $sub, 'category' => $cat, 'includes'=>$includes]);
    }

    public function varient($sku){
        $products = varients::where('sku', '=', $sku)->get();
        echo json_encode($products);
    }
}
