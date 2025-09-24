<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AttendanceRecordsRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'records' => ['required','array','min:1'],
            'records.*.student_id' => ['required','integer','exists:students,id'],
            'records.*.status' => ['required','in:present,absent,sick,excused'],
        ];
    }
}
