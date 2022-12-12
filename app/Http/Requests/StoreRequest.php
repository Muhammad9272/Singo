<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->user()->isAdmin();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        if ($this->method() === 'PUT') {
            return [
                'title'         => ['required', 'string', 'max:255', Rule::unique('stores')->ignore($this->store->id)],
                'fuga_store_id' => ['string', 'max:30', Rule::unique('stores')->ignore($this->store->id)],
            ];
        }

        return [
            'title'         => ['required', 'string', 'max:255', 'unique:stores,title'],
            'fuga_store_id' => ['string', 'max:30', 'unique:stores,fuga_store_id'],
        ];
    }
}
