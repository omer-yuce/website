/*
* Initialize Modules
*/
;(function($, window, document, undefined){

    $( window ).on( 'elementor:init', function() {
		
		// Add "master-addons" specific css class to elementor body
        $('.elementor-editor-active').addClass('master-addons');

        // Make our custom css visible in the panel's front-end
        if( typeof elementorPro == 'undefined' ) {
            elementor.hooks.addFilter( 'editor/style/styleText', function( css, view ){
                var model = view.getEditModel(),
                    customCSS = model.get( 'settings' ).get( 'custom_css' );

                if ( customCSS ) {
                    css += customCSS.replace( /selector/g, '.elementor-element.elementor-element-' + view.model.id );
                }
                return css;
            });
        }
        // End of Custom CSS	
        
        var JltmaControlBaseDataView = elementor.modules.controls.BaseData;


        /*!
         * ================== Visual Select Controller ===================
         **/
        var JltmaControlVisualSelectItemView = JltmaControlBaseDataView.extend( {
            onReady: function() {
                this.ui.select.jltmaVisualSelect();
            },
            onBeforeDestroy: function() {
                this.ui.select.jltmaVisualSelect( 'destroy' );
            }
        } );
        elementor.addControlView( 'jltma-visual-select', JltmaControlVisualSelectItemView );


        
        // Enables the live preview for Animation Tranistions in Elementor Editor
        function jltmaOnGlobalOpenEditorForTranistions ( panel, model, view ) {
            view.listenTo( model.get( 'settings' ), 'change', function( changedModel ){

                // Force to re-render the element if the Entrance Animation enabled for first time
                if( '' !== model.getSetting('ma_el_animation_name') && !view.$el.hasClass('ma-el-animated') ){
                    view.render();
                    view.$el.addClass('ma-el-animated');
                    view.$el.addClass('ma-el-animated-once');
                }

                // Check the changed setting value
                for( settingName in changedModel.changed ) {
                    if ( changedModel.changed.hasOwnProperty( settingName ) ) {

                        // Replay the animation if an animation option changed (except the animation name)
                        if( settingName !== "ma_el_animation_name" && -1 !== settingName.indexOf("ma_el_animation_") ){

                            // Reply the animation
                            view.$el.removeClass( model.getSetting('ma_el_animation_name') );

                            setTimeout( function() {
                                view.$el.addClass( model.getSetting('ma_el_animation_name') );
                            }, ( model.getSetting('ma_el_animation_delay') || 300 ) ); // Animation Delay
                        }
                    }
                }

            }, view );
        }
        elementor.hooks.addAction( 'panel/open_editor/section', jltmaOnGlobalOpenEditorForTranistions );
        elementor.hooks.addAction( 'panel/open_editor/column' , jltmaOnGlobalOpenEditorForTranistions );
        elementor.hooks.addAction( 'panel/open_editor/widget' , jltmaOnGlobalOpenEditorForTranistions );

        

        // Start of Transform
           // ma_el_transform_angle
            // ma_el_transform_rotate_x
            // ma_el_transform_rotate_y
            // ma_el_transform_translate_x
            // ma_el_transform_translate_y
            // ma_el_transform_translate_z
            // ma_el_transform_scale
            // ma_el_transform_origin_x
            // ma_el_transform_origin_y
            // ma_el_transform_perspective
            // ma_el_transform_perspective_origin_x
            // ma_el_transform_perspective_origin_y
        
        var ControlMultipleBaseItemView = elementor.modules.controls.BaseMultiple,
        ControlTransformsItemView;
    
        ControlTransformsItemView = ControlMultipleBaseItemView.extend( {
            ui: function() {
                var ui = ControlMultipleBaseItemView.prototype.ui.apply( this, arguments );
                ui.controls = '.elementor-slider-input > input:enabled';
                ui.sliders = '.elementor-slider';
                ui.link = 'button.reset-controls';
                //ui.colors = '.elementor-shadow-color-picker';
    
                return ui;
            },
            events: function() {
                return _.extend( ControlMultipleBaseItemView.prototype.events.apply( this, arguments ), {
                    'slide @ui.sliders': 'onSlideChange',
                    'click @ui.link': 'onLinkResetTransforms'
                } );
            },
    
            defaultTransformsValue: {
                'angle': 0,
                'rotate_x': 0,
                'rotate_y': 0,
                'scale': 1,
                'translate_x': 0,
                'translate_y': 0,
                'translate_z': 0
            },
            onLinkResetTransforms: function( event ) {
                event.preventDefault();
                event.stopPropagation();
    
    
                this.ui.controls.val('');
    
                this.updateTransformationsValue();
            },
            onSlideChange: function( event, ui ) {
                var type = event.currentTarget.dataset.input,
                    $input = this.ui.input.filter( '[data-setting="' + type + '"]' );
    
                $input.val( ui.value );
    
                //this.setValue( type, ui.value );
                //this.fillEmptyTransformations();
                //
                this.updateTransformations();
            },
            /*onBeforeDestroy: function() {
    
                this.$el.remove();
            }*/
            initSliders: function() {
                var _this = this;
                var value = this.getControlValue();
    
                _this.ui.sliders.each( function(index, slider) {
                    var $slider = jQuery( this ),
                        $input = $slider.next( '.elementor-slider-input' ).find( 'input' );
                        //alert(elementor.config.version);
                        console.log(elementor.config.version);
                        if (elementor.config.version < '2.5') {
                         $slider.slider( {
                                value: value[ this.dataset.input ],
                                min: +$input.attr( 'min' ),
                                max: +$input.attr( 'max' ),
                                step: +$input.attr( 'step' )
                            } );
                        } else {
                            var sliderInstance = noUiSlider.create(slider, {
                                start: [value[slider.dataset.input]],
                                step: 1,
                                range: {
                                    min: +$input.attr('min'),
                                    max: +$input.attr('max')
                                },
                                format: {
                                    to: function to(sliderValue) {
                                        return +sliderValue.toFixed(1);
                                    },
                                    from: function from(sliderValue) {
                                        return +sliderValue;
                                    }
                                }
                            });
    
                            sliderInstance.on('slide', function (values) {
                                var type = sliderInstance.target.dataset.input;
    
                                $input.val(values[0]);
    
                                _this.setValue(type, values[0]);
                                _this.updateTransformations();
                            });
                        }
                } );
    
    
            },
            onReady: function() {
                this.initSliders();
                //this.updateTransformations();
            },
    
            updateTransformations: function() {
                this.fillEmptyTransformations();
                this.updateTransformationsValue();
            },
            fillEmptyTransformations: function() {
                var transformations = this.getPossibleTransformations(),
    
                    $controls = this.ui.controls,
                    $sliders = this.ui.sliders,
                    defaultTransformsValue = this.defaultTransformsValue;
    
                transformations.forEach( function( transform, index ) {
                    var $slider = $sliders.filter( '[data-input="' + transform + '"]' );
                    var $element = $controls.filter( '[data-setting="' + transform + '"]' );
    
                    if ( $element.length && _.isEmpty( $element.val() ) ) {
                        $element.val( defaultTransformsValue[transform] );
                        if (elementor.config.version < '2.5') {
                            $slider.slider( 'value', defaultTransformsValue[transform] );
                        } else {
                            $slider[0].noUiSlider.set( defaultTransformsValue[transform] );
                        }
    
                    }
    
                } );
            },
            updateTransformationsValue: function() {
                var currentValue = {},
                    transformations = this.getPossibleTransformations(),
                    $controls = this.ui.controls,
                    $sliders = this.ui.sliders,
                    defaultTransformsValue = this.defaultTransformsValue;
    
                transformations.forEach( function( transform ) {
                    var $element = $controls.filter( '[data-setting="' + transform + '"]' );
    
                    currentValue[ transform ] = $element.length ? $element.val() : defaultTransformsValue;
    
                    var $slider = $sliders.filter( '[data-input="' + transform + '"]' );
                    if (elementor.config.version < '2.5') {
                        $slider.slider( 'value', $element.length ? $element.val() : defaultTransformsValue );
                    } else {
                        $slider[0].noUiSlider.set($element.length ? $element.val() : defaultTransformsValue);
                    }
                } );
    
                this.setValue( currentValue );
            },
    
            getPossibleTransformations: function() {
                return [
                    'angle',
                    'rotate_x',
                    'rotate_y',
                    'scale',
                    'translate_x',
                    'translate_y',
                    'translate_z'
                    ];
            },
            onInputChange: function( event ) {
                var inputSetting = event.target.dataset.setting;
    
                var type = event.currentTarget.dataset.setting,
                    $slider = this.ui.sliders.filter( '[data-input="' + type + '"]' );
    
                    if (elementor.config.version < '2.5') {
                        $slider.slider( 'value', this.getControlValue( type ) );
                    } else {
                            $slider[0].noUiSlider.set(this.getControlValue(type));
                    }
    
                this.updateTransformations();
            },
    
        });
        elementor.addControlView( 'transforms', ControlTransformsItemView );        
        // End of Transform



        // Start of XY Positions

        var ControlMultipleBaseItemView = elementor.modules.controls.BaseMultiple,
        ControlXY_PositionsItemView;
    
        ControlXY_PositionsItemView = ControlMultipleBaseItemView.extend( {
            ui: function() {
                var ui = ControlMultipleBaseItemView.prototype.ui.apply( this, arguments );
                ui.controls = '.elementor-slider-input > input:enabled';
                ui.sliders = '.elementor-slider';
                ui.link = 'button.reset-controls';
                //ui.colors = '.elementor-shadow-color-picker';
    
                return ui;
            },
            events: function() {
                return _.extend( ControlMultipleBaseItemView.prototype.events.apply( this, arguments ), {
                    'slide @ui.sliders': 'onSlideChange',
                    'click @ui.link': 'onLinkResetXYPositions'
                } );
            },
    
            defaultXYPositionsValue: {
                'x': 0,
                'y': 0,
            },
            onLinkResetXYPositions: function( event ) {
                event.preventDefault();
                event.stopPropagation();
    
    
                this.ui.controls.val('');
    
                this.updateXYPositionsValue();
            },
    
            onSlideChange: function( event, ui ) {
                var type = event.currentTarget.dataset.input,
                    $input = this.ui.input.filter( '[data-setting="' + type + '"]' );
    
                $input.val( ui.value );
    
                //this.setValue( type, ui.value );
                //this.fillEmptyXYPositions();
    
                this.updateXYPositions();
            },
            /*onBeforeDestroy: function() {
    
                this.$el.remove();
            }*/
            initSliders: function() {
                var _this = this;
                var value = this.getControlValue();
    
                this.ui.sliders.each( function(index, slider) {
                    var $slider = jQuery( this ),
                        $input = $slider.next( '.elementor-slider-input' ).find( 'input' );
    
                        if (elementor.config.version < '2.5') {
                            $slider.slider( {
                                value: value[ this.dataset.input ],
                                min: +$input.attr( 'min' ),
                                max: +$input.attr( 'max' ),
                                step: +$input.attr( 'step' )
                            } );
                        } else {
                            var sliderInstance = noUiSlider.create(slider, {
                                start: [value[slider.dataset.input]],
                                step: 1,
                                range: {
                                    min: +$input.attr('min'),
                                    max: +$input.attr('max')
                                },
                                format: {
                                    to: function to(sliderValue) {
                                        return +sliderValue.toFixed(1);
                                    },
                                    from: function from(sliderValue) {
                                        return +sliderValue;
                                    }
                                }
                            });
    
    
                        sliderInstance.on('slide', function (values) {
                            var type = sliderInstance.target.dataset.input;
    
                            $input.val(values[0]);
    
                            _this.setValue(type, values[0]);
                            //_this.updateXYPositions();
                        });
    
                    }
    
                } );
    
            },
            onReady: function() {
                this.initSliders();
                this.updateXYPositions();
            },
    
            updateXYPositions: function() {
                this.fillEmptyXYPositions();
                this.updateXYPositionsValue();
            },
            fillEmptyXYPositions: function() {
                var xypositions = this.getPossibleXYPositions(),
    
                    $controls = this.ui.controls,
                    $sliders = this.ui.sliders,
                    defaultXYPositionsValue = this.defaultXYPositionsValue;
    
                xypositions.forEach( function( xyposition, index ) {
                    var $slider = $sliders.filter( '[data-input="' + xyposition + '"]' );
                    var $element = $controls.filter( '[data-setting="' + xyposition + '"]' );
    
                    if ( $element.length && _.isEmpty( $element.val() ) ) {
                        $element.val( defaultXYPositionsValue[xyposition] );
    
                        if (elementor.config.version < '2.5') {
                            $slider.slider( 'value', defaultXYPositionsValue[xyposition] );
                        } else {
                            $slider[0].noUiSlider.set( defaultXYPositionsValue[xyposition] );
                        }
    
                        //alert(defaultXYPositionsValue[xyposition]);
                    }
    
                } );
            },
            updateXYPositionsValue: function() {
                var currentValue = {},
                    xypositions = this.getPossibleXYPositions(),
                    $controls = this.ui.controls,
                    $sliders = this.ui.sliders,
                    defaultXYPositionsValue = this.defaultXYPositionsValue;
    
                xypositions.forEach( function( xyposition ) {
                    var $element = $controls.filter( '[data-setting="' + xyposition + '"]' );
    
                    currentValue[ xyposition ] = $element.length ? $element.val() : defaultXYPositionsValue;
    
                    var $slider = $sliders.filter( '[data-input="' + xyposition + '"]' );
    
                    if (elementor.config.version < '2.5') {
                        $slider.slider( 'value', $element.length ? $element.val() : defaultXYPositionsValue );
                    } else {
                        $slider[0].noUiSlider.set( $element.length ? $element.val() : defaultXYPositionsValue );
                    }
                } );
                //alert(currentValue);
                this.setValue( currentValue );
            },
    
            getPossibleXYPositions: function() {
                return [
                    'x',
                    'y',
                ];
            },
            onInputChange: function( event ) {
                var inputSetting = event.target.dataset.setting;
    
                var type = event.currentTarget.dataset.setting,
                $slider = this.ui.sliders.filter( '[data-input="' + type + '"]' );
    
                if (elementor.config.version < '2.5') {
                    $slider.slider( 'value', this.getControlValue( type ) );
                } else {
                    $slider[0].noUiSlider.set( this.getControlValue( type ) );
                }
    
                this.updateXYPositions();
            },
        });
        elementor.addControlView( 'xy_positions', ControlXY_PositionsItemView );
        // End of XY Positions





	} );

})(jQuery, window, document);
