<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PointCreate extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            // 'title' => 'required|unique:dp_products,title',
            'title' => 'required',
            'point' => 'required',
            'amount' => 'required|numeric|min:100',
            'image' => 'required|image|mimes:jpeg,jpg,png,gif,svg|max:2048',
        ];
    }
}
