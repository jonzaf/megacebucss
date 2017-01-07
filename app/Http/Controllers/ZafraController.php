<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Post;
use App\Tag;

class ZafraController extends Controller
{
    //
    public function index(){
        return view('welcome');
    }

    public function tagsdb(Request $request)
    {

		/*//sample code in the view that uses this function
		<form method="post" action="{{ url('/tagsdb') }}">
            {{ csrf_field() }}
            <input type="text" name="tags" id="tags" value=""/>
            <input type="submit" name="submit" id="submiT" value="Submit"/>
        </form>*/

	$post = Post::find(2);//!!this should be edited to find an appropriate post and not just find 2 all the time.
    if(Tag::count()!=0)
       $post->Tag()->detach();//removes all previous tags

	$taglist = explode(";",$request->tags);//split strings @ semi-colons
    array_splice($taglist,5);//limit to 5 tags
	foreach ($taglist as $tag)//adds new tags to connections. if tag does not exist, it is added, otherwise tag is retrieved
    	{
            if(Tag::count()==0 || !Tag::where('tag_name','=', $tag)->exists())
            {
                $tagitem = Tag::create([
                    'tag_name' => $tag,
                ]);
            }
            else
            {
                $tagitem = Tag::where('tag_name','=', $tag)->first();
            }
            
    		$post->Tag()->attach($tagitem->id);//attach to connector table
    	}
      
        return redirect('index');
    }

}
