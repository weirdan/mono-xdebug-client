<?php
namespace Weirdan\Xdebug\View\Gtk;
use Gtk, Xwt;

class App
{
    protected $builder;
    protected $rootWindow;

    public static function main()
    {
        (new self)->run();
    }

    public function run()
    {
        Gtk\Application::init();
        Xwt\Application::initialize(Xwt\ToolkitType::Gtk);

        $this->builder = new Gtk\Builder;
        $this->builder->AddFromFile(__DIR__ . '/'. 'resources/interface.ui');

        $this->rootWindow = $this->builder->getObject('rootWnd');

        $this->rootWindow->Destroyed->add([$this, 'quit']);

        Gtk\Application::run();
    }

    public function quit()
    {
        $that = $this;
        Gtk\Application::invoke(function() use ($that) {

            Gtk\Application::quit();

        });
    }
}
