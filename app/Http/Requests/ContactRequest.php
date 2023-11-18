<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ContactRequest extends FormRequest
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


        $rules = [
            'name' => [
                'required',
                'string',
                'max:191'
            ],

            'email' => [
                'required',
                'email',

            ],
            'tp_no' => [
                'required',
                'numeric',
            ],
            'message' => [
                'required',
            ],



        ];
        return $rules;
    }
    public function messages()
    {
        return [
            'name.required' => 'Please add the name',
            'email.required' => 'Please enter email',
            'tp_no.required' => 'Please enter no of contact no',
            'message.required' => 'Please enter your message',


        ];
    }
}
