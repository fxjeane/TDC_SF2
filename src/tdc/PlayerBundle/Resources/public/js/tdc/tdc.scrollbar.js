(function($) {
    /////////////////////////
    //
    // ScrollBar Plugin
    //
    ////////////////////////    
    $.widget("tdc.tdcScrollBar", {
        options:{
            width:"15px",
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

    /*            
            e.bind('mousewheel', function(ev,delta) {
                var el = $(ev.delegateTarget);
                scrollGutter = el.siblings().last();
                scrollHandle = scrollGutter.children().first();

                pos = el.css("top");
                if (pos === "auto")
                    pos = 0;
                pos = parseInt(pos);
                ratio = parent.height() - el.outerHeight();
                // set content pos
                el.css("top", Math.max(Math.min(0,pos + delta),ratio));

                handlePos = scrollHandle.css("top");
                if (handlePos === "auto")
                    handlePos = 0;
                handlePos = parseInt(handlePos);
                handleRatio = scrollGutter.height() / scrollHandle.height();
                handleMax = parent.height() - handle.outerHeight();
                handleNewPos = (handlePos - (delta * handleRatio))
                // set handle pos
                scrollHandle.css("top", Math.min(Math.max(0,handleNewPos),handleMax));
            });
            e.click(function(){
                $("#console").html();
                e.bind('mousewheel', function(ev) {
                    var remap= function(value,low1,high1,low2,high2){
                        return low2 + (value - low1) * (high2 - low2) / (high1 - low1)
                    };

                    var el = $(ev.delegateTarget);

                    scrollGutter = el.siblings().last();
                    scrollHandle = scrollGutter.children().first();
                        
                    repos = (ev.pageY - parent.offset().top); 
                    relativePos = repos /parent.height();
                    ratio = parent.height() - handle.outerHeight();
                    diff = el.height() - parent.height();
                    
                    el.css("top", -(relativePos * diff));
                    // scroll pos
                    scrollPos =remap(repos,0,el.height(),0,ratio ) / ratio;
                    scrollHandle.css("top", Math.min((scrollPos * diff),ratio));
                });
            });
*/
        gutter.append(handle);
        }
    });
}(jQuery));
