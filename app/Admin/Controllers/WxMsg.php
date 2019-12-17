<?php

namespace App\Admin\Controllers;

use App\Model\WxMsgModel;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class WxMsg extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'App\Model\WxMsgModel';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new WxMsgModel);

        $grid->column('msg_id', __('Msg id'));
        $grid->column('msg', __('留言内容'));
        $grid->column('time', __('留言时间'))->display(function($time){
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
        $show = new Show(WxMsgModel::findOrFail($id));

        $show->field('msg_id', __('Msg id'));
        $show->field('msg', __('Msg'));
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
        $form = new Form(new WxMsgModel);

        $form->text('msg', __('Msg'));
        $form->number('time', __('Time'));
        $form->number('uid', __('Uid'));

        return $form;
    }
}
