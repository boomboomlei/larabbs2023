<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;

use App\Http\Requests\UserRequest;

use App\Handlers\ImageUploadHandler;

class UsersController extends Controller
{

    public function __construct(){
        $this->middleware('auth',['except'=>['show']]);
    }

    public function show(User $user){
        return view('users.show',['user'=>$user]);
    }
    public function edit(User $user){
        $this->authorize('update',$user);
        return view('users.edit',['user'=>$user]);
    }
    public function update(UserRequest $request,User $user){

        $this->authorize("update",$user);
       // dd($request->avatar);
        //dd($request->all());
        $data=$request->all();

        if($request->avatar){
           $uploader=new ImageUploadHandler();
            $res=$uploader->save($request->avatar,'avatars',$user->id,416);
            
            if($res){
                $data['avatar']=$res['path'];
            }     
        }
        $user->update($data);
        return redirect()->route('users.show',$user->id)->with('success','个人资料更新成功');
    }
}
