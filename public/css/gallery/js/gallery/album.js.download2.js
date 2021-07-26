/**
 * jQuery.browser.mobile (http://detectmobilebrowser.com/)
 *
 * jQuery.browser.mobile will be true if the browser is a mobile device
 *
 **/
;
(function(a) {
    (jQuery.browser = jQuery.browser || {}).mobile = /(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|mobile.+firefox|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows ce|xda|xiino/i.test(a) || /1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i.test(a.substr(0, 4))
})(navigator.userAgent || navigator.vendor || window.opera);

;
(function($) {
    $(document).ready(function() {
        var RequestState1 = true;
        var RequestState2 = true;
        var RequestState3 = true;
        var lastWinTop = 0,
            winNo = [],
            $grid, newMsgsNo = {},
            last_attachment_id = '',
            last_slider_id = '';
        last_album_attach_id = '', last_album_id = '';

        Array.prototype.remove = function(value) {
            if (this.indexOf(value) !== -1) {
                this.splice(this.indexOf(value), 1);
                return true;
            } else {
                return false;
            };
        }

        var MyGalleryAjax = {

            mygalleryInit: function() {
                var self = $(this);
                //this.eventHandler();
                this.resizewindow();
                this.HoverContent();
                this.AlbumHoverEffect();
            },

            CreateModal: function() {
                var win_w = $(window).width();
                var win_h = $(window).height();
                var html = '';
                html += '<div id="myg_modal_wrap" style="width:' + win_w + 'px; height:' + win_h + 'px" data-event="myg_modal_close">';
                html += '<span id="myg_modal_close" data-event="myg_modal_close">&nbsp;</span>';
                html += '<div id="myg_modal_image_inner" class="myg_clearfix">';
                html += '<div id="myg_modal_image_wrap">';
                html += '<ul id="myg_modal_image">';
                html += '</ul>';
                html += '<span id="myg_image_left" data-event="myg_image_left">&nbsp;</span>';
                html += '<span id="myg_image_right" data-event="myg_image_right">&nbsp;</span>';
                html += '</div>';
                html += '<div id="myg_modal_content_wrap">';

                html += '</div>';
                html += '</div>';
                html += '</div>';

                $("body").append(html);
            },

            CreateAlbumModal: function() {
                var win_w = $(window).width();
                var win_h = $(window).height();
                var html = '';
                html += '<div id="myg_album_modal_wrap" style="width:' + win_w + 'px; height:' + win_h + 'px">';
                html += '<span id="myg_album_modal_close" data-event="myg_album_modal_close">&nbsp;</span>';
                html += '<div id="myg_album_modal_image_inner" class="myg_clearfix">';
                html += '<div id="myg_album_modal_image">';
                html += '</div>';
                html += '<div class="mygalleryLoading myg_clearfix"></div>';
                html += '</div>';
                html += '</div>';

                $("body").append(html);
            },

            AlbumHoverEffect: function() {
                if ($(".myg_album_image").length > 0) {
                    $(".myg_album_image").delegate('img', 'mouseenter', function() {
                            //detect if cursor is hovering on an image which has a div with class name 'current
                            //attach the css class rotate1 , rotate2 and rotate3 to each image in the stack to rotate the images to specific degrees
                            var $parent = $(this).parent();
                            $parent.find('img.myg_album_photo1').addClass('myg_rotate1');
                            $parent.find('img.myg_album_photo2').addClass('myg_rotate2');
                            $parent.find('img.myg_album_photo3').addClass('myg_rotate3');
                            $parent.find('img.myg_album_photo1').css("left", "50px"); //reposition the last and first photo
                            $parent.find('img.myg_album_photo3').css("left", "-50px");

                        })
                        .delegate('img', 'mouseleave', function() { //if user removes curser on image
                            //remote all class previously added to give the photos it's initial position
                            $('img.myg_album_photo1').removeClass('myg_rotate1');
                            $('img.myg_album_photo2').removeClass('myg_rotate2');
                            $('img.myg_album_photo3').removeClass('myg_rotate3');
                            $('img.myg_album_photo1').css("left", "");
                            $('img.myg_album_photo3').css("left", "");

                        });
                }
            },

            resizewindow: function() {
                $(window).resize(function() {
                    if ($('#myg_modal_wrap').length > 0 || $('#myg_album_modal_wrap').length > 0) {
                        var win_w = $(window).width();
                        var win_h = $(window).height();
                        $('#myg_modal_wrap').css({
                            'width': win_w + 'px',
                            'height': win_h + 'px'
                        });
                        $('#myg_album_modal_wrap').css({
                            'width': win_w + 'px',
                            'height': win_h + 'px'
                        });
                    }
                });

            },

            HoverContent: function() {

                /*
                 * HoverDir object.
                 */
                jQuery.HoverDir = function(options, element) {
                    this.$el = $(element);
                    this._init(options);
                };

                jQuery.HoverDir.defaults = {
                    hoverDelay: 0,
                    reverse: false
                };

                jQuery.HoverDir.prototype = {
                    _init: function(options) {
                        this.options = $.extend(true, {}, $.HoverDir.defaults, options);
                        // load the events
                        this._loadEvents();
                    },
                    _loadEvents: function() {
                        var _self = this;
                        this.$el.on('mouseenter.hoverdir, mouseleave.hoverdir', function(event) {
                            var $el = $(this),
                                evType = event.type,
                                $hoverElem = $el.find('article'),
                                direction = _self._getDir($el, {
                                    x: event.pageX,
                                    y: event.pageY
                                }),
                                hoverClasses = _self._getClasses(direction);

                            $hoverElem.removeClass();

                            if (evType === 'mouseenter') {
                                $hoverElem.hide().addClass(hoverClasses.from)
                                    .delay(50, "steps")
                                    .queue("steps", function(next) {
                                        $hoverElem.siblings('.myg_social').slideToggle('slow');
                                        next();
                                    })
                                    .dequeue("steps");
                                clearTimeout(_self.tmhover);
                                _self.tmhover = setTimeout(function() {
                                    $hoverElem.show(0, function() {
                                        $(this).addClass('myg-animate').addClass(hoverClasses.to);
                                    });

                                }, _self.options.hoverDelay);

                            } else if (evType === 'mouseleave') {
                                $hoverElem.addClass('myg-animate')
                                    .delay(50, "steps")
                                    .queue("steps", function(next) {
                                        $hoverElem.siblings('.myg_social').slideToggle('slow');
                                        next();
                                    })
                                    .dequeue("steps");
                                clearTimeout(_self.tmhover);
                                $hoverElem.addClass(hoverClasses.from);

                            }
                        });
                    },
                    // credits : http://stackoverflow.com/a/3647634
                    _getDir: function($el, coordinates) {
                        /** the width and height of the current div **/
                        var w = $el.width(),
                            h = $el.height(),

                            /** calculate the x and y to get an angle to the center of the div from that x and y. **/
                            /** gets the x value relative to the center of the DIV and "normalize" it **/
                            x = (coordinates.x - $el.offset().left - (w / 2)) * (w > h ? (h / w) : 1),
                            y = (coordinates.y - $el.offset().top - (h / 2)) * (h > w ? (w / h) : 1),

                            /** the angle and the direction from where the mouse came in/went out clockwise (TRBL=0123);**/
                            /** first calculate the angle of the point, 
                            add 180 deg to get rid of the negative values
                            divide by 90 to get the quadrant
                            add 3 and do a modulo by 4  to shift the quadrants to a proper clockwise TRBL (top/right/bottom/left) **/
                            direction = Math.round((((Math.atan2(y, x) * (180 / Math.PI)) + 180) / 90) + 3) % 4;

                        return direction;

                    },
                    _getClasses: function(direction) {
                        var fromClass, toClass;
                        switch (direction) {
                            case 0:
                                // from top
                                (!this.options.reverse) ? fromClass = 'myg-slideFromTop': fromClass = 'myg-slideFromBottom';
                                toClass = 'myg-slideTop';
                                break;
                            case 1:
                                // from right
                                (!this.options.reverse) ? fromClass = 'myg-slideFromRight': fromClass = 'myg-slideFromLeft';
                                toClass = 'myg-slideLeft';
                                break;
                            case 2:
                                // from bottom
                                (!this.options.reverse) ? fromClass = 'myg-slideFromBottom': fromClass = 'myg-slideFromTop';
                                toClass = 'myg-slideTop';
                                break;
                            case 3:
                                // from left
                                (!this.options.reverse) ? fromClass = 'myg-slideFromLeft': fromClass = 'myg-slideFromRight';
                                toClass = 'myg-slideLeft';
                                break;
                        };
                        return {
                            from: fromClass,
                            to: toClass
                        };
                    }
                };

                var logError = function(message) {
                    if (this.console) {
                        console.error(message);
                    }
                };

                jQuery.fn.hoverdir = function(options) {
                    if (typeof options === 'string') {
                        var args = Array.prototype.slice.call(arguments, 1);
                        this.each(function() {
                            var instance = $.data(this, 'hoverdir');
                            if (!instance) {
                                logError("cannot call methods on hoverdir prior to initialization; " +
                                    "attempted to call method '" + options + "'");
                                return;
                            }
                            if (!$.isFunction(instance[options]) || options.charAt(0) === "_") {
                                logError("no such method '" + options + "' for hoverdir instance");
                                return;
                            }
                            instance[options].apply(instance, args);
                        });
                    } else {
                        this.each(function() {
                            var instance = $.data(this, 'hoverdir');
                            if (!instance) {
                                $.data(this, 'hoverdir', new $.HoverDir(options, this));
                            }
                        });

                    }
                    return this;
                };

                if ($('.myg_hover_content').length > 0) {
                    if (jQuery.browser.mobile) {
                        $('.myg_hover_content .myg_social').css('display', 'block')
                    } else {
                        $('.myg_hover_content').hoverdir();
                    }
                }

            }
        }

        MyGalleryAjax.mygalleryInit();

    });
}(jQuery));