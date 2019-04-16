<?php

namespace App\Validates;

use App\Handlers\AesEncryptHandler;
use App\Handlers\SlugTranslateHandler;
use App\Models\Article;
use App\Models\ArticleCategory;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use DB;
use Auth;

class  ArticleValidate extends Validate
{
    protected $message = '操作成功';
    protected $data = [];

    public function storeValidate($request_data)
    {
        $rules = [
            'title' => 'required|between:2,255',
            'article_category_id' => 'required|gt:0',
            'content' => 'required',
            'enable' => [
                'required',
                Rule::in(['T', 'F'])
            ],
            'recommend' => [
                'required',
                Rule::in(['T', 'F'])
            ],
            'top' => [
                'required',
                Rule::in(['T', 'F'])
            ],
            'access_type' => [
                'required',
                Rule::in(['pub', 'pri', 'pwd'])
            ],
            'weight' => 'required|numeric'
        ];
        $rest_validate = $this->validate($request_data, $rules);
        if ($rest_validate === true) {
            $authUser = Auth::user();
            if (!$authUser->hasRole('Founder')) return $this->baseFailed('抱歉，您没有操作权限');

            $request_data['access_value'] = isset_and_not_empty($request_data, 'access_value');
            $request_data['cover_image'] = isset_and_not_empty($request_data, 'cover_image');
            $request_data['tags'] = isset_and_not_empty($request_data, 'tags');

            $parent_is_exist = ArticleCategory::where('id', $request_data['article_category_id'])->count();
            if (!$parent_is_exist) return $this->baseFailed('上级分类不存在，可能已经被删除，请刷新后重试');

            $is_title_exist = Article::where('title', $request_data['title'])->count();
            if ($is_title_exist) return $this->baseFailed('文章标题被占用');
            $request_data['slug'] = (new SlugTranslateHandler())->translate($request_data['title']);

            if ($request_data['access_type'] === 'pwd') {
                if (!$request_data['access_value']) return $this->baseFailed('密码访问方式必须要输入密码');
                $request_data['access_value'] = AesEncryptHandler::securedEncrypt($request_data['access_value']);
            }

            if ($request_data['cover_image']) {
                $request_data['cover_image'] = $request_data['cover_image']['url'];
            }
            $request_data = unset_if_no_value($request_data, ['keywords', 'description', 'cover_image']);
            return $this->baseSucceed($request_data, $this->message);
        } else {
            $this->message = $rest_validate;
            return $this->baseFailed($this->message);
        }
    }


    public function updateValidate($request_data, $model = '')
    {
        $rules = [
            'title' => 'required|between:2,255',
            'article_category_id' => 'required|gt:0',
            'content' => 'required',
            'enable' => [
                'required',
                Rule::in(['T', 'F'])
            ],
            'recommend' => [
                'required',
                Rule::in(['T', 'F'])
            ],
            'top' => [
                'required',
                Rule::in(['T', 'F'])
            ],
            'access_type' => [
                'required',
                Rule::in(['pub', 'pri', 'pwd'])
            ],
            'weight' => 'required|numeric'
        ];
        $rest_validate = $this->validate($request_data, $rules);
        if ($rest_validate === true) {
            $authUser = Auth::user();
            if (!$authUser->hasRole('Founder')) return $this->baseFailed('抱歉，您没有操作权限');

            $request_data['access_value'] = isset_and_not_empty($request_data, 'access_value');
            $request_data['cover_image'] = isset_and_not_empty($request_data, 'cover_image');
            $request_data['tags'] = isset_and_not_empty($request_data, 'tags');

            if ($model->article_category_id != $request_data['article_category_id']) {
                $parent_is_exist = ArticleCategory::where('id', $request_data['article_category_id'])->count();
                if (!$parent_is_exist) return $this->baseFailed('上级分类不存在，可能已经被删除，请刷新后重试');
            }

            if ($model->title != $request_data['title']) {
                $is_title_exist = Article::where('title', $request_data['title'])->where('id', '<>', $model->id)->count();
                if ($is_title_exist) return $this->baseFailed('文章标题被占用');
                $request_data['slug'] = (new SlugTranslateHandler())->translate($request_data['title']);
            }


            if ($request_data['access_type'] === 'pwd') {
                if (($model->access_type === 'pwd')) {
                    if ($request_data['access_value']) {
                        $request_data['access_value'] = AesEncryptHandler::securedEncrypt($request_data['access_value']);
                    } else {
                        unset($request_data['access_value']);
                    }
                } else {
                    if (!$request_data['access_value']) return $this->baseFailed('密码访问方式必须要输入密码');
                    $request_data['access_value'] = AesEncryptHandler::securedEncrypt($request_data['access_value']);
                }
            }

            if ($request_data['cover_image']) {
                $request_data['cover_image'] = $request_data['cover_image']['url'];
            }

            $request_data = unset_if_no_value($request_data, ['keywords', 'description', 'cover_image']);
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
        return $this->baseSucceed($this->data, $this->message);
    }

    protected function validate($request_data, $rules)
    {
        $message = [
            'title.required' => '文章标题不能为空',
            'title.between' => '文章标题只能在:min-:max个字符范围',
            'article_category_id.required' => '请选择文章分类',
            'article_category_id.gt' => '文章分类格式不正确',
            'content.required' => '请填写文章内容',
            'enable.required' => 'enable 字段不能为空',
            'enable.in' => 'enable 字段格式不正确',
            'recommend.required' => 'recommend 字段不能为空',
            'recommend.in' => 'recommend 字段格式不正确',
            'top.required' => 'top 字段不能为空',
            'top.in' => 'top 字段格式不正确',
            'access_type.required' => '请选择文章访问权限',
            'access_type.in' => 'access_type 字段格式不正确',
            'weight.required' => '权重不能为空',
            'weight.numeric' => '权重只能是数字格式',
        ];
        $validator = Validator::make($request_data, $rules, $message);
        if ($validator->fails()) {
            return $validator->errors()->first();
        }
        return true;
    }
}
