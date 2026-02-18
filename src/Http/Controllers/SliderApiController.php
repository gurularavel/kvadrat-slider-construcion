<?php

namespace Msc\SliderConstructor\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Msc\SliderConstructor\Models\Slider;

class SliderApiController extends Controller
{
    public function dispatch(Request $request): JsonResponse
    {
        $action = $request->query('a', '');

        return match ($action) {
            'getfiles' => $this->getFiles(),
            'save'     => $this->save($request),
            'item'     => $request->isMethod('delete')
                            ? $this->deleteItem($request)
                            : $this->getItem($request),
            'jsLang'   => $this->jsLang(),
            default    => response()->json(['error' => 'Unknown action'], 400),
        };
    }

    private function getFiles(): JsonResponse
    {
        $slider = Slider::getDefault();

        return response()->json([
            'success' => true,
            'result'  => [$slider->toSliderArray()],
        ]);
    }

    private function getItem(Request $request): JsonResponse
    {
        $slider = Slider::getDefault();

        return response()->json([
            'success' => true,
            'result'  => $slider->toSliderArray(),
        ]);
    }

    private function save(Request $request): JsonResponse
    {
        $raw   = $request->getContent();
        $input = json_decode($raw, true);

        if (json_last_error() !== JSON_ERROR_NONE || !is_array($input)) {
            return response()->json(['error' => 'Invalid JSON'], 400);
        }

        $fields = [
            'title'                   => $input['title']                 ?? 'Default Slider',
            'width'                   => (int) ($input['width']          ?? 1200),
            'height'                  => (int) ($input['height']         ?? 500),
            'background_color'        => $input['backgroundColor']       ?? 'rgb(255,255,255)',
            'show_arrows'             => (bool) ($input['showArrows']    ?? true),
            'show_pages'              => (bool) ($input['showPages']     ?? true),
            'auto_play'               => (bool) ($input['autoPlay']      ?? false),
            'stop_auto_play_mouse_over' => (bool) ($input['stopAutoPlayMouseOver'] ?? true),
            'effects_from_first'      => (bool) ($input['effectsFromFirst'] ?? false),
            'slides_data'             => $input['data']                  ?? [],
        ];

        $slider = Slider::getDefault();
        $slider->update($fields);

        return response()->json([
            'success' => true,
            'result'  => array_merge($input, ['id' => $slider->id]),
        ]);
    }

    private function deleteItem(Request $request): JsonResponse
    {
        $slider = Slider::getDefault();
        $slider->update([
            'slides_data' => [],
        ]);

        return response()->json(['success' => true]);
    }

    private function jsLang(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'result'  => [
                'newSlider'    => 'New Slider',
                'openSlider'   => 'Open Slider',
                'save'         => 'Save',
                'addSlide'     => 'Add Slide',
                'addLayer'     => 'Add Layer',
                'delete'       => 'Delete',
                'duplicate'    => 'Duplicate',
                'settings'     => 'Settings',
                'preview'      => 'Preview',
                'text'         => 'Text',
                'image'        => 'Image',
                'icon'         => 'Icon',
                'video'        => 'Video',
                'background'   => 'Background',
                'animation'    => 'Animation',
                'link'         => 'Link',
                'width'        => 'Width',
                'height'       => 'Height',
                'color'        => 'Color',
                'fontSize'     => 'Font Size',
                'bold'         => 'Bold',
                'italic'       => 'Italic',
                'underline'    => 'Underline',
                'align'        => 'Align',
                'delay'        => 'Delay',
                'transition'   => 'Transition',
                'autoPlay'     => 'Auto Play',
                'showArrows'   => 'Show Arrows',
                'showPages'    => 'Show Pages',
            ],
        ]);
    }
}
