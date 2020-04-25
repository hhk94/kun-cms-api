<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/9/21
 * Time: 9:32
 */

namespace app\api\validate\v2;
use app\api\validate\BaseValidate;

class CmsArticleComments extends BaseValidate
{
    protected $rule = [
      'article_id' => 'require|isPositiveInteger',
      'comment_content' => 'require|isNotEmpty',
      'user_id' => 'require|isPositiveInteger',
      'tid'=>'require'
    ];
	protected $message=[
	    'id'=>'id必须为正整数',
	    'article_id'=>'article_id必须为正整数',
	];
}