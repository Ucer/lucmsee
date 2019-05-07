$(function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    var App = {
        init: function () {
            var self = this;
            self.siteBootUp()
        },
        siteBootUp: function () {
            var self = this;
            self.initFancyBox()
        },
        initFancyBox: function () {
            $('.fancybox')
                .attr('rel', 'media-gallery')
                .fancybox({
                    openEffect: 'none',
                    closeEffect: 'none',
                    prevEffect: 'none',
                    nextEffect: 'none',

                    arrows: false,
                    helpers: {
                        media: {},
                        buttons: {}
                    }
                });
        }
    };

    $(document).ready(function () {
        App.init()
    })

});

