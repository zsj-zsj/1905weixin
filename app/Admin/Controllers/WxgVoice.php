<?php

namespace App\Admin\Controllers;

use App\Model\WxVoiceModel;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class WxgVoice extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'App\Model\WxVoiceModel';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new WxVoiceModel);

        $grid->column('voice_id', __('Voice id'));
        $grid->column('voice', __('语音消息'))->display(function($voice){
            return '<audio src="http://weixin.com/'.$voice.'" controls></audio>';
        });
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
        $show = new Show(WxVoiceModel::findOrFail($id));

        $show->field('voice_id', __('Voice id'));
        $show->field('voice', __('Voice'));
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
        $form = new Form(new WxVoiceModel);

        $form->text('voice', __('Voice'));
        $form->number('time', __('Time'));
        $form->number('uid', __('Uid'));

        return $form;
    }
}
