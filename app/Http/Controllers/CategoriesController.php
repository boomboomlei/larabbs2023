<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Topic;
use App\Models\Link;
use App\Models\User;
use App\Models\Category;

class CategoriesController extends Controller
{
    public function show(Category $category,User $user,Link $link){
        $topics=Topic::where("category_id",$category->id)->paginate(15);
        $active_users=$user->getActiveUsers();
        $links=$link->getAllCached();
        return view("topics.index",['links'=>$links,"topics"=>$topics,'category'=>$category,'active_users'=>$active_users]);
    }
}
