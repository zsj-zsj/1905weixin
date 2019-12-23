<?php

namespace App\Admin\Controllers;

use App\Model\WxImgModel;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class WxImg extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '图片素材';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new WxImgModel);

        $grid->column('img_id', __('Img id'));
        $grid->column('img', __('Img'))->image();
        $grid->column('time', __('发送时间'))->display(function($time){
            return date('Y-m-d H:i:s',$time);
        });
        $grid->column('uid', __('Uid'));

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
        $show = new Show(WxImgModel::findOrFail($id));

        $show->field('img_id', __('Img id'));
        $show->field('img', __('Img'));
        $show->field('time', __('Time'));
        $show->field('uid', __('Uid'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new WxImgModel);

        $form->image('img', __('Img'));
        $form->number('time', __('Time'));
        $form->number('uid', __('Uid'));

        return $form;
    }
}
