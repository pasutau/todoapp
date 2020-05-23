<?php

namespace App\Http\Requests;

use App\Task;
use Illuminate\Validation\Rule;


class EditTask extends CreateTask
{
    /**
     * Get the validation rules that apply to the request.
     *
     * 
     */
    public function rules()
    {
        $rule = parent::rules();

        $status_rule = Rule::in(array_keys(Task::STATUS));

        return $rule + [
            'status' => 'required|' . $status_rule,
        ];
    }

    public function attributes()
    {
        $attributes = parent::attributes();

        return $attributes + [
            'status' => '状態',
        ];
    }

    public function messages()
    {
        $messages = parent::messages();

        //Task::STATUSの配列が$itemに入り、その中から'labelのみを取り出して格納している
        $status_labels = array_map(function($item) {
            return $item['label'];
        }, Task::STATUS);

        //第2引数の配列を第一引数の句読点で結合して、文字列で格納している
        $status_labels = implode('、', $status_labels);

        return $messages + [
            'status.in' => ':attribute には ' . $status_labels. ' のいずれかを指定してください。',
        ];
    }
}
