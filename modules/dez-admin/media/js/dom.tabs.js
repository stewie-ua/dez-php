app.DOM.extend({
    tabs: function($){
        var options = {
            tabActiveClass:     'dez-tab-active'
        };
        return this.each(function(){
            var tabsRoot    = $(this),
                li          = tabsRoot.find('li'),
                div         = tabsRoot.find('.dez-tabs-box');
            div.find('div:not(:first-child)').css( 'display', 'none' );
            tabsRoot.find('li:first-child a').addClass(options.tabActiveClass);
            tabsRoot.on('click', '.dez-tabs a', function(e) {
                e.preventDefault();

                li.find('a').removeClass(options.tabActiveClass);
                div.find( 'div' ).css( 'display', 'none' );
                div.find( '#tab-id-' + $(this).addClass(options.tabActiveClass).data('tabId') )
                    .css( 'display', 'block' );
            });
        });
    }
});