<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------


use think\Route;

Route::get('api/:version/banner/:id','api/:version.Banner/getBanner');


/*
*blog CMS相关
*/

Route::group('api/:version/cms',function(){
    Route::post('/add_user','api/:version.cms.CmsUser/add_user');//新增用户
    Route::post('/user_change','api/:version.cms.CmsUser/user_change');//修改用户
    Route::post('/add_user_avatar','api/:version.cms.CmsUser/add_user_avatar');//新增用户头像
    Route::post('/user_login','api/:version.cms.CmsUser/user_login');//用户登录
	Route::get('/user_list_get_by_count','api/:version.cms.CmsUser/user_list_get_by_count');//根据评论数量从高到底查看用户10条
    Route::post('/article_type_add','api/:version.cms.CmsArticleType/article_type_add');//文章类别新增
    Route::get('/article_type_list_get','api/:version.cms.CmsArticleType/article_type_list_get');//查看所有文章类别
    Route::delete('/article_type_delete','api/:version.cms.CmsArticleType/article_type_delete');//文章类别删除
	Route::post('/article_add_img','api/:version.cms.CmsArticle/article_add_img');//文章图片新增
    Route::post('/article_add','api/:version.cms.CmsArticle/article_add');//文章新增
    Route::get('/article_list_get','api/:version.cms.CmsArticle/article_list_get');//查看所有文章
    Route::get('/article_list_get_by_count','api/:version.cms.CmsArticle/article_list_get_by_count');//根据评论数量从高到底查看文章10条
	Route::delete('/article_delete','api/:version.cms.CmsArticle/article_delete');//文章删除
	Route::get('/article_get','api/:version.cms.CmsArticle/article_get');//查看单篇文章
	Route::post('/article_comments_add','api/:version.cms.CmsArticleComments/article_comments_add');//文章评论新增
	Route::get('/article_comments_get','api/:version.cms.CmsArticleComments/article_comments_get');//查看文章评论
	Route::post('/banenr_type_add','api/:version.cms.CmsBannerType/banenr_type_add');//banner类别新增
	Route::get('/banner_type_list_get','api/:version.cms.CmsBannerType/banner_type_list_get');//查看所有banner类别
	Route::delete('/banner_type_delete','api/:version.cms.CmsBannerType/banner_type_delete');//文章类别删除
});

/**
 * CMS后台管理API
 */
Route::group('api/:version/cms',function(){
    Route::get('/joinAdd','api/:version.cms.CmsJoin/add');
    Route::get('/joinLook','api/:version.cms.CmsJoin/lookAll');
    Route::get('/getGifts','api/:version.cms.CmsGiftsSettings/getGifts'); 
    Route::post('/addGifts','api/:version.cms.CmsGiftsSettings/addGifts');
    Route::get('/getNews','api/:version.cms.CmsNewsSettings/getNews');
    Route::post('/addNews','api/:version.cms.CmsNewsSettings/addNews');
    Route::post('/add','api/:version.cms.CmsUsernews/addUsernews');
    Route::post('/content','api/:version.cms.CmsUsernews/addContent');
    Route::get('/getBelong','api/:version.cms.CmsBelong/getBelong');
    Route::get('/getAllLeader','api/:version.cms.CmsUser/getAllLeader');
    Route::get('/getAllAdmin','api/:version.cms.CmsUser/getAllAdmin');
    Route::post('/addUser','api/:version.cms.CmsUser/userAdd');
    Route::post('/changePassword','api/:version.cms.CmsUser/changePassword');
    Route::get('/getPesonAll','api/:version.cms.CmsUsernews/getPesonAll');
    Route::get('/getLeaderAll','api/:version.cms.CmsUsernews/getLeaderAll');
    Route::get('/getAll','api/:version.cms.CmsUsernews/getAll');
    Route::get('/getChange','api/:version.cms.CmsUsernews/getChange');
    Route::get('/checkedman','api/:version.cms.CmsUsernews/checkedman');
    Route::get('/searchUsernews','api/:version.cms.CmsUsernews/searchUsernews');
    Route::post('/change','api/:version.cms.CmsUsernews/change');
    Route::get('/delete','api/:version.cms.CmsUsernews/delete');
    Route::get('/giftsDelete','api/:version.cms.CmsGiftsSettings/giftsDelete');
    Route::get('/newsDelete','api/:version.cms.CmsNewsSettings/newsDelete');
    //wechat
    Route::post('/addActivated','api/:version.cms.CmsActivated/addActivated');
    Route::get('/AllActivated','api/:version.cms.CmsActivated/getAll');
    Route::get('/OneActivated','api/:version.cms.CmsActivated/getOne');
});

/**
 * 商城banner - API
 */
Route::get('api/:version/theme','api/:version.Theme/getSimpleList');
Route::get('api/:version/theme/:id','api/:version.Theme/getComplexOne');

Route::post('api/:version/test/test','api/:version.Test/test');

/**
 * 商城产品 - API
 */
Route::group('api/:version/product',function(){
    Route::get('/by_category','api/:version.Product/getAllInCategory');
    Route::get('/:id','api/:version.Product/getOne',[],['id'=>'\d+']);
    Route::get('/recent','api/:version.Product/getRecent');
    /**
     * cms - 后台产品相关
     */
    Route::get('/all','api/:version.Product/getAll');
    Route::post('/add','api/:version.Product/addProduct');
    Route::post('/content','api/:version.Product/addContent');
    Route::post('/addType','api/:version.Category/addType');
});
Route::get('api/:version/category/all','api/:version.Category/getAllCategories');

//Route::get('api/:version/product/recent','api/:version.Product/getRecent');
//Route::get('api/:version/product/by_category','api/:version.Product/getAllInCategory');
//Route::get('api/:version/product/:id','api/:version.Product/getOne',[],['id'=>'\d+']);






Route::post('api/:version/token/user','api/:version.Token/getToken');
Route::post('api/:version/token/verify','api/:version.Token/verifyToken');
Route::get('api/:version/token/app','api/:version.Token/getAppToken');




Route::post('api/:version/address','api/:version.Address/createOrUpdateAddress');
Route::get('api/:version/address','api/:version.Address/getUserAddress');




Route::group('api/:version/order',function(){
    Route::post('','api/:version.Order/placeOrder');
    Route::get('/by_user','api/:version.Order/getSummaryByUser');
    Route::get('/:id','api/:version.Order/getDetail',[],['id'=>'\d+']);
    Route::get('/paginate','api/:version.Order/getSummary');
});



Route::group('api/:version/pay',function(){
    Route::post('/pre_order','api/:version.Pay/getPreOrder');
    Route::post('/notify','api/:version.Pay/receiveNotify');
    Route::post('/re_notify','api/:version.Pay/redirectNotify');

});

