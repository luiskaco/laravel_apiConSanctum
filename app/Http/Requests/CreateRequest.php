<?php

namespace App\Http\Requests;

use Dotenv\Exception\ValidationException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class CreateRequest extends FormRequest
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
            'name' => 'required|min:3',
            'email' => 'required|email|unique:users,email',
            'password' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'El campo nombre es requerido',
            'name.min' => 'Debe al menos tener un minimo de 3 caracteres',
            'email.required' => 'El campo email es requerido',
            'email.unique' => 'El correo ingresado ya ha sido registrado',
            'password.required' => 'El campo password es requerido',
        ];
    }


    // Reescribimos a variable validator faile - para que funcione la api con cutom request

    protected function failedValidation(Validator $validator)
    {


       // $errors = (new ValidationException($validator))->errors();

        throw new HttpResponseException(
            response()->json([
                'status' => 'fail',
                'message' => 'Estos campos son requeridos',
                'result' => $validator->errors()
            ], 400)
        );
    }
}
