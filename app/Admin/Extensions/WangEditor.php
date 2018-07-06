<?php

namespace App\Admin\Extensions;

use Encore\Admin\Form\Field;

class WangEditor extends Field
{
    protected $view = 'admin.extensions.wang-editor';

    protected static $css = [
        '/vendor/wangEditor-3.1.0/release/wangEditor.min.css',
        '/vendor/wangEditor-3.1.0/release/wangEditor-fullscreen-plugin.css',
    ];

    protected static $js = [
        '/vendor/wangEditor-3.1.0/release/wangEditor.min.js',
        '/vendor/wangEditor-3.1.0/release/wangEditor-fullscreen-plugin.js',
    ];

    public function render()
    {
        $name = $this->formatName($this->column);

        $this->script = <<<EOT

var E = window.wangEditor
var editor = new E('#{$this->id}');
editor.customConfig.zIndex = 0;
editor.customConfig.uploadImgServer = '/admin/wang_editor/images';  // 上传图片到服务器
editor.customConfig.uploadFileName = 'images[]';
editor.customConfig.uploadImgMaxLength = 3;   // 限制一次最多上传 1 张图片
editor.customConfig.uploadImgHeaders = {
    'X-Requested-With': 'XMLHttpRequest'
}
editor.customConfig.uploadImgHooks = {
    error: function (xhr, editor) {
        var obj = JSON.parse(xhr.response);
   
//单提示方式:
        alert(obj.errors["images.0"]);
        
//多提示方式:
//        var str = '';            
//        var i = 1;
//        for (err in obj.errors)
//        {
//            str += "第" + i + "张图片 : " + obj.errors[err] + "\\r\\n";
//            i++;
//        }
//        alert(str);

    }
}
// 自定义菜单配置
editor.customConfig.menus = [
    'head',  // 标题
    'bold',  // 粗体
    'fontSize',  // 字号
    'fontName',  // 字体
    'italic',  // 斜体
    'underline',  // 下划线
    'strikeThrough',  // 删除线
    'foreColor',  // 文字颜色
    'backColor',  // 背景颜色
//    'link',  // 插入链接
//    'list',  // 列表
    'justify',  // 对齐方式
//    'quote',  // 引用
//    'emoticon',  // 表情
    'image',  // 插入图片
    'table',  // 表格
//    'video',  // 插入视频
//    'code',  // 插入代码
    'undo',  // 撤销
    'redo'  // 重复
]

editor.customConfig.onchange = function (html) {
    $('input[name=$name]').val(html);
}

    editor.create();


//setTimeout(function(){
//
//    //E.fullscreen.init('#{$this->id}');
//},3000);


EOT;
        return parent::render();
    }
}