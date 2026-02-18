<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="shortcut icon" href="{{ asset('vendor/slider/img/favicon.ico') }}" type="image/x-icon">
    <title>Slider Constructor — Editor</title>

    <link href="{{ asset('vendor/slider/css/bootstrap-custom/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('vendor/slider/bundle/css/myslider_constructor.min.css') }}" rel="stylesheet">
    <link href="{{ asset('vendor/slider/css/icomoon/style.css') }}" rel="stylesheet">
    <link href="{{ asset('vendor/slider/css/icomoon2/style.css') }}" rel="stylesheet">
    <link href="{{ asset('vendor/slider/css/icomoon-custom/style.css') }}" rel="stylesheet">
    <style>
        .top-bar {
            position: fixed; top: 0; left: 0; right: 0; z-index: 1000;
            background: #343a40; padding: 6px 16px;
            display: flex; align-items: center; gap: 12px;
            border-bottom: 2px solid #495057;
        }
        .top-bar .brand { color: #fff; font-size: .95rem; font-weight: 700; }
        .top-bar .brand span { color: #7c83fd; }
        body { padding-top: 46px; }
        /* Single-slider mode: hide New/Open dropdown */
        .dropdown:has(.button-open) { display: none !important; }
    </style>

    <script src="{{ asset('vendor/slider/lib/jquery/dist/jquery.js') }}"></script>
    <script src="{{ route('slider-constructor.admin.api') }}?a=jsLang"></script>
    <script src="{{ asset('vendor/slider/bundle/js/myslider_constructor.min.js') }}"></script>
</head>
<body>

<div class="top-bar">
    <span class="brand">Slider<span>Constructor</span></span>
    <a href="{{ route('slider-constructor.front') }}" target="_blank"
       class="ml-auto btn btn-sm btn-outline-info" style="font-size:.8rem;">
        View Site &rarr;
    </a>
</div>

<div id="msc-app" class="app-container"></div>

@php
$lang = [
    'apply'                => 'Apply',          'save'                 => 'Save',
    'save_as_new'          => 'Save as new',     'close'                => 'Close',
    'new'                  => 'New',             'open'                 => 'Open',
    'logout'               => 'Log out',         'slide_new'            => 'New slide',
    'slide_copy'           => 'Copy slide',      'slide_delete'         => 'Delete slide',
    'slide_animation_play' => 'Play slide animation',
    'empty'                => 'Empty',           'layers'               => 'Layers',
    'layer_add'            => 'Add layer',       'animation_in'         => 'Animation - in',
    'animation_out'        => 'Animation - out', 'no_animation'         => 'No animation',
    'delay'                => 'Delay',           'color'                => 'Color',
    'image'                => 'Image',           'upload'               => 'Upload',
    'layer_options'        => 'Layer options',   'layer_type'           => 'Layer type',
    'layer_type_text'      => 'Text',            'layer_type_image'     => 'Image',
    'layer_type_video'     => 'Video',           'layer_type_icon'      => 'Icon',
    'layer_copy'           => 'Copy layer',      'layer_delete'         => 'Delete layer',
    'layer_show_hide'      => 'Show / hide',     'effect_n_delay'       => 'Effect and delay',
    'no_effect'            => 'No effect',       'text'                 => 'Text',
    'font_n_size'          => 'Font and size',   'text_style'           => 'Text style',
    'line_height'          => 'Line height',     'icon'                 => 'Icon',
    'choose'               => 'Choose',          'choose_icon'          => 'Choose icon',
    'padding'              => 'Padding',         'text_color'           => 'Text color',
    'background_color'     => 'Background color','link'                 => 'Link',
    'link_video'           => 'Video URL',       'source'               => 'Source',
    'name'                 => 'Name',            'show_arrows'          => 'Show arrows',
    'effects_from_first'   => 'Play effects from the first slide',
    'pages_nav'            => 'Pages navigation','auto_play'            => 'Auto play',
    'stop_play_mouseover'  => 'Stop auto play on mouse over',
    'delete'               => 'Delete',          'preview'              => 'Preview',
    'confirm_delete_slider'=> 'Are you sure you want to delete this slider?',
    'alert_no_slides'      => 'No slides to save.',
    'without_text'         => 'Without text',
];
@endphp

{{-- ── Backbone.js Templates ── --}}
<script type="text/template" id="msc-layout-template">
    <div class="app-container-inner">
        <div class="card mb-3" id="msc-container">
            <div class="card-header">
                <div class="row">
                    <div class="col-md-8">
                        <div class="row form-group">
                            <div class="col-4 no-padding-right">
                                <input class="form-control input-sm" type="number" name="slider_width" value="" min="150" step="1">
                            </div>
                            <div class="col-4 no-padding-right">
                                <input class="form-control input-sm" type="number" name="slider_height" value="" min="150" step="1">
                            </div>
                            <div class="col-4">
                                <button class="btn btn-outline-primary button-slider-accept" data-toggle="tooltip" title="{{ $lang['apply'] }}">
                                    <i class="icon-checkmark3"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 text-md-right text-xs-center">
                        <button class="btn btn-outline-success margin-horizontal button-slider-save">
                            <i class="icon-checkmark3"></i> {{ $lang['save'] }}
                        </button>
                        <div class="dropdown display-inline-block margin-horizontal">
                            <button type="button" class="btn btn-outline-primary button-open" data-toggle="dropdown">
                                <i class="icon-list"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-right">
                                <a class="dropdown-item button-slider-new" href="#"><i class="icon-file"></i> {{ $lang['new'] }}</a>
                                <a class="dropdown-item button-slider-open" href="#"><i class="icon-folder"></i> {{ $lang['open'] }}</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="clearfix"></div>
            </div>
            <div class="card-body p-0">
                <div class="msc-slides-wrapper">
                    <div class="msc-slides-container" id="msc-slides-container" style="min-width: 700px;">
                        <div class="inner et-wrapper" style="width: 700px; height: 350px;">
                            <div class="msc-slide et-page et-page-current"></div>
                        </div>
                    </div>
                    <div class="msc-right-panel" id="msc-right-panel"></div>
                </div>
            </div>
            <div class="card-footer" id="msc-slide-placeholder-container">
                <div class="row">
                    <div class="col-5 col-sm-5 col-md-7 list-container"></div>
                    <div class="col-7 col-sm-7 col-md-5 msc-slides-buttons-container">
                        <div class="msc-slides-buttons">
                            <button type="button" class="btn btn-outline-success button-play-slide" data-toggle="tooltip" title="{{ $lang['slide_animation_play'] }}">
                                <i class="icon-play"></i>
                            </button>
                            <span class="divider-vert"></span>
                            <button type="button" class="btn btn-outline-primary button-add-slide" data-toggle="tooltip" title="{{ $lang['slide_new'] }}">
                                <i class="icon-plus3"></i>
                            </button>
                            <button type="button" class="btn btn-outline-primary button-copy-slide" data-toggle="tooltip" title="{{ $lang['slide_copy'] }}">
                                <i class="icon-copy"></i>
                            </button>
                            <button type="button" class="btn btn-outline-danger button-remove-slide" data-toggle="tooltip" title="{{ $lang['slide_delete'] }}">
                                <i class="icon-cancel3"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-6"><div id="msc-options-layer"></div></div>
            <div class="col-lg-6"><div id="msc-layer-placeholder-wrapper"></div></div>
        </div>
    </div>
    <div id="msc-modals"></div>
</script>

<script type="text/template" id="msc-layer-placeholder-empty-template">
    <div class="alert alert-warning text-xs-center" role="alert">{{ $lang['empty'] }}</div>
</script>

<script type="text/template" id="msc-modal-template">
    <div class="modal fade">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title"><%- modalTitle %></h4>
                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                </div>
                <div class="modal-body max-height max-height400"></div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ $lang['close'] }}</button>
                </div>
            </div>
        </div>
    </div>
</script>

<script type="text/template" id="msc-modal-submit-template">
    <div class="modal fade">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title"><%- modalTitle %></h4>
                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                </div>
                <div class="modal-body max-height max-height400"></div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary button-submit" data-dismiss="modal">{{ $lang['save'] }}</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ $lang['close'] }}</button>
                </div>
            </div>
        </div>
    </div>
</script>

<script type="text/template" id="msc-modal-slider-save-template">
    <div class="modal fade">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title"><%- modalTitle %></h4>
                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                </div>
                <div class="modal-body max-height"></div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary button-submit" data-dismiss="modal">{{ $lang['save'] }}</button>
                    <button type="button" class="btn btn-outline-primary button-submit-new">{{ $lang['save_as_new'] }}</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ $lang['close'] }}</button>
                </div>
            </div>
        </div>
    </div>
</script>

<script type="text/template" id="msc-icon-button-template">
    <i class="<%- name %>" title="<%- name %>"></i>
</script>

<script type="text/template" id="msc-layer-placeholder-collection-template">
    <div class="card" id="msc-layer-placeholder-container">
        <div class="card-header"><h4 class="card-title">{{ $lang['layers'] }}</h4></div>
        <div class="card-body">
            <div class="form-group">
                <button class="btn btn-outline-primary btn-block button-add-layer">
                    <i class="icon-plus3"></i> {{ $lang['layer_add'] }}
                </button>
            </div>
            <div class="list-container"></div>
        </div>
    </div>
</script>

<script type="text/template" id="msc-layer-placeholder-template">
    <div class="card-body p-2">
        <div class="row">
            <div class="col-2 col-sm-2 col-md-1 placeholder-icon"><i class="icon-resize rotate-45"></i></div>
            <div class="col-6 col-sm-6 col-md-7 card-text"><div class="ellipsis"><%- placeholderText %></div></div>
            <div class="col-4 col-sm-4 col-md-4 text-xs-right msc-layer-buttons-container">
                <div class="msc-layer-buttons">
                    <button type="button" class="btn btn-outline-primary btn-sm show-on-hover button-layer-copy" data-toggle="tooltip" title="{{ $lang['layer_copy'] }}"><i class="icon-copy"></i></button>
                    <button type="button" class="btn btn-outline-danger btn-sm show-on-hover button-layer-remove" data-toggle="tooltip" title="{{ $lang['layer_delete'] }}"><i class="icon-cancel3"></i></button>
                    <button type="button" class="btn btn-outline-primary btn-sm button-layer-visible" data-toggle="tooltip" title="{{ $lang['layer_show_hide'] }}">
                        <% if( isVisible ){ %><i class="icon-eye2"></i><% } else { %><i class="icon-eye-slash"></i><% } %>
                    </button>
                </div>
            </div>
        </div>
    </div>
</script>

<script type="text/template" id="msc-options-slide-template">
    <div class="msc-right-panel-b shadow-20">
        <div class="inner">
            <div id="msc-options-wrapper">
                <div class="row form-group">
                    <div class="col-md-4"><label for="options-slide-color">{{ $lang['color'] }}</label></div>
                    <div class="col-md-8">
                        <input type="text" class="form-control form-control-sm input-color option-control" name="color" data-validate="color-rgb" id="options-slide-color">
                    </div>
                </div>
                <div class="row form-group">
                    <div class="col-md-4"><label>{{ $lang['image'] }}</label></div>
                    <div class="col-md-8">
                        <button type="button" class="btn btn-secondary btn-sm btn-block button-upload">
                            <i class="icon-picture"></i> {{ $lang['upload'] }}
                        </button>
                        <input type="file" name="image" accept="image/gif,image/jpeg,image/png" style="display:none;">
                    </div>
                </div>
                <div class="row form-group">
                    <div class="col-md-4"><label for="options-slide-transition-in">{{ $lang['animation_in'] }}</label></div>
                    <div class="col-md-8">
                        <select class="form-control form-control-sm option-control" name="transitionIn" id="options-slide-transition-in">
                            <option value="">{{ $lang['no_animation'] }}</option>
                            @foreach(['rotateSlideIn','rotateSidesIn','rotateCarouselBottomIn','rotateCarouselTopIn','rotateCarouselRightIn','rotateCarouselLeftIn','rotateCubeBottomIn','rotateCubeTopIn','rotateCubeRightIn','rotateCubeLeftIn','rotateRoomBottomIn','rotateRoomTopIn','rotateRoomRightIn','rotateRoomLeftIn','rotateUnfoldBottom','rotateUnfoldTop','rotateUnfoldRight','rotateUnfoldLeft','moveFromTopFade','moveFromLeftFade','moveFromRightFade','moveFromBottomFade','rotatePullTop','rotatePullBottom','rotatePullLeft','rotatePullRight','moveFromTop','moveFromBottom','moveFromLeft','moveFromRight','scaleUp','scaleUpDown','scaleUpCenter','flipInTop','flipInBottom','flipInLeft','flipInRight','rotateInNewspaper'] as $t)
                                <option value="{{ $t }}">{{ $t }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="row form-group">
                    <div class="col-md-4"><label for="options-slide-transition-out">{{ $lang['animation_out'] }}</label></div>
                    <div class="col-md-8">
                        <select class="form-control form-control-sm option-control" name="transitionOut" id="options-slide-transition-out">
                            <option value="">{{ $lang['no_animation'] }}</option>
                            @foreach(['rotateSlideOut','rotateSidesOut','rotateCarouselBottomOut','rotateCarouselTopOut','rotateCarouselRightOut','rotateCarouselLeftOut','rotateCubeBottomOut','rotateCubeTopOut','rotateCubeRightOut','rotateCubeLeftOut','rotateRoomBottomOut','rotateRoomTopOut','rotateRoomRightOut','rotateRoomLeftOut','rotateFoldBottom','rotateFoldTop','rotateFoldRight','rotateFoldLeft','moveToBottomFade','moveToTopFade','moveToRightFade','moveToLeftFade','rotatePushBottom','rotatePushTop','rotatePushRight','rotatePushLeft','moveToBottom','moveToTop','moveToRight','moveToLeft','scaleDown','scaleDownUp','flipOutBottom','flipOutTop','flipOutLeft','flipOutRight','fade','rotateOutNewspaper'] as $t)
                                <option value="{{ $t }}">{{ $t }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="row form-group">
                    <div class="col-md-4"><label for="options-slide-delay">{{ $lang['delay'] }}</label></div>
                    <div class="col-md-8">
                        <input type="number" class="form-control form-control-sm option-control" name="timeDelay" value="0" min="0" id="options-slide-delay">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="button-toggle">
        <button class="btn btn-secondary" type="button"><i class="icon"></i></button>
    </div>
</script>

<script type="text/template" id="msc-options-layer-template">
    <div class="card">
        <div class="card-header"><h4 class="card-title">{{ $lang['layer_options'] }}</h4></div>
        <div class="card-body msc-options-container">
            <form action="" method="post">
                <div class="row form-group">
                    <div class="col-md-4"><label><b>{{ $lang['layer_type'] }}</b></label></div>
                    <div class="col-md-8 form-group">
                        <select class="form-control option-control" name="type">
                            <option value="text">{{ $lang['layer_type_text'] }}</option>
                            <option value="image">{{ $lang['layer_type_image'] }}</option>
                            <option value="video">{{ $lang['layer_type_video'] }}</option>
                            <option value="icon">{{ $lang['layer_type_icon'] }}</option>
                        </select>
                    </div>
                </div>
                <div class="row form-group">
                    <div class="col-md-4"><label>{{ $lang['effect_n_delay'] }}</label></div>
                    <div class="col-md-5 form-group">
                        <select class="form-control form-control-sm option-control" name="effect">
                            <option value="">{{ $lang['no_effect'] }}</option>
                            @foreach(['bounce','flash','pulse','rubberBand','shake','headShake','swing','tada','wobble','jello','bounceIn','bounceInDown','bounceInLeft','bounceInRight','bounceInUp','fadeIn','fadeInDown','fadeInDownBig','fadeInLeft','fadeInLeftBig','fadeInRight','fadeInRightBig','fadeInUp','fadeInUpBig','flipInX','flipInY','lightSpeedIn','rotateIn','rotateInDownLeft','rotateInDownRight','rotateInUpLeft','rotateInUpRight','hinge','rollIn','zoomIn','zoomInDown','zoomInLeft','zoomInRight','zoomInUp','slideInDown','slideInLeft','slideInRight','slideInUp'] as $e)
                                <option value="{{ $e }}">{{ $e }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <input type="number" class="form-control form-control-sm option-control" name="timeDelay" value="0" min="0" step="0.2">
                    </div>
                </div>
                <% if( type == 'text' ){ %>
                <div class="row form-group">
                    <div class="col-md-4"><label>{{ $lang['text'] }}</label></div>
                    <div class="col-md-8"><textarea class="form-control form-control-sm option-control" name="text" cols="20" rows="3"></textarea></div>
                </div>
                <div class="row form-group">
                    <div class="col-md-4"><label>{{ $lang['font_n_size'] }}</label></div>
                    <div class="col-md-5 form-group">
                        <select class="form-control form-control-sm option-control" name="font">
                            <option value="Arial, Helvetica, sans-serif">Arial</option>
                            <option value="'Arial Black', Gadget, sans-serif">Arial Black</option>
                            <option value="Impact, Charcoal, sans-serif">Impact</option>
                            <option value="Tahoma, Geneva, sans-serif">Tahoma</option>
                            <option value="Verdana, Geneva, sans-serif">Verdana</option>
                            <option value="Georgia, serif">Georgia</option>
                            <option value="'Times New Roman', Times, serif">Times New Roman</option>
                            <option value="'Courier New', Courier, monospace">Courier New</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select class="form-control form-control-sm option-control" name="fontSize">
                            @foreach([16,18,20,22,24,28,32,36,40,46,52,72,80,100] as $fs)
                                <option value="{{ $fs }}">{{ $fs }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="row form-group">
                    <div class="col-md-4"><label>{{ $lang['text_style'] }}</label></div>
                    <div class="col-md-8">
                        <div class="btn-group" data-toggle="buttons">
                            <label class="btn btn-secondary btn-sm option-label"><input type="radio" name="textAlign" value="left"><i class="icon-align-left"></i></label>
                            <label class="btn btn-secondary btn-sm option-label"><input type="radio" name="textAlign" value="center"><i class="icon-align-center"></i></label>
                            <label class="btn btn-secondary btn-sm option-label"><input type="radio" name="textAlign" value="right"><i class="icon-align-right"></i></label>
                            <label class="btn btn-secondary btn-sm option-label"><input type="checkbox" name="bold" value="on"><i class="icon-bold"></i></label>
                            <label class="btn btn-secondary btn-sm option-label"><input type="checkbox" name="underline" value="on"><i class="icon-underline"></i></label>
                            <label class="btn btn-secondary btn-sm option-label"><input type="checkbox" name="italic" value="on"><i class="icon-italic"></i></label>
                            <label class="btn btn-secondary btn-sm option-label"><input type="checkbox" name="lineThrough" value="on"><i class="icon-strikethrough"></i></label>
                        </div>
                    </div>
                </div>
                <div class="row form-group">
                    <div class="col-md-4"><label>{{ $lang['line_height'] }}</label></div>
                    <div class="col-md-8"><input type="range" class="option-control" name="lineHeight" value="1.2" min="0.5" max="5" step="0.2"></div>
                </div>
                <% } %>
                <% if( _.contains(['icon'], type) ){ %>
                <div class="row form-group">
                    <div class="col-md-4"><label>{{ $lang['icon'] }}</label></div>
                    <div class="col-md-8">
                        <button type="button" class="btn btn-secondary btn-sm btn-block button-icons-select">
                            <i class="icon-list"></i> {{ $lang['choose'] }}
                        </button>
                    </div>
                </div>
                <% } %>
                <% if( _.contains(['text', 'icon'], type) ){ %>
                <div class="row form-group">
                    <div class="col-md-4"><label>{{ $lang['padding'] }}</label></div>
                    <div class="col-md-8"><input type="range" class="option-control" name="padding" value="0" min="0" max="70" step="1"></div>
                </div>
                <div class="row form-group">
                    <div class="col-md-4"><label>{{ $lang['text_color'] }}</label></div>
                    <div class="col-md-8"><input type="text" class="form-control form-control-sm input-color option-control" name="textColor" data-validate="color-rgb"></div>
                </div>
                <div class="row form-group">
                    <div class="col-md-4"><label>{{ $lang['background_color'] }}</label></div>
                    <div class="col-md-8"><input type="text" class="form-control form-control-sm input-color-bg option-control" name="background" data-validate="color-rgb"></div>
                </div>
                <% } %>
                <% if( _.contains(['image'], type) ){ %>
                <div class="row form-group">
                    <div class="col-md-4"><label>{{ $lang['image'] }}</label></div>
                    <div class="col-md-8">
                        <button type="button" class="btn btn-secondary btn-sm btn-block button-upload">
                            <i class="icon-picture"></i> {{ $lang['upload'] }}
                        </button>
                        <input type="file" name="image" accept="image/gif,image/jpeg,image/png" style="display:none;">
                    </div>
                </div>
                <% } %>
                <div class="row form-group">
                    <div class="col-md-4">
                        <label><% if( _.contains(['video'], type) ){ %>{{ $lang['link_video'] }}<% } else { %>{{ $lang['link'] }}<% } %></label>
                    </div>
                    <div class="col-md-8"><input type="text" class="form-control form-control-sm option-control" name="link" data-validate="link"></div>
                </div>
                <% if( type != 'icon' ){ %>
                <div class="row form-group">
                    <div class="col-md-4"><label>{{ $lang['source'] }}</label></div>
                    <div class="col-md-8"><input type="text" class="form-control form-control-sm option-control" name="source"></div>
                </div>
                <% } %>
            </form>
        </div>
    </div>
</script>

<script type="text/template" id="msc-save-form-template">
    <form action="" method="post">
        <div class="row form-group">
            <div class="col-md-4"><label>{{ $lang['name'] }}</label></div>
            <div class="col-md-8"><input type="text" class="form-control" name="name" value="<%- title %>" required></div>
        </div>
        <div class="row form-group">
            <div class="col-md-4"><label>{{ $lang['background_color'] }}</label></div>
            <div class="col-md-8"><input type="text" class="form-control form-control-sm input-color-bg" name="backgroundColor" value="<%- backgroundColor %>"></div>
        </div>
        <div class="row form-group">
            <div class="col-md-8 ml-auto">
                <label class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input" name="arrows" value="1"<% if( showArrows ){ %> checked<% } %>>
                    <span class="custom-control-indicator"></span>
                    <span class="custom-control-description">{{ $lang['show_arrows'] }}</span>
                </label>
            </div>
        </div>
        <div class="row form-group">
            <div class="col-md-8 ml-auto">
                <label class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input" name="pages" value="1"<% if( showPages ){ %> checked<% } %>>
                    <span class="custom-control-indicator"></span>
                    <span class="custom-control-description">{{ $lang['pages_nav'] }}</span>
                </label>
            </div>
        </div>
        <div class="row form-group">
            <div class="col-md-8 ml-auto">
                <label class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input" name="autoPlay" value="1"<% if( autoPlay ){ %> checked<% } %>>
                    <span class="custom-control-indicator"></span>
                    <span class="custom-control-description">{{ $lang['auto_play'] }}</span>
                </label>
            </div>
        </div>
        <div class="row form-group">
            <div class="col-md-8 ml-auto">
                <label class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input" name="stopAutoPlayMouseOver" value="1"<% if( stopAutoPlayMouseOver ){ %> checked<% } %>>
                    <span class="custom-control-indicator"></span>
                    <span class="custom-control-description">{{ $lang['stop_play_mouseover'] }}</span>
                </label>
            </div>
        </div>
        <div class="row form-group">
            <div class="col-md-8 ml-auto">
                <label class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input" name="effectsFromFirst" value="1"<% if( effectsFromFirst ){ %> checked<% } %>>
                    <span class="custom-control-indicator"></span>
                    <span class="custom-control-description">{{ $lang['effects_from_first'] }}</span>
                </label>
            </div>
        </div>
    </form>
</script>

<script type="text/template" id="msc-layer-template">
    <div class="inner"><%- text %></div>
</script>

<script type="text/template" id="msc-slider-files-container">
    <table class="table table-bordered table-hover">
        <colgroup><col width="15%"><col width="40%"><col width="20%"><col width="25%"></colgroup>
        <tbody></tbody>
    </table>
</script>

<script type="text/template" id="msc-slider-file-template">
    <td><small><%- id %></small></td>
    <td><% if( isActive ){ %><i class="icon-checkmark3"></i><% } %> <span><%- title %></span></td>
    <td><small><%- mtime %></small></td>
    <td class="text-xs-right">
        <button type="button" class="btn btn-sm btn-outline-danger button-file-remove" data-toggle="tooltip" title="{{ $lang['delete'] }}">
            <i class="icon-cancel3"></i>
        </button>
    </td>
</script>

<script>
    var underscore = _.noConflict();

    var app = new App({
        container:     '#msc-app',
        baseUrl:       '{{ asset('vendor/slider') }}/',
        controllerUrl: '{{ route('slider-constructor.admin.api') }}',
        lang:          window.msc_lang,
    });
    app.start();

    // Auto-load the default slider on startup
    $(document).ready(function () {
        setTimeout(function () {
            if (app && app.core) {
                app.core.clearData();
                setTimeout(function () {
                    app.core.openFile({{ $slider->id }});
                }, 300);
            }
        }, 800);
    });
</script>

</body>
</html>
