<?php

namespace App\Http\Controllers;

use App\Models\blog;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('bloglist')->with(['title' => 'Blogs | Ayuniya | ayuniya.com', 'css' => 'blog.scss']);
    }

    public function getBlog()
    {
        return view('blog')->with(['title' => 'Blogs | Ayuniya | ayuniya.com', 'css' => 'blog.scss']);
    }


    public function admin()
    {
        if (isAdmin()|| isCustomerCareManager()) {
            $blogs = blog::get();
            return view('dashboard.views.blogs')->with(['css' => 'blogs.scss', 'blogs' => $blogs]);
        } else {
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
    // public function store(Request $request)
    // {

    //         if ($request->file('blog_image')) {



    //             $banner = time() . "-" . rand(0, 999999999) . '.' . $request->file('bannerimage')->extension();
    //             $request->bannerimage->move(public_path('../../image.ayuniya.com/products'), $banner);

    //             $blogs = blog::insert([
    //                 "product_name" => $request->input('name'),
    //                 "short_des" => $request->input('shortdes'),
    //                 "long_des" => $request->input('longdes'),
    //                 "category" => $request->input('category'),
    //                 "banner" => $banner,
    //                 "vendor" => $request->input('vendor'),
    //                 "created_at" => date("Y-m-d")
    //             ]);


    //         } else {
    //             $response = array(
    //                 "error" => 1,
    //                 "msg" => "Please select an image for the product"
    //             );
    //         }

    //     return response(json_encode($response));
    // }
    public function store(Request $request)
    {

        $Blog = new  blog();

        $blog_image = time() . "." . $request->image_add->getClientOriginalName();
        $request->image_add->move(public_path('QR'), $blog_image);

        $Blog->blog_image = $blog_image;
        $Blog->blog_Title = $request->blog_Title;
        $Blog->blog_dis = $request->blog_dis;


        //dd($Blog);


        try {
            $data = $request->validated();
            $Blog->save();
            return redirect()->back()->with('message', 'New Blog post added Successfully');
        } catch (\Exception $ex) {
            return redirect()->back()->with('message', 'somthing went wrong' . $ex);
        }
    }


    /**
     * Display the specified resource.
     *
     * @param  \App\Models\blog  $blog
     * @return \Illuminate\Http\Response
     */
    public function show(blog $blog)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\blog  $blog
     * @return \Illuminate\Http\Response
     */
    public function edit(blog $blog)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\blog  $blog
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, blog $blog)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\blog  $blog
     * @return \Illuminate\Http\Response
     */
    public function delete(Request $request)
    {
        $response = array();
        if (sanitize($request->input('action')) == "delete" && !empty(sanitize($request->input('id')))) {
            $delete = blog::where('id', "=", sanitize($request->input('id')))->delete();
            if ($delete) {
                $response = array(
                    "error" => 0,
                    "msg" => "Blog post deleted"
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
                "msg" => "Sorry something went wrong"
            );
        }

        return response(json_encode($response));
    }
}
