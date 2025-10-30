<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class HomePageRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $isCreate = $this->isMethod('post');
        return [
            'type' => ['required','in:slider,banner,middle,service'],
            'title' => ['nullable','string','max:191'],
            'subtitle' => ['nullable','string','max:255'],
            'images'  => ['nullable','array', $isCreate ? 'required_without:image' : 'nullable', $isCreate ? 'min:1' : 'nullable'],
            'images.*'=> ['image','max:6048'],
        ];
    }
}
