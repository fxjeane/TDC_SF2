(function($) {
    $.widget("tdc.tdcPaginator", {
        options: {
            start:0,
            max:10,
            queryUrl:".",
            targetUrl:"",
            trailLinks:2,
            firstLabel:"first",
            lastLabel:"last"
        },
        _create: function() { 
            var self = this,
            o = self.options,
            el = self.element;
            
            url = o.queryUrl;

            $.getJSON(url,function(data){
                numpages = Math.floor(data.length / o.max);
                if (numpages === 0)
                    return;
                curpage = o.start;
                // handle the first page
                if (curpage - o.trailLinks > 1) {
                    target = o.targetUrl+"/"+1+"/"+o.max;
                    num = $('<a href='+target+'>');
                    li = num.append('<li>');
                    li.html(o.firstLabel);
                    el.append(num);
                    el.append(' ');
                }
                //alert(curpage + o.trailLinks);
                for (i = Math.max(1,curpage - o.trailLinks); 
                     i <= Math.min(numpages,curpage + o.trailLinks); i +=1) {
                    target = o.targetUrl+"/"+i+"/"+o.max;
                    num = $('<a href='+target+'>');
                    li = num.append('<li>');
                    li.html(i);
                    if (i === curpage)
                        li.css("color","red");
                    el.append(num);
                    el.append(' ');
                }
                // handle the last page
                if ((numpages >> curpage + o.trainLinks) && (numpages !== i-1)) {
                    target = o.targetUrl+"/"+numpages+"/"+o.max;
                    num = $('<a href='+target+'>');
                    li = num.append('<li>');
                    li.html(o.lastLabel);
                    el.append(li);
                } 
                // handle trailing page
                else 
                {
                    if (data.length % o.max !== 0) {
                    target = o.targetUrl+"/"+i+"/"+o.max;
                    num = $('<a href='+target+'>');
                    li = num.append('<li>');
                    li.html(i);
                    el.append(li);
                    }
                }

            });

        },
        destroy: function() {
            this.element.next().remove();
       }
    });

})(jQuery);
