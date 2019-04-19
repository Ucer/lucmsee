<?php

namespace App\Validates;

use App\Handlers\FunctionHandler;
use App\Models\Article;
use App\Models\ArticleCategory;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use DB;
use Auth;

class  ArticleCategoryValidate extends Validate
{
    protected $message = '操作成功';
    protected $data = [];

    public function storeValidate($request_data)
    {
        $rules = [
            'name' => 'bail|required|between:2,100|unique:article_categories',
            'pid' => 'required',
            'enable' => [
                'required',
                Rule::in(['T', 'F'])
            ]
        ];
        $rest_validate = $this->validate($request_data, $rules);
        if ($rest_validate === true) {

            $authUser = Auth::user();
            if (!$authUser->hasRole('Founder')) return $this->baseFailed('抱歉，您没有操作权限');

            $request_data = unset_if_no_value($request_data, ['description', 'enable']);
            return $this->baseSucceed($request_data, $this->message);
        } else {
            $this->message = $rest_validate;
            return $this->baseFailed($this->message);
        }
    }


    public function updateValidate($request_data, $model = '')
    {
        $rules = [
            'name' => [
                'bail',
                'required',
                'between:2,100',
                Rule::unique('article_categories')->ignore($model->id)
            ],
            'pid' => 'required',
            'enable' => [
                Rule::in(['T', 'F'])
            ]
        ];
        $rest_validate = $this->validate($request_data, $rules);
        if ($rest_validate === true) {
            if ($request_data['pid'] == $model->id) return $this->baseFailed('上级不能是自己');
            $rest_getChildrenTree = (new FunctionHandler())->getChildrenTree(ArticleCategory::get()->toArray(),$model->id);
            if (in_array($request_data['pid'], $rest_getChildrenTree)) {
                return $this->baseFailed('上级不能是自己的下级');
            }

            $authUser = Auth::user();
            if (!$authUser->hasRole('Founder')) return $this->baseFailed('抱歉，您没有操作权限');

            $request_data = unset_if_no_value($request_data, ['description', 'enable']);
            return $this->baseSucceed($request_data, $this->message);
        } else {
            $this->message = $rest_validate;
            return $this->baseFailed($this->message);
        }
    }

    public function destroyValidate($model)
    {
        $authUser = Auth::user();
        if (!$authUser->hasRole('Founder')) return $this->baseFailed('抱歉，您没有操作权限');
        $children_article_count = Article::where('article_category_id',$model->id)->count();
        if($children_article_count) return $this->baseFailed('该分类下有文章，请先删除文章再操作');
        return $this->baseSucceed($this->data, $this->message);
    }

    protected function validate($request_data, $rules)
    {
        $message = [
            'name.required' => '名称不能为空',
            'name.between' => '名称只能在:min-:max个字符范围',
            'name.unique' => '名称已存在',
            'pid.required' => '请选择上级',
            'enable.in' => '是否启用字段格式不正确'
        ];
        $validator = Validator::make($request_data, $rules, $message);
        if ($validator->fails()) {
            return $validator->errors()->first();
        }
        return true;
    }
}
