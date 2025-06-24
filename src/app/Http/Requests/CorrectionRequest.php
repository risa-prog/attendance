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
            'work_end' => 'required|date_format:H:i',
            'rest_start' => 'nullable|array',
            'rest_start.*' => 'nullable|date_format:H:i|after:work_start|before:work_end',
            'rest_end' => 'nullable|array|',
            'rest_end.*' => 'nullable|date_format:H:i|before:work_end',
            'note' => 'required'
        ];
    }

    public function messages(){
        return [
            'work_start.required' => '出勤時間を入力してください',
            'work_start.date_format' => '開始時間は「時:分（例:09:00）」の形式で入力してください',
            'work_start.before' => '出勤時間もしくは退勤時間が不適切な値です',
            'work_end.required' => '退勤時間を入力してください',
            'work_end.date_format' => '終了時間は「時:分（例:18:00）」の形式で入力してください',
            'rest_start.*.date_format' => '開始時間は「時:分（例:12:00）」の形式で入力してください',
            'rest_start.*.after' => '休憩時間が勤務時間外です',
            'rest_start.*.before' => '休憩時間が勤務時間外です',
            'rest_end.*.date_format' => '終了時間は「時:分（例:13:00）」の形式で入力してください',
            'rest_end.*.before' => '休憩時間が勤務時間外です',
            'note.required' => '備考を記入してください'
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $this->validateRestTimeCheck($validator);
            $this->validateFormInputCheck($validator);
        });
    }

    protected function validateRestTimeCheck($validator) {
        $starts = $this->rest_start ?? [];
        $ends = $this->rest_end ?? [];

        foreach ($starts as $i => $start) {
            $end = $ends[$i] ?? null;

            if ($start && $end && $start >= $end) {
                $validator->errors()->add("rest_end.$i", '休憩時間が不適切な値です');
            }    
        }
    }

    protected function validateFormInputCheck($validator) {
        $starts = $this->rest_start ?? [];
        $ends = $this->rest_end ?? [];

        foreach ($starts as $i => $start) {
            $end = $ends[$i] ?? null;

            // どちらか片方だけ入力された場合
            if ($start && !$end) {
                $validator->errors()->add("rest_end.$i", '終了時間を入力してください');
            }

            if (!$start && $end) {
                $validator->errors()->add("rest_start.$i", '開始時間を入力してください');
            }
        }       
    }
}

    