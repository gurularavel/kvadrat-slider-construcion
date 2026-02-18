<?php

namespace Msc\SliderConstructor\Http\Controllers;

use Illuminate\Routing\Controller;
use Msc\SliderConstructor\Models\Slider;

class FrontController extends Controller
{
    public function index()
    {
        $slider = Slider::getDefault();
        $slides = $slider->slides_data ?? [];

        $options = [
            'showArrows'            => $slider->show_arrows,
            'showPages'             => $slider->show_pages,
            'autoPlay'              => $slider->auto_play,
            'stopAutoPlayMouseOver' => $slider->stop_auto_play_mouse_over,
            'effectsFromFirst'      => $slider->effects_from_first,
        ];

        return view('slider-constructor::front.index', compact('slider', 'slides', 'options'));
    }
}
