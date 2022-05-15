<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Auth;
use DB;
class BlogController extends Controller{
    
    public function index(){
        $blogs = Blog::latest()->where('is_active', 1)->get();

        return Inertia::render('Blog/Index', [
            'user'      => $user,
            'cat' => $categories,
        ]);  
       // return view('frontend.blog.index', compact('blogs'));
    }







    public function show($slug){
        $blog = Blog::where('slug', $slug)->where('is_active', 1)->first();
        $categories = DB::table('blog_category')->where('is_active', 1)->get();
        // var_dump(count($categories));
        // exit;

        $date = date('d', strtotime($blog->created_at));
        $month = date('M', strtotime($blog->created_at));
        $user = Auth::user();
		if($blog){
           $comments = DB::table('comments')
            ->where('commentable_id', $blog->id)
            ->join('web_users','web_users.id','=','comments.commented_id')
            ->get();
            // var_dump($comments);
            // exit;
            return Inertia::render('Blog/Blog', [
                'blog'      => $blog,
                'date'      => $date,
                'month'     => $month,
                'comments'  => $comments,
                'categories'  => $categories,
                'user'     => $user,
            ]);  
		}else{
            return redirect()->back();
		}
        
    }

    public function comment(Request $request){
        $slug     = $request->slug;
        $comment  = $request->comment;
        if(!$slug){
            session()->flash('error', 'Opps.. ! Something is went wrong.'); 
            return redirect()->back(); 
        }
        if(!$comment){
            session()->flash('error', 'Comment field is required.'); 
            return redirect()->back(); 
        }
        if(Auth::user()){
            $blog = Blog::where('slug', $slug)->first();
            if($blog){
                $data['commentable_id']  = $blog->id;
                $data['commentable_type']= 'App\Post';
                $data['commented_type']  = 'App\Customer';
                $data['commented_id']    =  Auth::user()->id;
                $data['comment']         =  $comment;
                $data['approved']        =  2;
                DB::table('comments')->insert($data);
                session()->flash('success', 'Thank you for comment..! Your comment is waiting for moderation.'); 
                return redirect()->back();
            }else{
                session()->flash('error', 'Opps.. ! Something is went wrong.'); 
                return redirect()->back(); 
            }
        }else{
            session()->flash('error', 'You have to login first.'); 
            return redirect()->back();  
        }

        // $user = auth('customer')->user();
        // $blog = Blog::where('slug', $slug)->first();
		// if($blog){
		// 	$comment = $request->comment;
		// 	$user->comment($blog, $comment, $rate=0);
		// }
        // return redirect()->back();
    }
    public function category_wise(Request $request){
        $cat_id = $request->category_id;
        $blogs = Blog::latest()->where('category_id', $cat_id)->where('is_active', 1)->get();
        $category = DB::table('blog_category')->where('is_active', 1)->where('id', $cat_id)->first();

        if(count($blogs) > 0 && $category){
            return Inertia::render('Blog/Index', [
                'blogs'      => $blogs,
                'category_name' => $category->title,
            ]);  
        }else{
            session()->flash('error', 'Data not found by this category'); 
            return redirect()->back(); 
        }

    }
    









}
