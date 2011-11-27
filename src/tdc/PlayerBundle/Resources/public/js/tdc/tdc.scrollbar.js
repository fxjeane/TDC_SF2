(function($) {
    /////////////////////////
    //
    // ScrollBar Plugin
    //
    ////////////////////////    
    $.widget("tdc.tdcScrollBar", {
        options:{
            width:"20px",
            handleMinHeight:"30px",
            cornerRadius:"0px"
        },
        _create: function() {
            var self = this;
            var e = self.element;
            var o = self.options;
            var parent = e.parent();

            e.width((parseInt(e.width()) - parseInt(o.width)) + "px");
            e.css("border-bottom-right-radius", "0px");
            e.css("position","relative");
            // add gutter
            var gutter = $("<div>");
            gutter.addClass("tdcScrollbarGutter");
            gutter.height(parent.height()).width(o.width);
            gutter.css("position","relative");
            gutter.css("float","right");
            gutter.css("top",-e.height());
            parent.append(gutter);
            // add handle
            var handle = $("<div>");
            handle.addClass("tdcScrollbarHandle")
            handle.css("border-radius", o.cornerRadius);
            handle.css("cursor", "row-resize");
            var handleHeight = Math.max(parseInt(o.handleMinHeight),
                    gutter.height() * (gutter.height() / e.height()));
            handle.height(handleHeight + "px");

            $(handle).draggable({
                axis: 'y',
                containment: "parent",
                drag: function(event, ui) {
                        handle = $(event.target);
                        parent = handle.parent();
                        content = parent.prev();

                        ratio = parent.height()-handle.height();
                        diff = content.height() - parent.height();
                        relativePos = (ui.position.top  / ratio);
                        content.css("top",-(relativePos * diff));
                    }
            });

            e.bind('mousewheel', function(ev,delta) {
                    event.preventDefault();
                    delta *= 10;

                    var el = $(ev.delegateTarget);
                    scrollGutter = el.siblings().last();
                    scrollHandle = scrollGutter.children().first();

                    pos = el.css("top");
                    if (pos === "auto")
                    pos = 0;
                    pos = parseInt(pos);
                    ratio = parent.height() - el.outerHeight();
                    el.css("top", Math.max(Math.min(0,pos + delta),ratio));

                    percent = Math.abs(parseInt(el.css("top")) / parseInt(el.outerHeight() - parent.outerHeight()));

                    handleMax = parent.height() - handle.outerHeight();
                    handleNewPos = percent * handleMax;
                    scrollHandle.css("top", Math.min(Math.max(0,handleNewPos),handleMax));
            });

        gutter.append(handle);
        }
    });
}(jQuery));
