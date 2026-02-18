<!DOCTYPE html>
<html lang="az">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $slider->title }}</title>

    <link href="{{ asset('vendor/slider/bundle/css/myslider.min.css') }}" rel="stylesheet">
    <link href="{{ asset('vendor/slider/css/icomoon/style.css') }}" rel="stylesheet">
    <link href="{{ asset('vendor/slider/css/icomoon2/style.css') }}" rel="stylesheet">
    <link href="{{ asset('vendor/slider/css/icomoon-custom/style.css') }}" rel="stylesheet">
    <script src="{{ asset('vendor/slider/js/myslider.js') }}"></script>

    <style>
        *, *::before, *::after { box-sizing: border-box; }
        html, body { margin: 0; padding: 0; }
        body { background: #111; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; }

        .slider-section {
            width: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: {{ $slider->height }}px;
        }
        .msc-container { max-width: 100%; overflow: hidden; }

        /* Admin düyməsi — sağ alt küncdə */
        .edit-bar {
            position: fixed; bottom: 16px; right: 16px; z-index: 999;
        }
        .edit-bar a {
            display: inline-flex; align-items: center; gap: 6px;
            background: rgba(28,28,40,.85); backdrop-filter: blur(6px);
            color: #fff; text-decoration: none;
            padding: 8px 16px; border-radius: 30px; font-size: .82rem; font-weight: 600;
            border: 1px solid rgba(255,255,255,.12); transition: background .2s;
        }
        .edit-bar a:hover { background: rgba(124,131,253,.9); }
    </style>
</head>
<body>

<div class="slider-section">
    @if(count($slides) === 0)
        <div style="color:#adb5bd; text-align:center; padding:60px 24px;">
            <div style="font-size:3rem; margin-bottom:12px; opacity:.3;">&#9654;</div>
            <p style="margin:0 0 16px; font-size:1rem;">Slayder hələ boşdur.</p>
            <a href="{{ route('slider-constructor.admin.editor') }}"
               style="color:#7c83fd; text-decoration:none; font-size:.9rem; font-weight:600;">
                Redaktəyə keç &rarr;
            </a>
        </div>
    @else
        <div id="msc_default_slider"
             class="msc-container"
             data-current="0"
             style="width:{{ $slider->width }}px; height:{{ $slider->height }}px;">
            <div class="et-wrapper"
                 style="width:{{ $slider->width }}px; height:{{ $slider->height }}px; background-color:{{ $slider->background_color }};">

                @foreach($slides as $si => $slide)
                    @php
                        if (!empty($slide['image'])) {
                            $slideBg = "url('" . $slide['image'] . "') center/cover no-repeat transparent";
                        } elseif (!empty($slide['color'])) {
                            $slideBg = 'none ' . $slide['color'];
                        } else {
                            $slideBg = 'none rgb(255,255,255)';
                        }
                    @endphp
                    <div class="et-page {{ $si === 0 ? 'et-page-current' : '' }}"
                         data-et-in="{{ $slide['transitionIn'] ?? '' }}"
                         data-et-out="{{ $slide['transitionOut'] ?? '' }}"
                         data-et-delay="{{ $slide['timeDelay'] ?? 5 }}"
                         style="background:{{ $slideBg }};">

                        @foreach($slide['layers'] ?? [] as $layer)
                            @php
                                $lTag   = ($layer['type'] === 'video') ? 'div' : 'a';
                                $lClass = 'msc-layer';
                                if (!empty($layer['link']) && $layer['type'] !== 'video') {
                                    $lClass .= ' msc-link';
                                }
                                $lStyle  = "position:absolute;";
                                $lStyle .= " width:{$layer['width']}px; height:{$layer['height']}px;";
                                $lStyle .= " left:{$layer['left']}px; top:{$layer['top']}px;";
                                $lStyle .= " padding:{$layer['padding']}px;";
                                if (!empty($layer['image'])) {
                                    $lBg = "url('{$layer['image']}') no-repeat center/cover transparent";
                                } elseif (!empty($layer['background'])) {
                                    $lBg = 'none ' . $layer['background'];
                                } else {
                                    $lBg = 'transparent';
                                }
                                $lStyle .= " background:{$lBg};";
                                $lStyle .= " font-size:{$layer['fontSize']}px;";
                                $lStyle .= " font-weight:" . ($layer['bold'] ? 'bold' : 'normal') . ";";
                                $lStyle .= " color:{$layer['textColor']}; text-align:{$layer['textAlign']};";
                                $lStyle .= " line-height:{$layer['lineHeight']};";
                                $lDeco   = $layer['underline'] ? 'underline' : ($layer['lineThrough'] ? 'line-through' : 'none');
                                $lStyle .= " text-decoration:{$lDeco};";
                                $lAttrs  = '';
                                if (!empty($layer['effect'])) {
                                    $lClass .= ' animated';
                                    $lAttrs  = " data-delay=\"{$layer['timeDelay']}\" data-effect=\"{$layer['effect']}\"";
                                }
                                $lHref = ($lTag === 'a')
                                    ? 'href="' . htmlspecialchars($layer['link'] ?? 'javascript:void(0)') . '"'
                                    : '';
                            @endphp
                            <{{ $lTag }} {{ $lHref }} class="{{ $lClass }}" style="{{ $lStyle }}"{!! $lAttrs !!}>
                                @if(!empty($layer['text'])){{ $layer['text'] }}@endif
                                @if($layer['type'] === 'icon' && !empty($layer['icon']))
                                    <span class="{{ $layer['icon'] }}"></span>
                                @endif
                            </{{ $lTag }}>
                        @endforeach
                    </div>
                @endforeach
            </div>
        </div>

        <script>
        (function() { new MySlider('msc_default_slider', @json($options)); })();
        </script>
    @endif
</div>

<div class="edit-bar">
    <a href="{{ route('slider-constructor.admin.editor') }}">&#9998; Redaktə et</a>
</div>

</body>
</html>
