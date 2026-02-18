<?php

namespace Msc\SliderConstructor\Http\Controllers;

use Illuminate\Routing\Controller;
use Msc\SliderConstructor\Models\Slider;

class AdminController extends Controller
{
    public function editor()
    {
        $slider = Slider::getDefault();

        return view('slider-constructor::admin.editor', compact('slider'));
    }
}
