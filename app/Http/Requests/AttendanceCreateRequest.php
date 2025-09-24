<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AttendanceCreateRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'class_subject_id' => ['required','integer','exists:class_subjects,id'],
            'date' => ['nullable','date'],
        ];
    }
}
