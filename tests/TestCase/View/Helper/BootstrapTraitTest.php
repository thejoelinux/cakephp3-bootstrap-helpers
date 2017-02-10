<?php

namespace Bootstrap\Test\TestCase\View\Helper;

use Bootstrap\View\Helper\BootstrapTrait;
use Bootstrap\View\Helper\BootstrapFormHelper;
use Bootstrap\View\Helper\BootstrapHtmlHelper;
use Bootstrap\View\Helper\BootstrapPaginatorHelper;
use Cake\TestSuite\TestCase;
use Cake\View\View;

class PublicBootstrapTrait {

    use BootstrapTrait;

    public function __construct($view) {
        $this->Html = new BootstrapHtmlHelper($view);
    }

    public function publicEasyIcon($callback, $title, $options) {
        return $this->_easyIcon($callback, $title, $options);
    }

};

class BootstrapTraitTemplateTest extends TestCase {

    /**
     * Instance of PublicBootstrapTrait.
     *
     * @var PublicBootstrapTrait
     */
    public $trait;

    /**
     * Instance of BootstrapFormHelper.
     *
     * @var BootstrapFormHelper
     */
    public $form;

    /**
     * Instance of BootstrapPaginatorHelper.
     *
     * @var BootstrapPaginatorHelper
     */
    public $paginator;

    /**
     * Setup
     *
     * @return void
     */
    public function setUp() {
        parent::setUp();
        $view = new View();
        $this->trait = new PublicBootstrapTrait($view);
        $this->form = new BootstrapFormHelper($view);
        $this->paginator = new BootstrapPaginatorHelper($view);
    }

    public function testAddClass() {
        // Test with a string
        $opts = [
            'class' => 'class-1'
        ];
        $opts = $this->trait->addClass($opts, '  class-1    class-2  ');
        $this->assertEquals($opts, [
            'class' => 'class-1 class-2'
        ]);
        // Test with an array
        $opts = $this->trait->addClass($opts, ['class-1', 'class-3']);
        $this->assertEquals($opts, [
            'class' => 'class-1 class-2 class-3'
        ]);
    }

    public function testEasyIcon() {

        $that = $this;
        $callback = function($text, $options) use($that) {
            $that->assertEquals(isset($options['escape']) ? $options['escape'] : true,
                                $options['expected']['escape']);
            $that->assertHtml($options['expected']['result'], $text);
        };

        $this->trait->publicEasyIcon($callback, 'i:plus', [
            'expected' => [
                'escape' => false,
                'result' => [['i' => [
                    'class' => 'glyphicon glyphicon-plus',
                    'aria-hidden' => 'true'
                ]], '/i']
            ]
        ]);

        $this->trait->publicEasyIcon($callback, 'Click Me!', [
            'expected' => [
                'escape' => true,
                'result' => 'Click Me!'
            ]
        ]);

        $this->trait->publicEasyIcon($callback, 'i:plus Add', [
            'expected' => [
                'escape' => false,
                'result' => [['i' => [
                    'class' => 'glyphicon glyphicon-plus',
                    'aria-hidden' => 'true'
                ]], '/i', ' Add']
            ]
        ]);

        $this->trait->publicEasyIcon($callback, 'Add i:plus', [
            'expected' => [
                'escape' => false,
                'result' => ['Add ', ['i' => [
                    'class' => 'glyphicon glyphicon-plus',
                    'aria-hidden' => 'true'
                ]], '/i']
            ]
        ]);

        $this->trait->easyIcon = false;
        $this->trait->publicEasyIcon($callback, 'i:plus', [
            'expected' => [
                'escape' => true,
                'result' => 'i:plus'
            ]
        ]);

    }

    public function testHelperMethods() {

        // BootstrapPaginatorHelper - TODO
        // BootstrapPaginatorHelper::prev($title, array $options = []);
        // BootstrapPaginatorHelper::next($title, array $options = []);
        // BootstrapPaginatorHelper::numbers(array $options = []); // For `prev` and `next` options.

        // BootstrapFormatHelper
        $result = $this->form->button('i:plus');
        $this->assertHtml([
            ['button' => [
                'class' => 'btn btn-default',
                'type'  => 'submit'
            ]], ['i' => [
                'class' => 'glyphicon glyphicon-plus',
                'aria-hidden' => 'true'
            ]], '/i', '/button'
        ], $result);
        $result = $this->form->input('fieldname', [
            'prepend' => 'i:home',
            'append'  => 'i:plus',
            'label'   => false
        ]);
        $this->assertHtml([
            ['div' => [
                'class' => 'form-group text'
            ]],
            ['div' => [
                'class' => 'input-group'
            ]],
            ['span' => [
                'class' => 'input-group-addon'
            ]],
            ['i' => [
                'class' => 'glyphicon glyphicon-home',
                'aria-hidden' => 'true'
            ]], '/i',
            '/span',
            ['input' => [
                'type' => 'text',
                'class' => 'form-control',
                'name' => 'fieldname',
                'id' => 'fieldname'
            ]],
            ['span' => [
                'class' => 'input-group-addon'
            ]],
            ['i' => [
                'class' => 'glyphicon glyphicon-plus',
                'aria-hidden' => 'true'
            ]], '/i',
            '/span',
            '/div',
            '/div'
        ], $result);
        //BootstrapFormHelper::prepend($input, $prepend); // For $prepend.
        //BootstrapFormHelper::append($input, $append); // For $append.
    }

};