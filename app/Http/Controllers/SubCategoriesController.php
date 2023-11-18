<?php

namespace App\Http\Controllers;

use App\Models\Categories;
use App\Models\products;
use App\Models\SubCategories;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class SubCategoriesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (isAdmin()) {
            $category = getCategories();
            return view('dashboard.views.subcategories')->with(['css' => 'subcategories.scss', 'category' => $category]);
        } else {
            return redirect(route('login'));
        }
    }

    public function updateCategory(Request $request)
    {
        $response = array();
        if (sanitize($request->input("updateid")) && sanitize($request->input("updatename")) && sanitize($request->input("updatecategory"))) {
            $sub_ver = SubCategories::where("sub_category_name", "=", sanitize($request->input("updatename")))->where("category_id", "=", sanitize($request->input("updatecategory")))->count();

            if ($sub_ver == 0) {
                $update = SubCategories::where("id", "=", $request->input("updateid"))->update(['sub_category_name' => $request->input('updatename'), 'category_id' => $request->input('updatecategory')]);
                if ($update) {
                    $response = array(
                        "error" => 0,
                        "msg" => "Category updated"
                    );
                } else {
                    $response = array(
                        "error" => 1,
                        "msg" => "Something went wrong"
                    );
                }
            } else {
                $response = array(
                    "error" => 1,
                    "msg" => "Category name already exists"
                );
            }
        } else {
            $response = array(
                "error" => 1,
                "msg" => "Please fill all fields"
            );
        }
        return response(json_encode($response));
    }

    public function addCategory(Request $request)
    {
        $response = array();
        if (sanitize($request->input("createname")) && sanitize($request->input("createcategory"))) {
            $sub_ver = SubCategories::where("sub_category_name", "=", sanitize($request->input('createname')))->where("category_id", "=", sanitize($request->input("createcategory")))->count();
            if ($sub_ver > 0) {
                $response = array(
                    "error" => 1,
                    "msg" => "Sub Category name already used"
                );
            } else {
                $create = SubCategories::insert(['sub_category_name' => sanitize($request->input('createname')), 'category_id' => sanitize($request->input("createcategory"))]);
                if ($create) {
                    $response = array(
                        "error" => 0,
                        "msg" => "Sub category created"
                    );
                } else {
                    $response = array(
                        "error" => 1,
                        "msg" => "Something went wrong"
                    );
                }
            }
        } else {
            $response = array(
                "error" => 1,
                "msg" => "Please fill all fields"
            );
        }

        return response(json_encode($response));
    }

    public function deleteCategory(Request $request)
    {
        if (sanitize($request->input("action")) == "delete" && sanitize($request->input("id"))) {
            $sub = SubCategories::where("id", "=", sanitize($request->input("id")));
            if ($sub->count() > 0) {
                if ($sub->delete()) {
                    $response = array(
                        "error" => 0,
                        "msg" => "Sub category deleted successfully"
                    );
                } else {
                    $response = array(
                        "error" => 1,
                        "msg" => "Sorry something went wrong"
                    );
                }
            } else {
                $response = array(
                    "error" => 1,
                    "msg" => "No such sub category or already deleted"
                );
            }
        } else {
            $response = array(
                "error" => 1,
                "msg" => "Sorry something went wrong"
            );
        }

        return response(json_encode($response));
    }

    public function subcategory($sub)
    {
        if (is_numeric($sub) && $sub != 0) {
            redirectCookie();

            $category = getCategories();
            $app_name = config('app.name');

            $navsubcategory = SubCategories::where("id", "=", $sub)->get();
            $navcategory = Categories::where("id", "=", $navsubcategory[0]->category_id)->get();

            if (isset($_GET['priceFilter']) && !empty(sanitize($_GET['priceFilter'])) && is_numeric(sanitize($_GET['priceFilter']))) {
                Session::put('max_price_range', sanitize($_GET['priceFilter']));

                $product = products::where("category", "=", $sub)->get();
                $product->map(function ($subvarient) {
                    return $subvarient->sortedvarients;
                });

                //dd($product->toArray());

                if ($product->count() == 0) {
                    $product = "No product found";
                }

                return view('shop')->with(['title' => 'Explore all kinds of ' . $navsubcategory[0]->sub_category_name . ' | ' . config('app.name'), 'keyword' => $app_name.", Medicine, Herbal medicine, Health, ".$navsubcategory[0]->sub_category_name.", ".$app_name.".com", 'metaDes' => "Shop for all kind of ". $navsubcategory[0]->sub_category_name ." at ".$app_name.".com." , 'css' => 'shop.scss', 'js' => 'products.js', 'categories' => $category, 'filterproducts' => $product, 'request' => 'categories', 'subcat' => $navsubcategory[0]->sub_category_name, "cat" => $navcategory[0]->category_name]);
            }

            $product = products::where("category", "=", $sub)->paginate(12);
            $product->map(function ($subvarient) {
                return $subvarient->varient;
            });

            if ($product->count() == 0) {
                $product = "No product found";
            }

            return view('shop')->with(['title' => 'Explore all kinds of ' . $navsubcategory[0]->sub_category_name . ' | ' . config('app.name'), 'keyword' => $app_name.", Medicine, Herbal medicine, Health, ".$navsubcategory[0]->sub_category_name.", ".$app_name.".com", 'metaDes' => "Shop for all kind of ". $navsubcategory[0]->sub_category_name ." at ".$app_name.".com." , 'css' => 'shop.scss', 'js' => 'products.js', 'categories' => $category, 'products' => $product, 'request' => 'categories', 'subcat' => $navsubcategory[0]->sub_category_name, "cat" => $navcategory[0]->category_name]);
        }
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
     * @param  \App\Models\SubCategories  $subCategories
     * @return \Illuminate\Http\Response
     */
    public function show(SubCategories $subCategories)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\SubCategories  $subCategories
     * @return \Illuminate\Http\Response
     */
    public function edit(SubCategories $subCategories)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\SubCategories  $subCategories
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, SubCategories $subCategories)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\SubCategories  $subCategories
     * @return \Illuminate\Http\Response
     */
    public function destroy(SubCategories $subCategories)
    {
        //
    }
}
