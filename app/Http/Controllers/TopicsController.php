<?php

namespace App\Http\Controllers;

use App\Models\Topic;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\TopicRequest;
use App\Models\Category;
use App\Models\Reply;
use Auth;

use App\Models\Link;

use App\Models\User;

use  App\Handlers\ImageUploadHandler;

class TopicsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth', ['except' => ['index', 'show']]);
    }

	public function index(Request $request,User $user,Link $link)
	{

		$topics = Topic::withOrder($request->order)->with('user','category')->paginate(15);


		$links=$link->getAllCached();

		$active_users=$user->getActiveUsers();
		//dd($active_users);



		return view('topics.index', compact('topics','active_users','links'));
	}

    public function show(Request $request,Topic $topic)
    {

		if(!empty($topic->slug) && $topic->slug!=$request->slug){
			return redirect($topic->link(),301);
		}

		//$replies = Reply::where(['topic_id'=>$topic->id])->paginate(5);

        return view('topics.show', compact('topic'));
    }

	public function create(Topic $topic)
	{
		$categories=Category::all();

		return view('topics.create_and_edit', compact('topic','categories'));
	}

	public function store(TopicRequest $request,Topic $topic)
	{
		// $topic = Topic::create($request->all());
		// return redirect()->route('topics.show', $topic->id)->with('message', 'Created successfully.');
		$topic->fill($request->all());
		$topic->user_id=Auth::id();
		$topic->save();

		//return redirect()->route('topics.show',$topic->id)->with('success','创建帖子成功');
		return redirect()->to($topic->link())->with('success','创建帖子成功');
	}

	public function edit(Topic $topic)
	{
		
        $this->authorize('update', $topic);
		$categories=Category::all();
		return view('topics.create_and_edit', compact('topic','categories'));
	}

	public function update(TopicRequest $request, Topic $topic)
	{
		$this->authorize('update', $topic);
		$topic->update($request->all());

		// return redirect()->route('topics.show', $topic->id)->with("success",'更新成功~~');
		return redirect()->to($topic->link())->with("success",'更新成功~~');
	}

	public function destroy(Topic $topic)
	{
		$this->authorize('destroy', $topic);
		$topic->delete();

		return redirect()->route('topics.index')->with('success', '删除成功~~');
	}

	public function uploadImage(Request $request,ImageUploadHandler $uploader){
		$data=[
			'success'=>false,
			"msg"=>"上传图片失败",
			"file_path"=>''
		];

		if($file=$request->upload_file){
			$res=$uploader->save($file,"topics",Auth::id(),1024);
			if($res){
				$data=[
					'success'=>true,
					"msg"=>"上传图片success",
					"file_path"=>$res['path'],
				];
			}
		}
		return $data;
	}
}