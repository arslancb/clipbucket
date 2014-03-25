/**
 * Slider (or carousel) that supports "Slide", "Crossfade", "Fade",
 * "HTML5 Rotate", "HTML5 Scale" and "Carousel" effects. Flexible, secure
 * for html layout (do not changes structure of HTML) and very simple to use.
 * 
 * The plugin supports Responsive design, images Preloading, Lazy Load and Ajax.
 * Any HTML can be used as slide content (not only images).
 *
 * If the Mousewheel plugin has been included on the page then
 * the slider will also respond to the mouse wheel. (Set "mousewheel" option as "true")
 *
 * @name jQuery ulSlide plugin
 * @license GPL
 * @version 1.5.5
 * @date Dec 02th, 2013
 * @category jQuery plugin
 * @author Yevhen Kotelnytskyi (evgennniy@gmail.com)
 * @copyright (c) 2011 - 2013 Yevhen Kotelnytskyi (http://4coder.info/en/)
 * @example Visit http://4coder.info/en/code/jquery-plugins/ulslide/ for more informations about this jQuery plugin
 */
(function($) {
    // --- CSS Hook "uslTransformRotateY" and "uslTransformTranslateZ"
    function setUslTransform(elem) {
        var uslTransformRotateY = $.data( elem, 'uslTransformRotateY' );
        var uslTransformTranslateZ = $.data( elem, 'uslTransformTranslateZ' );
        
        var transform = '';
        if (uslTransformRotateY !== undefined)
            transform += ' rotateY('+uslTransformRotateY+'deg) ';
            
        if (uslTransformTranslateZ !== undefined)
            transform += ' translateZ('+uslTransformTranslateZ+'px) ';

        $(elem).css({
                    '-webkit-transform': transform,
                    'transform': transform
                });
    }
    
    $.cssHooks.uslTransformRotateY = {
        set: function(elem, val, unit) {
            $.data( elem, 'uslTransformRotateY', parseInt(val) );
            setUslTransform(elem);
        },
        get: function(elem, computed) {
            var uslTransformRotateY = $.data( elem, 'uslTransformRotateY' );
            var val = uslTransformRotateY ? uslTransformRotateY : 0;
            return val;
        }
    };     
    
    $.cssHooks.uslTransformTranslateZ = {
        set: function(elem, val, unit) {
            $.data( elem, 'uslTransformTranslateZ', parseInt(val) );
            setUslTransform(elem);
        },
        get: function(elem, computed) {
            var uslTransformTranslateZ = $.data( elem, 'uslTransformTranslateZ' );
            var val = uslTransformTranslateZ ? uslTransformTranslateZ : 0;
            return val;
        }
    };   
    
    $.fx.step.uslTransformRotateY = function( fx ) {
        $.cssHooks.uslTransformRotateY.set( fx.elem, fx.now, fx.unit);
    };    
    
    $.fx.step.uslTransformTranslateZ = function( fx ) {
        $.cssHooks.uslTransformTranslateZ.set( fx.elem, fx.now, fx.unit);
    };
    // --- end CSS Hook ---
    
    
    // --- Effects ---
    ulslideEffects = {
        //-------------------------------------------------------------------------------
        flip3D: {
            init: function($elem, settings) {
                if (settings['debug'])
                    console.log('ulslideEffects.flip3D.init();'); //***
                
                $elem.css({
                        'overflow': 'visible',
                        'transformStyle': 'preserve-3d'
                    });

                $('> *', $elem[0]).each(function(i) {                                    
                    var styles = {
                            'uslTransformRotateY': '0deg', 
                            'transformStyle': 'preserve-3d',
                            'backface-visibility': 'hidden',
                            '-webkit-backface-visibility': 'hidden',
                            'top': '50%',
                            'left': '50%'
                        };

                    if (i == settings['current'])
                        styles.display = 'block';
                    else styles.display = 'none';

                    $(this).css(styles);
                });
            },
            resize: function($elem, settings) {
                if (settings['debug'])
                    console.log('ulslideEffects.flip3D.resize();'); //***
                
                $elem.css({
                        'perspective': settings['perspective'] + 'px',
                    });

                $('> *', $elem[0]).each(function(i) {                                    
                    var styles = {
                            'margin': ('-' + (settings['height']/2) + 'px 0 0' + '-' + (settings['width']/2) + 'px')
                        };
                    $(this).css(styles);
                });
            },
            slide: function($elem, settings, $prevElem, $currentElem, callback) {
                if (settings['debug'])
                    console.log('ulslideEffects.flip3D.slide();'); //***
                
                if (settings['direction'] == 'f') {
                    var degs = {
                            prev_start: '0deg', 
                            prev_finish: 180,                             
                            current_start: '180deg', 
                            current_finish: 360
                        };
                }
                else {
                    var degs = {
                            prev_start: '360deg', 
                            prev_finish: 180,                             
                            current_start: '180deg', 
                            current_finish: 0
                        };
                }
                
                $prevElem.css({
                        uslTransformRotateY: degs.prev_start, 
                        opacity: 1, 
                        display: 'block'
                    });
                    
                $currentElem.css({
                        uslTransformRotateY: degs.current_start, 
                        opacity: 1, 
                        display: 'block'
                    });

                $prevElem.animate({
                        'uslTransformRotateY': degs.prev_finish, 
                        'opacity': 0
                    },{
                        duration: settings['duration'],
                        easing: settings['easing']
                    });
              
                $currentElem.animate({
                        'uslTransformRotateY': degs.current_finish, 
                        'opacity': 1
                    },{
                        duration: settings['duration'],
                        easing: settings['easing'],
                        complete: function(){
                            callback();
                        }                                   
                    });
            }
        },
        //-------------------------------------------------------------------------------
        cube3D: {
            init: function($elem, settings) {
                if (settings['debug'])
                    console.log('ulslideEffects.cube3D.init();'); //***

                $elem.css({
                        'transformStyle': 'preserve-3d',
                        'overflow': 'visible'
                    });

                $('> *', $elem[0]).each(function(i) {                                    
                    var styles = {
                            'uslTransformRotateY': '0deg', 
                            'transformStyle': 'preserve-3d',
                            'backface-visibility': 'hidden',
                            '-webkit-backface-visibility': 'hidden',
                            'top': '50%',
                            'left': '50%'
                        };

                    if (i == settings['current'])
                        styles.display = 'block';
                    else styles.display = 'none';

                    $(this).css(styles);
                });
            },
            resize: function($elem, settings) {
                if (settings['debug'])
                    console.log('ulslideEffects.cube3D.resize();'); //***
                
                var scale = (settings['perspective'] - settings['width'] / 2) / settings['perspective'];
                $elem.css({
                        'perspective': settings['perspective'] + 'px',
                        'transform': 'scale('+scale+')',
                        '-webkit-transform': 'scale('+scale+')'
                    });

                $('> *', $elem[0]).each(function(i) {                                    
                    var styles = {
                            'uslTransformTranslateZ': (settings['width']/2) + 'px',
                            'margin': ('-' + (settings['height']/2) + 'px 0 0' + '-' + (settings['width']/2) + 'px')
                        };
                    $(this).css(styles);
                });
            },
            slide: function($elem, settings, $prevElem, $currentElem, callback) {
                if (settings['debug'])
                    console.log('ulslideEffects.cube3D.slide();'); //***
                
                if (settings['direction'] == 'f') {
                    var degs = {
                            prev_start: '0deg', 
                            prev_finish: 90,                             
                            current_start: '-90deg', 
                            current_finish: 0
                        };
                }
                else {
                    var degs = {
                            prev_start: '0deg', 
                            prev_finish: -90,                             
                            current_start: '90deg', 
                            current_finish: 0
                        };
                }
                
                $prevElem.css({
                        uslTransformRotateY: degs.prev_start, 
                        display: 'block'
                    });
                    
                $currentElem.css({
                        uslTransformRotateY: degs.current_start, 
                        display: 'block'
                    });

                $prevElem.animate({
                        'uslTransformRotateY': degs.prev_finish
                    },{
                        duration: settings['duration'],
                        easing: settings['easing']
                    });
              
                $currentElem.animate({
                        'uslTransformRotateY': degs.current_finish
                    },{
                        duration: settings['duration'],
                        easing: settings['easing'],
                        complete: callback                                
                    });
            }
        },
        //-------------------------------------------------------------------------------
        slide: {
            init: function($elem, settings) {
                if (settings['debug'])
                    console.log('ulslideEffects.slide.init();'); //***
            },
            resize: function($elem, settings) {
                if (settings['debug'])
                    console.log('ulslideEffects.slide.resize();'); //***
            },
            slide: function($elem, settings, $prevElem, $currentElem, callback) {
                if (settings['debug'])
                    console.log('ulslideEffects.slide.slide();'); //***
                
                var axis = settings['effect']['axis'];
                
                // Randomize axis
                if (axis == 'r')
                    axis = $elem.array_rand(['x', 'y']); 
                
                if (axis == 'x') {
                    if (settings['prev'] != settings['current']){
                        if (settings['direction'] == 'f'){
                            $prevElem.animate({
                                'left': -(settings['width'] + settings['effect']['distance'])
                            }, settings['duration'], settings['easing']);
                            $currentElem.css('left', settings['width'] + settings['effect']['distance']);
                        }
                        else{
                            $prevElem.animate({
                                'left': settings['width'] + settings['effect']['distance']
                            }, settings['duration'], settings['easing']);
                            $currentElem.css('left', -(settings['width'] + settings['effect']['distance']));
                        }
                    }
                    
                    $currentElem.css('top', 0);
                    $prevElem.css('top', 0);
                    
                    $currentElem.animate({
                        'left': 0
                    }, settings['duration'], settings['easing'], callback
                    );
                }
                else {
                    if (settings['prev'] != settings['current']) {
                        if (settings['direction'] == 'f') {
                            $prevElem.animate({
                                'top': $elem[0].currentHeight + settings['effect']['distance']
                            }, settings['duration'], settings['easing'], function() {
                                $prevElem.css('left', -(settings['width'] + settings['effect']['distance']));
                            });
                            $currentElem.css('top', -(settings['prevHeight'] + settings['effect']['distance']));
                        }
                        else {
                            $prevElem.animate({
                                'top': -($elem[0].currentHeight + settings['effect']['distance'])
                            }, settings['duration'], settings['easing'], function(){
                                $prevElem.css('left', -(settings['width'] + settings['effect']['distance']));
                            });
                            $currentElem.css('top', settings['prevHeight'] + settings['effect']['distance']);
                        }
                    }
                    
                    $currentElem.css('left', 0);
                    $prevElem.css('left', 0);
                    
                    $currentElem.animate({ 'top': 0 }, 
                                        settings['duration'], 
                                        settings['easing'], 
                                        callback);
                }
            }
        },
        //-------------------------------------------------------------------------------
        fade: {
            init: function($elem, settings) {
                if (settings['debug'])
                    console.log('ulslideEffects.fade.init();'); //***
            },
            resize: function($elem, settings) {
                if (settings['debug'])
                    console.log('ulslideEffects.fade.resize();'); //***
            },
            slide: function($elem, settings, $prevElem, $currentElem, callback) {
                if (settings['debug'])
                    console.log('ulslideEffects.fade.slide();'); //***

                $currentElem.css('display', 'none');
                $currentElem.css('left', 0);
                $currentElem.css('top', 0);

                $prevElem.fadeOut(settings['duration'], function(){
                    $prevElem.css('display', 'none');
                    $currentElem.fadeIn(settings['duration'], callback);
                });
            }
        },
        //-------------------------------------------------------------------------------
        crossfade: {
            init: function($elem, settings) {
                if (settings['debug'])
                    console.log('ulslideEffects.crossfade.init();'); //***
            },
            resize: function($elem, settings) {
                if (settings['debug'])
                    console.log('ulslideEffects.crossfade.resize();'); //***
            },
            slide: function($elem, settings, $prevElem, $currentElem, callback) {
                if (settings['debug'])
                    console.log('ulslideEffects.crossfade.slide();'); //***
                    
                $currentElem.css('display', 'none');
                $currentElem.css('left', 0);
                $currentElem.css('top', 0);

                $prevElem.fadeOut(settings['duration'], function(){
                    $prevElem.css('display', 'none');
                });
                $currentElem.fadeIn(settings['duration'], callback);  
            }
        },
        //-------------------------------------------------------------------------------
        rotate: {
            init: function($elem, settings) {
                if (settings['debug'])
                    console.log('ulslideEffects.rotate.init();'); //***
                    
                $elem.css('overflow', 'visible');
            },
            resize: function($elem, settings) {
                if (settings['debug'])
                    console.log('ulslideEffects.rotate.resize();'); //***
            },
            slide: function($elem, settings, $prevElem, $currentElem, callback) {
                if (settings['debug'])
                    console.log('ulslideEffects.rotate.slide();'); //***
                    
                var rotate_pref = settings['direction'] == 'f' ? '-' : '';
                
                $currentElem.animate({
                                        'rotate': rotate_pref + '90deg', 
                                        'scale': '0.01', 
                                        'opacity': 0.3, 
                                        'z-index': 2, 
                                        'left': 0, 
                                        'top': 0
                                    }, 0);
                                    
                $prevElem.css('z-index', 1);

                $prevElem.animate({
                                    'opacity': 0
                                }, 
                                settings['duration'], 
                                settings['easing']);
                                
                $currentElem.animate({
                                        'rotate': rotate_pref + '360deg', 
                                        'scale': '1', 
                                        'opacity': 1
                                    }, 
                                    settings['duration'], 
                                    settings['easing'], 
                                    callback);
            }
        },
        //-------------------------------------------------------------------------------
        scale: {
            init: function($elem, settings) {
                if (settings['debug'])
                    console.log('ulslideEffects.scale.init();'); //***
            },
            resize: function($elem, settings) {
                if (settings['debug'])
                    console.log('ulslideEffects.scale.resize();'); //***
            },
            slide: function($elem, settings, $prevElem, $currentElem, callback) {
                if (settings['debug'])
                    console.log('ulslideEffects.scale.slide();'); //***
                    
                if (settings['direction'] == 'f') {
                    var rotate_pref =  '-';
                    var rotate_pref_i =  '';
                }
                else {
                    var rotate_pref = '';
                    var rotate_pref_i = '-';
                }

                $currentElem.animate({
                                        'scale': '0.05', 
                                        'opacity': 0.3, 
                                        'z-index': 2, 
                                        'left': 0, 
                                        'top': 0, 
                                        'marginLeft': rotate_pref_i + (settings['fwidth']/2)+'px'
                                    }, 0);
                                    
                $prevElem.css('z-index', 1);

                $prevElem.animate({
                                    'scale': '0.01', 
                                    'opacity': 0, 
                                    'marginLeft': rotate_pref + (settings['fwidth']/2)+'px'
                                }, 
                                settings['duration'], 
                                settings['easing']);

                $currentElem.animate({
                                        'scale': '1', 
                                        'opacity': 1, 
                                        'marginLeft': '0px'
                                    }, 
                                    settings['duration'], 
                                    settings['easing'], 
                                    callback);
            }
        },
        //-------------------------------------------------------------------------------
        carousel: {
            init: function($elem, settings) {
                if (settings['debug'])
                    console.log('ulslideEffects.carousel.init();'); //***
                
                $('> *', $elem[0]).each(function(i) {
                    var ci = $elem.carouselGetFramePos(i, settings['current']);
                    if (settings['effect']['axis'] == 'y') {
                        $(this).css({
                                    'top': (ci * settings['fheight']),
                                    'left': '0'
                                });
                    }
                    else {
                        $(this).css({
                                    'top': '0',
                                    'left': (ci * settings['fwidth'])
                                });
                    }
                });
                
                if (settings['effect']['axis'] == 'y') {
                    $elem.css('width', settings['width']);
                    $elem.css('height', settings['fheight'] * settings['effect']['showCount'] - settings['effect']['distance']);
                }
                else {
                    $elem.css('width', settings['fwidth'] * settings['effect']['showCount'] - settings['effect']['distance']);
                    $elem.css('height', settings['height']);
                }
            },
            resize: function($elem, settings) {
                if (settings['debug'])
                    console.log('ulslideEffects.carousel.resize();'); //***
                    
                if (settings['effect']['axis'] == 'y') {
                    $elem.css('width', settings['width']);
                    $elem.css('height', settings['fheight'] * settings['effect']['showCount'] - settings['effect']['distance']);
                }
                else {
                    $elem.css('width', settings['fwidth'] * settings['effect']['showCount'] - settings['effect']['distance']);
                    $elem.css('height', settings['height']);
                }
                
                $('> *', $elem[0]).each(function(i) {
                    var ci = $elem.carouselGetFramePos(i, settings['current']);
                    if (settings['effect']['axis'] == 'y') {
                        $(this).css({
                                    'top': (ci * settings['fheight']),
                                    'left': '0'
                                });
                    }
                    else {
                        $(this).css({
                                    'top': '0',
                                    'left': (ci * settings['fwidth'])
                                });
                    }
                });
            },
            slide: function($elem, settings, $prevElem, $currentElem, callback) {
                if (settings['debug'])
                    console.log('ulslideEffects.carousel.slide();'); //***
                    
                $('> *', $elem[0]).each(function(i){
                    liel = $(this);
                    var ci = $elem.carouselGetFramePos(i, settings['current']);
                    if (settings['direction'] == 'f')
                         var pi = $elem.carouselGetFramePos(i, settings['current'] - 1);
                    else var pi = $elem.carouselGetFramePos(i, settings['current'] + 1);

                    if (settings['effect']['axis'] == 'y') {
                        if ((settings['direction'] == 'f') && (ci == 0)) {
                            liel.css('top', (-1 * settings['fheight']));
                            liel.animate({'top': ci * settings['fheight']}, settings['duration'], settings['easing']);
                        }
                        else if ((settings['direction'] == 'f') && (pi + 1 == settings['effect']['showCount'])) {
                            liel.animate({'top': (settings['effect']['showCount']) * settings['fheight']}, settings['duration'], settings['easing']);
                        }
                        else if ((settings['direction'] == 'b') && (pi == 0)) {
                            liel.animate({'top': -1 * settings['fheight']}, settings['duration'], settings['easing']);
                        }
                        else if ((settings['direction'] == 'b') && (ci + 1 == settings['effect']['showCount'])) {
                            liel.css('top', (ci + 1) * settings['fheight']);
                            liel.animate({'top': ci * settings['fheight']}, settings['duration'], settings['easing']);
                        }
                        else {
                            if ((ci < settings['effect']['showCount']) && (ci >= 0)) {
                                liel.animate({'top': ci * settings['fheight']}, settings['duration'], settings['easing']);
                            }
                            else {
                                liel.css('top', (ci * settings['fheight']));
                            }
                        }
                    }
                    else {
                        if ((settings['direction'] == 'f') && (ci == 0)) {
                            liel.css('left', (-1 * settings['fwidth']));
                            liel.animate({'left': ci * settings['fwidth']}, settings['duration'], settings['easing']);
                        }
                        else if ((settings['direction'] == 'f') && (pi + 1 == settings['effect']['showCount'])) {
                            liel.animate({'left': (settings['effect']['showCount']) * settings['fwidth']}, settings['duration'], settings['easing']);
                        }
                        else if ((settings['direction'] == 'b') && (pi == 0)) {
                            liel.animate({'left': -1 * settings['fwidth']}, settings['duration'], settings['easing']);
                        }
                        else if ((settings['direction'] == 'b') && (ci + 1 == settings['effect']['showCount'])) {
                            liel.css('left', (ci + 1) * settings['fwidth']);
                            liel.animate({'left': ci * settings['fwidth']}, settings['duration'], settings['easing']);
                        }
                        else {
                            if ((ci < settings['effect']['showCount']) && (ci >= 0)) {
                                liel.animate({'left': ci * settings['fwidth']}, settings['duration'], settings['easing']);
                            }
                            else {
                                liel.css('left', (ci * settings['fwidth']));
                            }
                        }
                    }

                    setTimeout(callback, settings['duration'] + 100);
                });
            }
        },
        //-------------------------------------------------------------------------------
        test: {
            init: function($elem, settings) {
                if (settings['debug'])
                    console.log('ulslideEffects.test.init();'); //***
            },
            resize: function($elem, settings) {
                if (settings['debug'])
                    console.log('ulslideEffects.test.resize();'); //***
            },
            slide: function($elem, settings, $prevElem, $currentElem, callback) {
                if (settings['debug'])
                    console.log('ulslideEffects.test.slide();'); //***
            }
        }
        //-------------------------------------------------------------------------------
    };
    // --- end Effects ---
    
    
    // --- jQuery plugin ---
    $.fn.ulslide = function(settings) {
        var thisObj = this;
        if (thisObj.length == 0) return false;
        var thisEl = thisObj[0];
        if (! $(thisEl).attr('id')) {
            if (ulslide_last_id == undefined)
                ulslide_last_id = 0;
            ulslide_last_id ++;
            $(thisEl).attr('id', 'ulslide-' + ulslide_last_id);
        }
        var id = $(thisEl).attr('id');

        // Settings
        settings = $.extend({
            effect: {
                type: 'slide', // slide, fade, crossfade, flip3D, cube3D or carousel (use showCount for carousel)
                axis: 'x',     // x, y, r (r - random)
                distance: 20   // Distance between frames
            },
            perspective: 1000, // Perspective for 3D effects
            duration: 600,     // Changing duration
            direction: 'f',    // f, b
            autoslide: false,  // Autoscrolling interval (ms)
            current: 0,

            canResize: false,
            width: thisObj.width(),
            height: 'auto',    // pixels or 'auto'

            statusbar: true,
            loader: '',
            loadClass: 'usl-loading',
            
            loadTimeout: 5000, // images loading timeout
            lazyload: false,   // testing
            ajax: false,

            mousewheel: false, // Scroll on "mousewheel"

            // Selectors:
            pager: false,
            nextButton: false,
            prevButton: false,
            printCurrentTo: false,

            //framesOnPage: 2,

            onAnimateStart: function(settings, thisEl){},
            onAnimate: function(settings, thisEl){},
            onAjaxStart: function(settings, thisEl){},
            onAjaxStop: function(settings, thisEl){},

            beforeResize: function(settings, thisEl){},
            onResize: function(settings, thisEl){},

            debug: false

        },settings);

        // Deprecated Options
        if (typeof settings['affect']        != 'undefined') settings['effect']['type']     = settings['affect'];
        if (typeof settings['axis']          != 'undefined') settings['effect']['axis']     = settings['axis'];
        if (typeof settings['padding']       != 'undefined') settings['effect']['distance'] = settings['padding'];
        if (typeof settings['navigator']     != 'undefined') settings['pager']              = settings['navigator'];
        if (typeof settings['print_current'] != 'undefined') settings['printCurrentTo']     = settings['print_current'];
        if (typeof settings['bnext']         != 'undefined') settings['nextButton']         = settings['bnext'];
        if (typeof settings['bprev']         != 'undefined') settings['prevButton']         = settings['bprev'];
        // end Deprecated Options

        if (settings['canResize'] && settings['height'] == 'auto') 
            settings['height'] = thisObj.height();
        
        if (typeof settings['effect']['distance'] == 'undefined') 
            settings['effect']['distance'] = 20;
            
        settings['fwidth'] = settings['width'] + settings['effect']['distance'];
        settings['fheight'] = settings['height'] + settings['effect']['distance'];
        settings['prev'] = settings['current'];
        settings['count'] = $('> *', thisObj).length;

        if (settings['lazyload']) {
            $('img', thisObj).each(function(i){
                var img = $(this);
                img.attr('rel', img.attr('src'));
                if (i > 0) {
                    img.removeAttr('src');
                }
            });
        }

        thisObj.carouselGetFramePos = function (i, current) {
            if (i >= settings['effect']['showCount'] - current) {
                var l = settings['count'] - settings['effect']['showCount'];
                var ci = (i + current - settings['effect']['showCount']) - l;
                return ci;
            }
            else return i + current;
        };
        
        // Initialize elements
        $('> *', thisObj).each(function(i){
            var liel = $(this);
            liel.addClass('slide-node slide-node-'+i);
            
            liel.css({
                    'position': 'absolute',
                    'margin': '0',
                    'width': settings['width'],
                    'overflow': 'hidden'
                });

            if (i == settings['current']) {
                liel.css({
                        'top': '0',
                        'left': '0'
                    });
            }
            else {
                liel.css({
                        'top': '0',
                        'left': -(settings['width'] + settings['effect']['distance'])
                    });
            }
        });
        
        // Initialize container
        thisObj.css({
                    'list-style': 'none',
                    'position': 'relative',
                    'padding': 0,
                    'overflow': 'hidden'
                });

        if (! settings['canResize']) {
            thisObj.css('width', settings['width']);

            if (settings['height'] == 'auto'){
                thisObj.css('height', $('> *:eq('+settings['current']+')', thisObj).height());
            }
            else thisObj.css('height', settings['height']);

            settings['prevHeight'] = settings['height'];
        }

        // Initialize effect
        var effect = ulslideEffects[settings['effect']['type']];
        if (effect != undefined) {
            effect.init(thisObj, settings);
            effect.resize(thisObj, settings);            
        }
        // end Initialize

        function resize(){
            var size = settings['beforeResize'](settings, thisEl); // event

            if (size && size.width && size.height) {
                settings['width'] = size.width;
                settings['height'] = size.height;
            }
            else {
                settings['width'] = thisObj.width();
                settings['height'] = thisObj.height();
            }

            //console.log('resize ' + settings['width'] + ' x ' + settings['height']); //*****
            settings['fwidth'] = settings['width'] + settings['effect']['distance'];
            settings['fheight'] = settings['height'] + settings['effect']['distance'];


            //thisObj.css('width', settings['width']);

            if (settings['height'] == 'auto'){
                thisObj.css('height', $('> *:eq('+settings['current']+')', thisObj).height());
                
                console.log('resize auto height: ' +  $('> *:eq('+settings['current']+')', thisObj).height()); //*****
            }
            //else thisObj.css('height', settings['height']);

            settings['prevHeight'] = settings['height'];

            /* elements */
            $('> *', thisObj).each(function(i){
                var liel = $(this);
                liel.addClass('slide-node slide-node-'+i);
                liel.css('position', 'absolute');
                liel.css('margin', '0');
                liel.css('distance', '0');
                liel.css('width', settings['width']);
                liel.css('overflow', 'hidden');
                
                if (settings['effect']['type'] != 'carousel') {
                    if (i == settings['current']){
                        liel.css('top', '0');
                        liel.css('left', '0');
                    }
                    else{
                        liel.css('top', '0');
                        liel.css('left', -(settings['width'] + settings['effect']['distance']));
                    }
                    
                    if (settings['height'] == 'auto'){
                        liel.css('height', $('> *:eq('+settings['current']+')', thisObj).height());
                    }
                    else liel.css('height', settings['height']);
                }
            });
            
            // Resize effect
            if (effect != undefined) {
                effect.resize(thisObj, settings);
            }
            // end Resize effect

            settings['onResize'](settings, thisEl); // event
        }
        
        thisEl.getSlide = function getSlide(num) {
            return $('> *:eq('+num+')', thisEl);
        };

        thisEl.array_rand = function(arg) {
            return arg[Math.floor(Math.random() * arg.length)];
        };

        function next() {
            var c = thisEl.uslCurrent();
            settings['direction'] = 'f';
            if (c + 1 < settings['count']) {
                thisEl.uslRefresh(c + 1);
            } else {

                thisEl.uslRefresh(0);
            }
        }

        function prev() {
            var c = thisEl.uslCurrent();
            settings['direction'] = 'b';
            if (c > 0) {
                thisEl.uslRefresh(c - 1);
            } else {
                thisEl.uslRefresh(settings['count'] - 1);
            }
        }

        if (settings['height'] == 'auto')
            thisEl.currentHeight = thisEl.getSlide(settings['current']).height();
        else thisEl.currentHeight = settings['height'];

        thisEl.uslCurrent = function(new_value){
            if (new_value == undefined){
                return settings['current'];
            }
            else {
                var old = thisEl.uslCurrent();
                var c = new_value;

                settings['current'] = new_value;
                return new_value;
            }
        };

        thisEl.autoslideNext = function(){
            /*if (settings['direction'] == 'f') next();
            else prev();*/
            next();
        };

        thisEl.initAutoslide = function(){

            if (settings['TimeoutID']) clearTimeout(settings['TimeoutID']);
            settings['TimeoutID'] = setTimeout("jQuery('#"+$(thisEl).attr('id')+"')[0].autoslideNext()", settings['autoslide']);

            if (settings['debug'])
                console.log('initAutoslide: ' + settings['TimeoutID']);
        };

        thisEl.clearAutoslide = function(){
            if (settings['TimeoutID']) {
                clearTimeout(settings['TimeoutID']);
            }
        };

        thisEl.uslRefresh = function(slide_index, fast, callback){
            if (settings['debug'])
                console.log('uslRefresh()');

            if (! thisEl.ready) {
                if (settings['debug'])
                    console.log('uslRefresh / ' + settings['id'] + ': ! thisEl.ready');

                setTimeout("jQuery('#"+$(thisEl).attr('id')+"')[0].uslRefresh()", 400);
                return;
            }
            if (settings['LoadTimeoutID']) clearTimeout(settings['LoadTimeoutID']);
            thisEl.ready = false;

            if (typeof(slide_index) != 'undefined') {
                thisEl.uslCurrent(slide_index);
            }

            thisEl.clearAutoslide();
            var prev = thisEl.getSlide(settings['prev']);
            var current = thisEl.getSlide(settings['current']);
            current.css('display', 'block');

            function doRefresh() {
                if (settings['debug'])
                    console.log('doRefresh()');

                settings['onAnimateStart'](settings, thisEl); // notification
                //console.log(settings['id'] + ': doRefresh');

                if (settings['height'] == 'auto') {
                    thisEl.currentHeight = thisEl.getSlide(settings['current']).height();
                    settings['prevHeight'] = thisEl.getSlide(settings['prev']).height();
                }

                function finish_animate() {
                    if (settings['debug'])
                        console.log('finish_animate(): start');

                    if (settings['printCurrentTo']) {
                        $(settings['printCurrentTo']).html(settings['current'] + 1);
                    }

                    if ((settings['prev'] != settings['current']) && (settings['effect']['type'] != 'carousel') ) {
                        prev.css('display', 'none');
                    }

                    if (settings['height'] == 'auto') {
                        thisObj.animate({
                            'height': thisEl.currentHeight
                        }, 250/*, function() { alert(settings['id'] + ': finish_animate()' + thisEl.currentHeight); }*/);
                    }

                    //settings['prev'] = settings['current'];
                    if (settings['debug'])
                        console.log('finish_animate(): autoslide = ' + settings['autoslide']);

                    if (settings['autoslide']) thisEl.initAutoslide();
                    settings['onAnimate'](settings, thisEl); // notification
                    settings['prev'] = settings['current'];
                    thisEl.uslRefreshClasses();
                    thisEl.ready = true;

                    if (typeof callback != 'undefined') callback();
                }

                if (settings['prev'] == settings['current']) {
                    finish_animate();
                    return;
                }

                // Call effect
                if (effect != undefined) {
                    effect.slide(thisObj, settings, prev, current, finish_animate);
                }
            }

            if (settings['ajax']) {
                settings['onAjaxStart'](settings, thisEl); // notification
                var statusbar_loaded = thisEl.getSlide(settings['current'])[0].usl_ajax_loaded;

                thisEl.uslAjaxLoadSlide(settings['current'], function() {
                    settings['onAjaxStop'](settings, thisEl); // notification
                    doRefresh();
                })
            }
            else {
                if (settings['lazyload']) {
                    var $imgToLoad = $('img', current[0]);

                    $imgToLoad.each(function(i){
                        var img = $(this);
                        img.attr('src', img.attr('rel'));
                    });

                    settings['z_img_count'] = $imgToLoad.length;
                    settings['z_img_loaded'] = 0;
                    $imgToLoad.each(function(){
                        if (this.complete) {
                            settings['z_img_loaded'] ++;
                        }
                        else {
                            $(this).load(function(){
                                settings['z_img_loaded'] ++;
                                if (settings['z_img_loaded'] == settings['z_img_count']){
                                    doRefresh();
                                }
                            });
                        }
                    });

                    if (settings['z_img_loaded'] == settings['z_img_count']){
                        doRefresh();
                    }
                    return;
                }

                doRefresh();
            }
        };

        thisEl.uslAjaxLoadSlide = function(slide_num, callback) {
            var current = thisEl.getSlide(slide_num);

            if (current[0].usl_ajax_loaded) {
                callback();
            }
            else {
                var url = $(settings['pager']).eq(slide_num).attr('href');
                current[0].usl_ajax_loaded = true;
                current.load(url + '?ajax=1', false, callback);
            }
        };

        thisEl.uslRefreshClasses = function(){
            if (settings['count'] > 1){
                if (settings['nextButton']) $(settings['nextButton']).addClass('active');
                if (settings['prevButton']) $(settings['prevButton']).addClass('active');
            }
            if (settings['pager']){
                $(settings['pager']).removeClass('usl-current');
                $(settings['pager'] + '.usl-pager-'+thisEl.uslCurrent()).addClass('usl-current');
                $(settings['pager']).parent().removeClass('usl-current-parent');
                $(settings['pager'] + '.usl-pager-'+thisEl.uslCurrent()).parent().addClass('usl-current-parent');
            }
        };

        if (settings['nextButton']){
            $(settings['nextButton']).click(function(){
                next();
                return false;
            });
        }

        if (settings['prevButton']){
            $(settings['prevButton']).click(function(){
                prev();
                return false;
            });
        }

        function setNavigator(s_navigator) {
            var pager = $(s_navigator);
            pager.each(function(index){
                this.usl_navigator_index = index;
                $(this).addClass('usl-pager-' + index);
            });

            pager.click(function(){
                var c = this.usl_navigator_index;
                if ((c < settings['count']) && (c != thisEl.uslCurrent())) {
                    //thisEl.uslCurrent(c);
                    if (c > thisEl.uslCurrent()) settings['direction'] = 'f';
                    else settings['direction'] = 'b';
                    thisEl.uslRefresh(c);
                }
                return false;
            });
        }

        if (settings['pager']){
            setNavigator(settings['pager']);
        }
        if (settings['navigator2']){
            setNavigator(settings['navigator2']);
        }

        function loadingStatus(loading) {
            if (loading) {
                thisObj.addClass('usl-loading');
                thisObj.parent().addClass('usl-parent-loading');
                
                if (settings['loader']) {
                    jQuery(settings['loader']).addClass(settings['loadClass']);
                }
            }
            else {
                thisObj.removeClass('usl-loading');
                thisObj.parent().removeClass('usl-parent-loading');
                
                if (settings['loader']) {
                    jQuery(settings['loader']).removeClass(settings['loadClass']);
                }
            }
        }

        thisEl.uslStatusbar = function() {

            function isImageLoaded(img) {
                if (!img.complete) {
                    return false;
                }
                if (typeof img.naturalWidth !== 'undefined' && img.naturalWidth === 0) {
                    return false;
                }
                return true;
            }

            if (settings['lazyload']) {
                var $imgToLoad = $('>li:eq('+settings['current']+') img', thisEl);
            }
            else {
                var $imgToLoad = $('img', thisEl);
            }

            settings['img_count'] = $imgToLoad.length;
            if (settings['img_count']) {
                loadingStatus(true);
            }

            settings['img_loaded'] = 0;
            $imgToLoad.each(function(){

                if (isImageLoaded(this)) {
                    settings['img_loaded'] ++;
                    if (settings['debug'])
                        console.log($(this).attr('src') + ' loaded');
                }
                else {
                    $(this).load(function(){
                        settings['img_loaded'] ++;

                        if (settings['debug'])
                            console.log('Img LOAD / ' + settings['img_loaded'] + ' of ' + settings['img_count']);

                        if (settings['img_loaded'] == settings['img_count']){
                            loadingStatus(false);
                            thisEl.ready = true;
                            thisEl.uslRefresh();
                        }
                    });
                    if (settings['debug'])
                        console.log($(this).attr('src') + ' NOT loaded');
                }
            });

            if (settings['debug'])
                console.log('uslStatusbar() / ' + settings['img_loaded'] + ' of ' + settings['img_count']);

            if (settings['img_loaded'] == settings['img_count']){
                loadingStatus(false);
                thisEl.ready = true;
                thisEl.uslRefresh();
            }

            settings['LoadTimeoutID'] = setTimeout(function(){
                                            loadingStatus(false);
                                            thisEl.ready = true;
                                            thisEl.uslRefresh();
                                        }, settings['loadTimeout']);
        };

        // statusbar
        if (settings['statusbar'] && !settings['ajax']){
            thisEl.uslStatusbar();
        }

        /*
         * If the mousewheel plugin has been included on the page then
         * the slider will also respond to the mouse wheel.
         */
        if (settings['mousewheel']) {
            thisObj.bind(
                'mousewheel',
                function (event, delta) {
                    if (thisEl.ready) {
                        if (delta < 0) {
                            next();
                        }
                        else {
                            prev();
                        }
                    }
                    return false;
                });
        }

        if (settings['canResize']){
            thisObj.on('resize', resize);
            jQuery(window).on('resize', resize);
            resize();            
        }
                
        if (! settings['statusbar'] || settings['ajax']) {
            thisEl.ready = true;
            thisEl.uslRefresh();
        }
    };
})(jQuery); 
// --- end jQuery plugin ---