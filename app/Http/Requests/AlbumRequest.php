<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AlbumRequest extends FormRequest
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
            'name' => 'bail|required|string|max:255',
            'genre' => 'bail|required|exists:genres,id',
            'date' => 'bail|required|date|after:today',
            'upc' => 'nullable',
            'cover' => 'required',
            'copyrightCheck1' => 'required',
            'copyrightCheck2' => 'required',
            'copyrightCheck3' => 'required',
            'songs.*.song' => 'required',
            'songs.*.composer' => 'bail|required|string|max:255',
            'songs.*.title' => 'bail|required|string|max:255',
            'song.*.language' => 'bail|required|string|max:255'
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'copyrightCheck1.required' => 'You must confirm copyright status 1',
            'copyrightCheck2.required' => 'You must agree with the copyright status 2',
            'copyrightCheck3.required' => 'You must confirm copyright status 3',
            'songs.*.song.required' => 'You must upload a file for :attribute',
        ];
    }

    public function attributes()
    {
        return [
            'songs.*.song' => 'every track'
        ];
    }
}
