<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

use Auth;

class UserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|between:3,25|regex:/^[A-Za-z0-9\-\_]+$/|unique:users,name,' . Auth::id(),
            'email'=>'required|email',
            'introduction'=>'min:6|max:80',
            "avatar"=>'mimes:png,jpg,gif,jpeg|dimensions:min_width=260,min_height=260',
        ];
    }
    public function messages(){
        return [
            'avatar.mines'=>'头像必须是png,jpg,gif,jpeg',
            'avatar.dimensions'=>'图形太小了~~',
            'name.regex'=>'名字格式不对哦',
            'name.between'=>'名字数量在3-25个的哦~',
            'introduction.min'=>"太少了哦~~",
        ];
    }
}
