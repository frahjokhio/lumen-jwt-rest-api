<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use  App\Post;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
	public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login'] ]);
    }

    // to create new post
    public function store(Request $request){

    	$this->validate($request, [
	        'name' => 'required|unique:posts|max:200',
	        'description' => 'required',
	        'status' => 'required|in:publish,draft'
	    ]);

    	$input = $request->only('name', 'description', 'status');

    	$post = Post::create($input);

    	if( $post )
    		return response()->json($post, 200);
    	else 
    		return response()->json(['message' => "An error occurred."], 404);

    }

    // to show post by id
    public function show($id){

    	$post = Post::find($id);

    	if( $post )
    		return response()->json($post, 200);
    	else 
    		return response()->json(['message' => "Post Not found."], 404);

    }

    // to update existing post by id
    public function update(Request $request, $id){

    	$this->validate($request, [
	        'name' => 'unique:posts,id,'.$id.'|max:200',
	        'status' => 'in:publish,draft'
	    ]);

    	$input = $request->only('name', 'description', 'status');

	    $post = Post::find($id);

	    if( $post ){
    		
    		$post->update($input);
    		$post = Post::find($id);

    		return response()->json($post, 200);
	    }
    	else {
    		
    		return response()->json(['message' => "Post Not found."], 404);
    	}
    }

    public function delete($id){

    	$post = Post::find($id);

    	if( $post ){
    		
    		if( $post->delete() )
    			return response()->json(['message' => "Post deleted!."], 200);
    		else
    			return response()->json(['message' => "An error occurred."], 404);
    	}
    	else {
    		return response()->json(['message' => "Post Not found."], 404);
    	}

    }


    public function allPosts(){

    	$posts = Post::all();

    	if( $posts )
    		return response()->json($posts, 200);
    	else
    		return response()->json(['message' => "No record found."], 404);
    }


}