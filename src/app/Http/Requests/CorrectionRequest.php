<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CorrectionRequest extends FormRequest
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
            'work_start' => 'required|date_format:H:i|before:work_end',
            'work_end' => 'required|date_format:H:i|after:work_start',
            'rest_start' => 'nullable|date_format:H:i|after_or_equal:work_start',
            'rest_end' => 'nullable|date_format:H:i|before_or_equal:work_end',
            'note' => 'required'
        ];
    }

    public function messages(){
        return [
            'work_start.required' => '出勤・退勤時間を入力してください。',
            'punchIn.before' => '出勤時間もしくは退勤時間が不適切な値です。',
            'punchOut.after' => '出勤時間もしくは退勤時間が不適切な値です。',
            'break_begins.after_or_equal' => '休憩時間が勤務時間外です。',
            '' => '休憩時間が勤務時間外です。',
            'break_ends.before_or_equal' => '備考を記入してください。',
            'note.required' => '備考を入力してください。'
        ];
    }

    public function withValidator ($validator) {
        $validator -> after(function ($validator) {
            $start = $this->input('work_start');
            $end = $this->input('work_end');
            if (is_null($start) && !is_null($end) || !is_null($work_start) && is_null($work_end)) {
                $validator->errors()->add('work_start','開始時間と終了時間は両方入力するか、両方空にしてください');
            }
        });
    }
}
