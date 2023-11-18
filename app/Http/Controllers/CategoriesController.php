<?php

namespace App\Http\Controllers;

use App\Models\Categories;
use App\Models\SubCategories;
use Illuminate\Http\Request;

class CategoriesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (isAdmin()) {
            $categories = getCategories();
            return view('dashboard.views.categories')->with(['css' => 'categories.scss', 'categories' => $categories]);
        } else {
            return redirect(route('login'));
        }
    }

    public function updateCategory(Request $request)
    {
        $response = array();
        if (sanitize($request->input("updateid")) && sanitize($request->input("updatename"))) {
            $update = Categories::where("id", "=", $request->input("updateid"))->update(['category_name' => $request->input('updatename')]);
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
                "msg" => "Please enter category name"
            );
        }
        return response(json_encode($response));
    }

    public function addCategory(Request $request)
    {
        $response = array();
        if (sanitize($request->input("createcategory"))) {
            $vercat = Categories::where("category_name", "=", $request->input('createcategory'));
            if ($vercat->count() == 0) {
                $add = Categories::insert(["category_name" => sanitize($request->input('createcategory'))]);
                if ($add) {
                    $response = array(
                        "error" => 0,
                        "msg" => "Category created"
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
                "msg" => "Please enter category name"
            );
        }
        return response(json_encode($response));
    }

    public function deleteCategory(Request $request)
    {
        $result = array();
        if ($request->input('action') == 'delete' && $request->input('id')) {
            $subdelete = SubCategories::where("category_id", "=", sanitize($request->input('id')));
            if ($subdelete->count() > 0) {
                if ($subdelete->delete()) {
                    $delete = Categories::where('id', '=', sanitize($request->input('id')))->delete();
                    if ($delete) {
                        $result = array(
                            "error" => 0,
                            "msg" => "Category deleted successfully"
                        );
                    } else {
                        $result = array(
                            "error" => 1,
                            "msg" => "Sorry, something went wrong"
                        );
                    }
                } else {
                    $result = array(
                        "error" => 1,
                        "msg" => "Sorry, something went wrong"
                    );
                }
            } else {
                $delete = Categories::where('id', '=', sanitize($request->input('id')))->delete();
                if ($delete) {
                    $result = array(
                        "error" => 0,
                        "msg" => "Category deleted successfully"
                    );
                } else {
                    $result = array(
                        "error" => 1,
                        "msg" => "Sorry, something went wrong"
                    );
                }
            }
        } else {
            $result = array(
                "error" => 1,
                "msg" => "Sorry, something went wrong"
            );
        }
        return response(json_encode($result));
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
     * @param  \App\Models\Categories  $categories
     * @return \Illuminate\Http\Response
     */
    public function show(Categories $categories)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Categories  $categories
     * @return \Illuminate\Http\Response
     */
    public function edit(Categories $categories)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Categories  $categories
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Categories $categories)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Categories  $categories
     * @return \Illuminate\Http\Response
     */
    public function destroy(Categories $categories)
    {
        //
    }
}
