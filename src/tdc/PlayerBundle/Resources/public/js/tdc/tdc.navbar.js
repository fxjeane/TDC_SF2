(function( $ ) {
 $.widget( "tdc.tdcNavbar", {
    options: {
        videoObject: null,
        width: "200px",
        height: "250px",
        cornerRadius: "0px",
        tabHeight: "30px"
    },
    _create: function() {
        var self = this;
        var e = self.element;
        var o = self.options;
        e.css("float", "left");
        e.css("border-radius", o.cornerRadius);
        e.css("overflow","hidden");
        padTop = parseInt(e.css("margin-top"));
        padBot = parseInt(e.css("margin-bottom"));
        padLeft = parseInt(e.css("margin-left"));
        padRight = parseInt(e.css("margin-right"));
        e.width(parseInt(o.width) - padLeft - padRight);
        e.height(parseInt(o.height) - padTop - padBot);

        /////////////////////////
        //
        // Tab Control
        //
        ////////////////////////
        // create tab bar
        var tabBar = $("<div id='tdcTabBar'>");
        tabBar.height(o.tabHeight);
        tabBar.css("border-top-left-radius", o.cornerRadius);
        tabBar.css("border-top-right-radius", o.cornerRadius);
        e.append(tabBar);
        // Index Tab
        var index = $("<div id='tdcIndexTab'>");
        index.addClass("tdcTabBarBtn");
        index.addClass("tdcTabBarBtnSelected");
        index.css("float", "left");
        index.css("border-top-left-radius", o.cornerRadius);
        index.css("line-height", (parseInt(o.tabHeight) - 1) + "px");
        index.css("text-align", "center");
        index.width((e.width() * 0.5) - 1);
        index.html("Index");
        tabBar.append(index);
        index.click(e, self.showIndex);
        // Notes Tab
        var notes = $("<div id='tdcNotesTab'>");
        notes.addClass("tdcTabBarBtn");
        notes.css("float", "left");
        notes.css("border-top-right-radius", o.cornerRadius);
        notes.css("line-height", (parseInt(o.tabHeight) - 1) + "px");
        notes.css("text-align", "center");
        notes.width((e.width() * 0.5) - 1);
        notes.html("Notes");
        tabBar.append(notes);
        notes.click(e, self.showNotes);

        /////////////////////////
        //
        // Content
        //
        ////////////////////////
        var indexCnt = $("<div id='tdcIndexCnt'>");
        e.append(indexCnt);
        indexCnt.css("overflow", "hidden");
        indexCnt.css("border-bottom-right-radius", o.cornerRadius);
        indexCnt.css("border-bottom-left-radius", o.cornerRadius);

        // holder of all links
        var linkHolder = $("<div id='tdcIndexCntHolder'>");
        indexCnt.append(linkHolder);

        if (o.videoObject) {
            this.loadIndex(o.videoObject.index);
        } // if o.videoObject

        var notesCnt = $("<div id='tdcNotesCnt'>");
        notesCnt.html("notes");
        notesCnt.hide();
        e.append(notesCnt);
        topPad = parseInt(notesCnt.css("padding-top"));
        botPad = parseInt(notesCnt.css("padding-bottom"));
        notesCnt.height(e.height() - tabBar.height() - topPad - botPad);
    },
    showNotes: function(ev) {
                   var e = ev.data;
                   var index = $('#tdcIndexCnt');
                   var indexBtn = $("#tdcIndexTab");
                   var notes = $('#tdcNotesCnt');
                   var notesBtn = $("#tdcNotesTab");
                   index.hide();
                   notes.show();
                   notesBtn.addClass("tdcTabBarBtnSelected");
                   indexBtn.removeClass("tdcTabBarBtnSelected");
    },
    showIndex: function(ev) {
                   var e = ev.data;
                   var index = $('#tdcIndexCnt');
                   var indexBtn = $("#tdcIndexTab");
                   var notes = $('#tdcNotesCnt');
                   var notesBtn = $("#tdcNotesTab");
                   index.show();
                   notes.hide();
                   indexBtn.addClass("tdcTabBarBtnSelected");
                   notesBtn.removeClass("tdcTabBarBtnSelected");
    },
    loadIndex: function(index){
        var e = this.element;
        var indexCnt = $("#tdcIndexCnt");
        var linkHolder = $("#tdcIndexCntHolder");
        var tabBar =$("tdcTabBar");
        var links = index;
        var row = 0;
        for (var i in links) {
            var li = $("<li>");
            li.addClass("row" + row);
            li.css("cursor","pointer");
            row = 1 - row;
            li.html(links[i].title);
            linkHolder.append(li);
            li.click(links[i].link,function(e) {
               state = $f().getState();
               if (state != 3) {
                    $f().play();
                    if (state === 3) {
                        $f().getClip().onStart(function(){
                            $f().seek(e.data);
                        });
                    }
                    if (state === 4) {
                        $f().seek(e.data);
                    }
                } else {
                    $f().seek(e.data);
                }
            });
        }
        topPad = parseInt(indexCnt.css("padding-top"));
        botPad = parseInt(indexCnt.css("padding-bottom"));
        indexCnt.height(e.height() - tabBar.height() - topPad - botPad - 1);
        // compare against the size of the first link
        liTopPad = parseInt(li.css("padding-top"));
        liBotPad = parseInt(li.css("padding-top"));
        if (((liTopPad + liBotPad + li.height()) * 
            links.length) > indexCnt.height()) {
            /*
            $(linkHolder).tdcScrollBar({
                    container:indexCnt,
                    cornerRadius:o.cornerRadius
                });
            */
        }
    },
    loadData: function(data){
        this.loadIndex(data.index);
    }
});

}( jQuery ) );