<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DiscountRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = [
            'title' => ['required','string','max:150'],
            'description' => ['nullable','string','max:1000'],
            'type' => ['required','in:percentage,fixed'],
            'value' => ['required','numeric','min:0'],
            'start_date' => ['nullable','date'],
            'end_date' => ['nullable','date','after_or_equal:start_date'],
            'status' => ['required','in:active,inactive'],
        ];
        if ($this->isMethod('post')) {
            $rules['image'] = ['required','image','max:2048'];
        } else {
            $rules['image'] = ['nullable','image','max:2048'];
        }
        return $rules;
    }
}
