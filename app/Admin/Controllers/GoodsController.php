<?php

namespace App\Admin\Controllers;

use App\Model\WxGoodsModel;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class GoodsController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'App\Model\WxGoodsModel';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new WxGoodsModel);

        $grid->column('id', __('Id'));
        $grid->column('img', __('商品图片'))->image();
        $grid->column('price', __('商品价格'));
        $grid->column('goods_name', __('商品名称'));
        $grid->column('desc', __('商品详情'));
        $grid->column('created_at', __('Created at'));
        $grid->column('updated_at', __('Updated at'));
        

        return $grid;
    }

    /**
     * Make a show builder.
     *
     * @param mixed $id
     * @return Show
     */
    protected function detail($id)
    {
        $show = new Show(WxGoodsModel::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('img', __('商品图片'));
        $show->field('price', __('商品价格'));
        $show->field('goods_name', __('商品名称'));
        $show->field('created_at', __('Created at'));
        $show->field('updated_at', __('Updated at'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new WxGoodsModel);

        $form->image('img', __('商品图片'));
        $form->number('price', __('商品价格'));
        $form->text('goods_name', __('商品名称'));
        $form->ckeditor('desc');

        return $form;
    }
}
