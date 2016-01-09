<?php
namespace Weirdan\Xdebug\View\Gtk;

use
    Mono\TextEditor,

    Cairo,
    Xwt,

    System\Linq,
    System\IO,
    System\Resources;

use function
    clr_typeof,
    substr,
    round,
    floor;

/**
 * BreakpointTextMarker (ported from monodevelop)
 *
 * @uses TextEditor\MarginMarker
 */
class BreakpointTextMarker extends TextEditor\MarginMarker
{
    protected $_editor = null;

    public static function withEditor($editor)
    {
        $instance = new self;
        $instance->editor = $editor;
        return $instance;
    }

    public function CanDrawBackground(TextEditor\Margin $margin)
    {
        return false;
    }

    public function CanDrawForeground(TextEditor\Margin $margin)
    {
        return $margin instanceof TextEditor\IconMargin;
    }

    public function DrawForeground(TextEditor\TextEditor $editor, Cairo\Context $cr, TextEditor\MarginDrawMetrics $metrics)
    {
        $width = $metrics->Margin->Width;
        $lineWidth = $cr->LineWidth;
        $x = floor($metrics->Margin->XOffset / 2);
        $y = floor($metrics->Y + ($metrics->Height - $width) / 2);
        $this->DrawMarginIcon($cr, $x, $y, $width);
    }

    protected function _streamFromResource($name, $altname)
    {
        $me = clr_typeof('self')->Assembly;
        $resourceStream = $me->GetManifestResourceStream($name);
        $reader = new Resources\ResourceReader($resourceStream);
        $type = $data = null;

        $reader->GetResourceData($altname, $type, $data);
        // and still there are 4 excess bytes
        // representing the length of the resource
        $data = substr($data, 4);
        $stream  = new IO\MemoryStream($data);

        return $stream;
    }

    public function DrawMarginIcon(Cairo\Context $cr, $x, $y, $size)
    {
        $name = "src.View.Gtk.resources.breakpoint.png";
        $image = Xwt\Drawing\Image::FromStream($this->_streamFromResource($name, 'breakpoint.png'));
        $this->DrawImage($cr, $image, $x, $y, $size);
    }

    public function DrawImage(Cairo\Context $cr, Xwt\Drawing\Image $image, $x, $y, $size)
    {
        $num = $size / 2 - $image->Width / 2 + 0.5;
        $num2 = $size / 2 - $image->Height / 2 + 0.5;

        // 1. $cr->DrawImage($this->editor, $image, round($x + $num), round($x + $num2));

        // 2. static readonly Xwt.Toolkit gtkToolkit = Xwt.Toolkit.LoadedToolkits.First (t => t.Type == Xwt.ToolkitType.Gtk);
        //    public static void DrawImage (this Cairo.Context s, Gtk.Widget widget, Xwt.Drawing.Image image, double x, double y)
        //    {
        //       gtkToolkit.RenderImage (widget, s, image, x, y);
        //    }
        //    ---
        //    $kit = Xwt\Toolkit::$LoadedToolkits->First(function($t) {
        //       return $t->Type == Xwt\ToolkitType::Gtk;
        //    });
        //    $kit->RenderImage($this->editor, $cr, $image, round($x + $num), round($y + $num2));


        // that's how you call generic C# extension method in php
        // use a dynamically typed language they said
        // it'll be less typing they said
        $kit = Linq\Enumerable::First<:Xwt\Toolkit:>(Xwt\Toolkit::$LoadedToolkits, function($t) {
            return $t->Type == Xwt\ToolkitType::Gtk;
        });
        $kit->RenderImage($this->editor, $cr, $image, round($x + $num), round($y + $num2));
    }
}
