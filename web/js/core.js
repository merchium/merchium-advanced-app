(function($){

    // Rewrite yii events
    $(document).on('click.merchium', yii.clickableSelector, function(e) {
        var jelm = $(e.target);

        if (jelm.data('cProcessItems')) {
            var url = jelm.data('url') || jelm.attr('href');
            jelm.data('url', url);
            var obj_name = jelm.data('cProcessItems'),
                url_params = {},
                keys = $('.grid-view').yiiGridView('getSelectedRows');
            
            if (!keys.length) {
                alert(yii.merchium.langs['No items selected']);
                e.stopImmediatePropagation();
                return false;
            }
            
            url_params[obj_name] = keys;
            var delimiter = url.indexOf('?') == -1 ? '?' : '&';
            jelm.attr('href', url + delimiter + decodeURIComponent($.param(url_params)));
            return true;
        }
    });

})(jQuery);
