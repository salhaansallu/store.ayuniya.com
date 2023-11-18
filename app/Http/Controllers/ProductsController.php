<?php

namespace App\Http\Controllers;

use App\Models\products;
use App\Models\varients;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Session;

class ProductsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        redirectCookie();

        $app_name = config('app.name');
        $product = array();
        $category = getCategories();

        if (isset($_GET['priceFilter']) && !empty(sanitize($_GET['priceFilter'])) && is_numeric(sanitize($_GET['priceFilter']))) {
            Session::put('max_price_range', sanitize($_GET['priceFilter']));

            $product = products::inRandomOrder()->paginate(12);
            $product->map(function ($subvarient) {
                return $subvarient->sortedvarients;
            });

            if ($product->count() == 0) {
                $product = "No product found";
            }

            //dd($product);

            return view('shop')->with(['title' => 'Shop for any type of herbal medicine at '. $app_name .'.com | ' . $app_name, 'keyword' => $app_name.", Online shopping, Medicine, Herbal medicine, Health, ".$app_name.".com", 'metaDes' => "Shop for any type of herbal medicine at ".$app_name.".com." , 'css' => 'shop.scss', 'js' => 'products.js', 'categories' => $category, 'filterproducts' => $product, 'request' => 'shop']);
        }

        $product = products::inRandomOrder()->paginate(12);
        $product->map(function ($subvarient) {
            return $subvarient->varient;
        });

        if ($product->count() == 0) {
            $product = "No product found";
        }

        return view('shop')->with(['title' => 'Shop for any type of herbal medicine at '. $app_name .'.com | ' . $app_name, 'keyword' => $app_name.", Online shopping, Medicine, Herbal medicine, Health, ".$app_name.".com", 'metaDes' => "Shop for any type of herbal medicine at ".$app_name.".com." , 'css' => 'shop.scss', 'js' => 'products.js', 'categories' => $category, 'products' => $product, 'request' => 'shop']);
    }

    public function search($search)
    {
        redirectCookie();

        $category = getCategories();
        $app_name = config('app.name');

        $search = str_replace('-', ' ', sanitize($search));

        if (isset($_GET['priceFilter']) && !empty(sanitize($_GET['priceFilter'])) && is_numeric(sanitize($_GET['priceFilter']))) {
            Session::put('max_price_range', sanitize($_GET['priceFilter']));

            $product = products::where("product_name", "LIKE", "%{$search}%")->paginate(12);
            $product->map(function ($subvarient) {
                return $subvarient->sortedvarients;
            });

            if ($product->count() == 0) {
                $product = "No product found";
            }

            //dd($product->toArray());

            return view('shop')->with(['title' => 'Shop for ' . $search . ' | ' . config('app.name').'.com', 'keyword' => $app_name.", Online shopping, Medicine, Herbal medicine, Health, ".$app_name.".com", 'metaDes' => "Shop for ". $search ." at ".$app_name.".com.", 'css' => 'shop.scss', 'js' => 'products.js', 'categories' => $category, 'filterproducts' => $product, 'request' => 'search', 'keyword' => $search]);
        }

        $product = products::where("product_name", "LIKE", "%{$search}%")->paginate(12);
        $product->map(function ($subvarient) {
            return $subvarient->varient;
        });

        if ($product->count() == 0) {
            $product = "No product found";
        }

        return view('shop')->with(['title' => 'Shop for ' . $search . ' | ' . config('app.name').'.com', 'keyword' => $app_name.", Online shopping, Medicine, Herbal medicine, Health, ".$app_name.".com", 'metaDes' => "Shop for ". $search ." at ".$app_name.".com.", 'css' => 'shop.scss', 'js' => 'products.js', 'categories' => $category, 'products' => $product, 'request' => 'search', 'keyword' => $search]);
    }

    public function admin()
    {
        if (isAdmin()||  isAccountManager()) {
            if (isset($_GET['edit'])) {
                $products = products::orderBy("id", "DESC")->paginate(25);
                $products->map(function ($subvarient) {
                    return $subvarient->varient;
                });

                $editproducts = products::where("id", "=", sanitize($_GET['edit']))->get();
                $editproducts->map(function ($subvarient) {
                    return $subvarient->varient;
                });

                return view('dashboard.views.products')->with(['css' => 'products.scss', 'products' => $products, 'editproducts' => $editproducts]);
            } else {
                $products = products::orderBy("id", "DESC")->paginate(25);
                $products->map(function ($subvarient) {
                    return $subvarient->varient;
                });
                return view('dashboard.views.products')->with(['css' => 'products.scss', 'products' => $products]);
            }
        }
        else {
            return redirect(route('login'));
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
        $response = array();
        if (!empty(sanitize($request->input('varient_count')))) {
            $var_count = sanitize($request->input('varient_count'));
            if ($request->file('bannerimage')) {

                for ($j = 1; $j <= $var_count; $j++) {
                    if (!$request->file('var_image' . $j)) {
                        $response = array(
                            "error" => 1,
                            "msg" => "Please select an image for varient " . $j
                        );
                        return response(json_encode($response));
                    }
                }

                $banner = time() . "-" . rand(0, 999999999) . '.' . $request->file('bannerimage')->extension();
                $request->bannerimage->move(public_path('../../image.ayuniya.com/products'), $banner);

                $product = products::insert([
                    "product_name" => $request->input('name'),
                    "short_des" => $request->input('shortdes'),
                    "long_des" => $request->input('longdes'),
                    "category" => $request->input('category'),
                    "banner" => $banner,
                    "vendor" => $request->input('vendor'),
                    "created_at" => date("Y-m-d")
                ]);

                if ($product) {
                    for ($i = 1; $i <= $var_count; $i++) {
                        $image = time() . "-" . rand(0, 999999999) . '.' . $request->file('var_image' . $i)->extension();
                        $request->file('var_image' . $i)->move(public_path('../../image.ayuniya.com/products'), $image);
                        varients::insert([
                            "sku" => $request->input('sku' . $i),
                            "v_name" => $request->input('v_name' . $i),
                            "unit" => $request->input('unit' . $i),
                            "qty" => $request->input('qty' . $i),
                            "price" => $request->input('price' . $i),
                            "sales_price" => $request->input('sales_price' . $i),
                            "status" => $request->input('active' . $i),
                            "weight" => $request->input('weight' . $i),
                            "image_path" => $image,
                            "pro_id" => products::where("product_name", "=", $request->input('name'))->get()[0]->id,
                            "created_at" => date("Y-m-d")
                        ]);
                    }

                    $response = array(
                        "error" => 0,
                        "msg" => "Product created successfully"
                    );
                } else {
                    $response = array(
                        "error" => 1,
                        "msg" => "Sorry, Error while creating product"
                    );
                }
            } else {
                $response = array(
                    "error" => 1,
                    "msg" => "Please select an image for the product"
                );
            }
        }
        return response(json_encode($response));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\products  $products
     * @return \Illuminate\Http\Response
     */
    public function show(products $products)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\products  $products
     * @return \Illuminate\Http\Response
     */
    public function edit(products $products)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\products  $products
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $response = array("error" => 1, "msg" => "Sorry, somthing went wrong");
        $pro_id = sanitize($request->input('pro_id'));
        $product = products::where("id", "=", $pro_id);

        if ($product->count() > 0) {
            if ($request->file('banner')) {
                if (file_exists(public_path('../../image.ayuniya.com/products' . $product->get()[0]->banner))) {
                    File::delete('../../image.ayuniya.com/products' . $product->get()[0]->banner);
                    $banner = time() . "-" . rand(0, 999999999) . '.' . $request->file('banner')->extension();
                    $request->banner->move(public_path('../../image.ayuniya.com/products'), $banner);
                    $product->update(["banner" => $banner]);
                } else {
                    $banner = time() . "-" . rand(0, 999999999) . '.' . $request->file('banner')->extension();
                    $request->banner->move(public_path('../../image.ayuniya.com/products'), $banner);
                    $product->update(["banner" => $banner]);
                }
            }

            $product->update([
                "product_name" => sanitize($request->input('name')),
                "short_des" => sanitize($request->input('shortdes')),
                "long_des" => sanitize($request->input('longdes')),
                "category" => sanitize($request->input('category')),
                "vendor" => sanitize($request->input('vendor'))
            ]);

            if (sanitize($request->input('var_count')) > 0) {
                $varient = varients::where("pro_id", "=", $pro_id);
                if ($varient->count() > 0) {
                    for ($i = 1; $i <= sanitize($request->input('var_count')); $i++) {

                        if ($request->file('var_image' . $i)) {
                            if (file_exists(public_path('../../image.ayuniya.com/products' . varients::where("id", "=", sanitize($request->input('id' . $i)))->get()[0]->image_path))) {
                                File::delete('../../image.ayuniya.com/products' . varients::where("id", "=", sanitize($request->input('id' . $i)))->get()[0]->image_path);
                                $image_path = time() . "-" . rand(0, 999999999) . '.' . $request->file('var_image' . $i)->extension();
                                $request->file('var_image' . $i)->move(public_path('../../image.ayuniya.com/products'), $image_path);
                                varients::where("id", "=", sanitize($request->input('id' . $i)))->update(["image_path" => $image_path]);
                            } else {
                                $image_path = time() . "-" . rand(0, 999999999) . '.' . $request->file('var_image' . $i)->extension();
                                $request->file('var_image' . $i)->move(public_path('../../image.ayuniya.com/products'), $image_path);
                                varients::where("id", "=", sanitize($request->input('id' . $i)))->update(["image_path" => $image_path]);
                            }
                        }

                        varients::where("id", "=", sanitize($request->input('id' . $i)))->update([
                            "v_name" => sanitize($request->input('v_name' . $i)),
                            "unit" => sanitize($request->input('unit' . $i)),
                            "qty" => sanitize($request->input('qty' . $i)),
                            "price" => sanitize($request->input('price' . $i)),
                            "sales_price" => sanitize($request->input('sales_price' . $i)),
                            "weight" => sanitize($request->input('weight' . $i)),
                            "status" => sanitize($request->input('active' . $i)),
                        ]);

                        $response = array(
                            "error" => 0,
                            "msg" => "Product updated successfully"
                        );
                    }

                } else {
                    $response = array(
                        "error" => 1,
                        "msg" => "Error while updating the product. Please delete the product an add again"
                    );
                }
            } else {
                $response = array(
                    "error" => 1,
                    "msg" => "Please add atleast 1 varient"
                );
            }
        } else {
            $response = array(
                "error" => 1,
                "msg" => "Sorry, somthing went wrong"
            );
        }

        return response(json_encode($response));
    }

    public function delete(Request $request)
    {
        $response = array(
            "error" => 1,
            "msg" => "Sorry, somthing went wrong"
        );

        if ($request->input('action') == "delete"  && sanitize($request->input('id'))) {
            $product = products::where("id", "=", sanitize($request->input('id')));
            if ($product->count() > 0) {
                $delbanner = $product->get();
                if (file_exists(public_path('../../image.ayuniya.com/products' . $delbanner[0]->banner))) {
                    File::delete('../../image.ayuniya.com/products' . $delbanner[0]->banner);
                }

                $varients = varients::where("pro_id", "=", sanitize($request->input('id')));
                if ($varients->count() > 0) {
                    $del_images = $varients->get();
                    foreach ($del_images as $del_image) {
                        if (file_exists(public_path('../../image.ayuniya.com/products' . $del_image->image_path))) {
                            File::delete('../../image.ayuniya.com/products' . $del_image->image_path);
                        }
                    }

                    if ($varients->delete()) {
                        if ($product->delete()) {
                            $response = array(
                                "error" => 0,
                                "msg" => "Product deleted successfully"
                            );
                        } else {
                            $response = array(
                                "error" => 1,
                                "msg" => "Sorry, somthing went wrong"
                            );
                        }
                    } else {
                        $response = array(
                            "error" => 1,
                            "msg" => "Sorry, somthing went wrong"
                        );
                    }
                } else {
                    $response = array(
                        "error" => 1,
                        "msg" => "Sorry, somthing went wrong"
                    );
                }
            }
        }

        return response(json_encode($response));
    }

    public function deleteVarient(Request $request)
    {
        $varients = varients::where("id", "=", sanitize($request->input('id')));
        $mainPro = varients::where("pro_id", "=", $varients->get()[0]->pro_id)->get();
        if ($mainPro->count() > 1) {
            $del_images = $varients->get();
            foreach ($del_images as $del_image) {
                if (file_exists(public_path('../../image.ayuniya.com/products' . $del_image->image_path))) {
                    File::delete('../../image.ayuniya.com/products' . $del_image->image_path);
                }
            }

            if ($varients->delete()) {
                $response = array(
                    "error" => 0,
                    "msg" => "Varient deleted successfully"
                );
            } else {
                $response = array(
                    "error" => 1,
                    "msg" => "Sorry, somthing went wrong"
                );
            }
        } else {
            $response = array(
                "error" => 1,
                "msg" => "Atleast 1 varient needed",
            );
        }
        return response(json_encode($response));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\products  $products
     * @return \Illuminate\Http\Response
     */
    public function destroy(products $products)
    {
        //
    }
}
