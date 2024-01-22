<?php

namespace App\Http\Controllers;
use App\Http\Requests\BlogRequest;
use App\Models\blog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class BlogController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Fetch all blog posts from the database
        $blogs = Blog::orderBy('created_at', 'desc')->get(); // Retrieve blogs ordered by creation date

        return view('bloglist', [
            'title' => 'Blogs | Ayuniya | ayuniya.com',
            'css' => 'blog.scss',
            'blogs' => $blogs, // Pass the fetched blog data to the view
        ]);
    }

    public function getBlog($id)
    {
        $blogs = Blog::orderBy('created_at', 'desc')->get(); // Retrieve blogs ordered by creation date
        $blog = Blog::find($id);

        return view('blog', [
            'title' => 'Blogs | Ayuniya | ayuniya.com',
            'blog' => $blog, // Pass the single blog data to the view
            'blogs' => $blogs, // Pass the fetched blog data to the view
            'css' => 'blog.scss'
        ]);
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

    public function store(Request $request)
    {
        try {
            // Validate incoming request data
            $validatedData = $request->validate([
                'blog_image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
                'blog_Title' => 'required|string',
                'blog_dis' => 'required|string',
                'blog_content' => 'required|string',
            ]);

            // Handle image upload
            $blog_image = time() . '.' . $request->file('blog_image')->getClientOriginalExtension();
            $request->file('blog_image')->move(public_path('blog_images'), $blog_image);

            // Create a new blog instance
            $blog = new Blog();
            $blog->blog_image = $blog_image;
            $blog->blog_Title = $validatedData['blog_Title'];
            $blog->blog_dis = $validatedData['blog_dis'];
            $blog->blog_content = $validatedData['blog_content'];
            $blog->save();

            // Return a JSON response on success
            return response()->json([
                'error' => 0,
                'msg' => 'New Blog post added successfully'
            ]);
        } catch (\Exception $ex) {
            // Return a JSON response on failure
            return response()->json([
                'error' => 1,
                'msg' => 'Something went wrong: ' . $ex->getMessage()
            ]);
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
    public function update(Request $request)
    {
        // Retrieve the data from the request
        $blogId = $request->input('updateid');
        $blogImage = $request->input('updateimage');
        $blogTitle = $request->input('updatename');
        $blogDescription = $request->input('updatedis');
        $blogContent = $request->input('updatecont');
        // Find the blog entry
        $blog = Blog::find($blogId);

        // Update the blog entry
        if ($blog) {
            $blog->blog_image = $blogImage;
            $blog->blog_Title = $blogTitle;
            $blog->blog_dis = $blogDescription;
            $blog->blog_content = $blogContent;
            // Save the updated blog
            $blog->save();

            return response()->json([
                'error' => 0,
                'msg' => 'Blog updated successfully'
            ]);
        }

        return response()->json([
            'error' => 1,
            'msg' => 'Failed to update blog'
        ]);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\blog  $blog
     * @return \Illuminate\Http\Response
     */


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
