(function($) {
    $.widget("tdc.tdcBrowser", {
        options: {
            iconPath:"../public/images/icons",
            dataUrl:null,
            videosUrl:"."
        },
        _create: function() { 
            var self = this,
            o = self.options,
            el = self.element;

            // create window frame
            var wframe = $("<div id='tdcBrowserWFrame'>");
           
            // mode buttons
            var listModeBtn = $("<div id='tdcBrowserListModeBtn'>");
            listModeBtn.width(32).height(32);
            listModeBtn.addClass("tdcGenericButton grayGradient03 blueHoverGradient");
            var listIcon = $("<img>");
            listIcon.attr("src",o.iconPath+"/listMode.png");
            listModeBtn.append(listIcon);

            var iconModeBtn = $("<div id='tdcBrowserIconModeBtn'>");
            var gridIcon = $("<img>");
            gridIcon.attr("src",o.iconPath+"/gridMode.png");
            iconModeBtn.append(gridIcon);
            iconModeBtn.css("float","none");
             
            iconModeBtn.width(32).height(32);
            iconModeBtn.addClass("tdcGenericButton grayGradient03 blueHoverGradient");
            wframe.append(listModeBtn);
            wframe.append(iconModeBtn);


            // Append frame to element
            el.append(wframe);

            var itemHolder = $("<table id='tdcBrowserContent'>");
            var th = $("<th>");
            th.html("icon");
            itemHolder.append(th);
            th = $("<th>");
            th.html("course");
            itemHolder.append(th);
            th = $("<th>");
            th.html("summary");
            itemHolder.append(th);
            th = $("<th>");
            th.html("views");
            itemHolder.append(th);
            th = $("<th>");
            th.html("rating");
            itemHolder.append(th);

            $.getJSON(o.dataUrl, function(data) {
                var row = 0;
                for (i=0; i < data.length; i+=1) {
                    var item = data[i];
                    var tr = $("<tr>");
                    tr.addClass("row_"+row);
                    var td = $("<td class='iconRow'>");
                    
                    var a = $("<a>");
                    a.attr("href",o.videosUrl+"/"+item.id);
                    var img = $("<img>");
                    img.attr("src",o.iconPath+"/"+item.icon);
                    a.append(img)
                    td.append(a);
                    tr.append(td);

                    
                    td = $("<td>");
                    a = $("<a>");
                    a.attr("href",o.videosUrl+"/"+item.id);
                    var courseName = $("<h3 class='courseName'>");
                    courseName.html(item.name);
                    a.append(courseName) 
                    td.append(a);

                    var courseAuthor = $("<span class='courseAuthor'>");
                    courseAuthor.html("Author: "+item.author);
                    td.append(courseAuthor);
                    
                    var trt = $("<span class='courseTrt'>");
                    trt.html("TRT: 22:38");
                    td.append(trt);
                    tr.append(td);
                    
                    td = $("<td>");
                    td.html(item.summary);
                    tr.append(td);

                    td = $("<td>");
                    td.html(item.views);
                    tr.append(td);

                    td = $("<td>");
                    td.html(item.views);
                    tr.append(td);

                    itemHolder.append(tr);
                    
                    row = 1 - row;
                }
             });
             wframe.append(itemHolder);

        },
        destroy: function() {
            this.element.next().remove();
            $(window).unbind("resize");
       }
    });

})(jQuery);
