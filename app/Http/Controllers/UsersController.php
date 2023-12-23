<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;

use App\Http\Requests\UserRequest;

use App\Handlers\ImageUploadHandler;

class UsersController extends Controller
{
    public function show(User $user){
        return view('users.show',['user'=>$user]);
    }
    public function edit(User $user){
        return view('users.edit',['user'=>$user]);
    }
    public function update(UserRequest $request,User $user){
       // dd($request->avatar);
        //dd($request->all());
        $data=$request->all();

        if($request->avatar){
           $uploader=new ImageUploadHandler();
            $res=$uploader->save($request->avatar,'avatars',$user->id);
            
            if($res){
                $data['avatar']=$res['path'];
            }     
        }
        $user->update($data);
        return redirect()->route('users.show',$user->id)->with('success','个人资料更新成功');
    }
}
