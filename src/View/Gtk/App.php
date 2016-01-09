<?php
namespace Weirdan\Xdebug\View\Gtk;
use Gtk,
    Xwt,
    Mono\TextEditor,
    System\IO;

use function file_get_contents;

class App
{
    protected $builder;
    protected $rootWindow;

    public static function main()
    {
        (new self)->run();
    }

    protected function _sourceViewOptions()
    {
        $options = new TextEditor\TextEditorOptions();
        $options->EnableSyntaxHighlighting = true;
        $options->ColorScheme = "Solarized Dark";

        return $options;
    }

    public function run()
    {
        Gtk\Application::init();
        Xwt\Application::initialize(Xwt\ToolkitType::Gtk);

        $this->builder = new Gtk\Builder;
        $this->builder->AddFromFile(__DIR__ . '/'. 'resources/interface.ui');

        $this->rootWindow = $this->builder->getObject('rootWnd');

        $this->editor = new TextEditor\TextEditor();

        $this->editor->Text = file_get_contents(__FILE__);
        $this->editor->Document->SyntaxMode = TextEditor\Highlighting\SyntaxMode::read(new IO\FileStream(__DIR__ . '/' . 'resources/PhpSyntaxMode.xml', IO\FileMode::Open));
        $this->editor->Document->ReadOnly = true;
        $this->editor->Options = $this->_sourceViewOptions();

        $placeholder = $this->builder->getObject('scintilla_placeholder');

        $placeholder->add($this->editor);




        $that = $this;
        $markers = [];
        $this->editor->IconMargin->ButtonReleased->Add(function($widget, $args) use ($that, &$markers) {
            if ($args->Button == 1) {
                if (isset($markers[$args->LineNumber])) {
                    $that->editor->Document->RemoveMarker($markers[$args->LineNumber]);
                    unset($markers[$args->LineNumber]);
                } else {
                    $marker = BreakpointTextmarker::withEditor($that->editor);
                    $that->editor->Document->AddMarker($args->LineNumber, $marker);
                    $markers[$args->LineNumber] = $marker;
                }
            }
        });




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
