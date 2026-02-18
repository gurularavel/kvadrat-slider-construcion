<?php

namespace Msc\SliderConstructor\Models;

use Illuminate\Database\Eloquent\Model;

class Slider extends Model
{
    protected $table = 'sliders';

    protected $fillable = [
        'title',
        'width',
        'height',
        'background_color',
        'show_arrows',
        'show_pages',
        'auto_play',
        'stop_auto_play_mouse_over',
        'effects_from_first',
        'slides_data',
    ];

    protected $casts = [
        'slides_data'             => 'array',
        'show_arrows'             => 'boolean',
        'show_pages'              => 'boolean',
        'auto_play'               => 'boolean',
        'stop_auto_play_mouse_over' => 'boolean',
        'effects_from_first'      => 'boolean',
        'width'                   => 'integer',
        'height'                  => 'integer',
    ];

    /**
     * Always returns the single default slider.
     * Creates it on first access.
     */
    public static function getDefault(): self
    {
        return self::first() ?? self::create([
            'title'                   => 'Default Slider',
            'width'                   => config('slider-constructor.default_width', 1200),
            'height'                  => config('slider-constructor.default_height', 500),
            'background_color'        => 'rgb(255,255,255)',
            'show_arrows'             => true,
            'show_pages'              => true,
            'auto_play'               => false,
            'stop_auto_play_mouse_over' => true,
            'effects_from_first'      => false,
            'slides_data'             => [],
        ]);
    }

    /**
     * Returns slider data in camelCase format for JS constructor.
     */
    public function toSliderArray(): array
    {
        return [
            'id'                    => $this->id,
            'title'                 => $this->title,
            'width'                 => $this->width,
            'height'                => $this->height,
            'backgroundColor'       => $this->background_color,
            'showArrows'            => $this->show_arrows,
            'showPages'             => $this->show_pages,
            'autoPlay'              => $this->auto_play,
            'stopAutoPlayMouseOver' => $this->stop_auto_play_mouse_over,
            'effectsFromFirst'      => $this->effects_from_first,
            'data'                  => $this->slides_data ?? [],
        ];
    }
}
