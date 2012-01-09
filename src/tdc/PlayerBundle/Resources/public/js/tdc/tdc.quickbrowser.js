(function( $ ) {

////////////////////////////
//
// Navbar plugin
//
////////////////////////////    
 $.widget("tdc.tdcBrowserNavbar", {
    options: {
        show: true,
        label: "NavBar",
        height: "30px",
        borderRadius: "inherit",
        controlRadius: "0px",
        listButton: true,
        thumbnailButton: true,
        pageRoot: "",
        searchBar: {
            show: true,
            width: "150px"
        }
    },
    _create: function() {
        var self = this;
        var e = self.element;
        var o = self.options;

        var labelWidth = e.outerWidth();

        // set the root;
        e.height(o.height);
        e.addClass("tdcQuickBrowserNavBar windowBar");
        e.css("border-radius", o.borderRadius + " " + o.borderRadius + " 0px 0px");
        
        ///////////////
        // Display Mode Buttons
        ///////////////
        if (o.listButton || o.thumbnailButton) {
            var dispModeButtons = $('<div id="tdcQBNBDisplayModeButtons">');
            dispModeButtons.addClass('gutter');
            dispModeButtons.height(e.height());
            dispModeButtons.css("float", "left");
            dispModeButtons.css("border-radius", o.controlRadius);
            e.append(dispModeButtons);

            // list button
            if (o.listButton) {
                var listButton = $('<div id="listBtn">');
                listButton.addClass('button widgetControlActive');
                var img = $('<img>');
                img.attr("src",o.pageRoot+"../public/images/TDC1/questions.png");
                listButton.html(img);
                dispModeButtons.append(listButton);
                listButton.css("border-top-left-radius", o.controlRadius);
                listButton.css("border-bottom-left-radius", o.controlRadius);
                listButton.css("position", "relative");
                listButton.css("float", "left");
                if (!o.thumbnailButton) {
                    listButton.css("border-top-right-radius", o.controlRadius);
                    listButton.css("border-bottom-right-radius", o.controlRadius);
                } else {
                    listButton.addClass("leftEndBtn");
                }
                listButton.click({"root": e.parent() }, self._displayList);

                bl = parseInt(listButton.css("border-top-width"));            
                br = parseInt(listButton.css("border-bottom-width"));            
                listButton.height(e.height() - bl - br);
                listButton.css("line-height", listButton.height() + "px");
            }
            // thumbnail button
            if (o.thumbnailButton) {
                var thumbButton = $('<div id="thumbnailBtn">');
                thumbButton.addClass('button widgetControlGray');
                var img = $('<img>');
                img.attr("src",o.pageRoot+"../public/images/TDC1/browser.png");
                thumbButton.html(img);
                dispModeButtons.append(thumbButton);
                thumbButton.css("border-top-right-radius", o.controlRadius);
                thumbButton.css("border-bottom-right-radius", o.controlRadius);
                thumbButton.css("float", "left");
                thumbButton.css("position", "relative");
                if (!o.listButton) {
                    thumbButton.css("border-top-left-radius", o.controlRadius);
                    thumbButton.css("border-bottom-left-radius", o.controlRadius);
                } else {
                    thumbButton.addClass("rightEndBtn");
                }
                bl = parseInt(thumbButton.css("border-top-width"));            
                br = parseInt(thumbButton.css("border-top-width"));            
                thumbButton.height(e.height() - bl - br);
                thumbButton.css("line-height", thumbButton.height() + "px");
                thumbButton.click( self._displayThumb);
            }
            labelWidth -= dispModeButtons.outerWidth(true);
        }
        /////////////////
        // label
        ///////////////
        var label = $('<div id="label">');
        label.addClass('label');
        label.css("line-height", (e.height() - 4) + "px");
        label.css("text-align", "center");
        label.html(o.label);
        e.append(label);
        /////////////////
        // search bar
        ///////////////
        if (o.searchBar.show) {
            var searchBar = $('<div id="tdcQBNBSearchBar">');
            e.append(searchBar);
            searchBar.height(e.height()).width(o.searchBar.width);
            searchBar.addClass("gutter");
            searchBar.css("line-height", searchBar.height() + "px");
            searchBar.css("float", "right");
            searchBar.css("border-radius", o.controlRadius);


            // search bar icon
            var searchBarIcon = $('<div id="tdcQBNBSearchBarIcon">');
            searchBarIcon.addClass("widgetControlGray");
            searchBar.append(searchBarIcon);
            searchBarIcon.css("float", "left");
            searchBarIcon.css("border-top-left-radius", o.controlRadius);
            searchBarIcon.css("border-bottom-left-radius", o.controlRadius);
            var img = $('<img>');
            img.attr("src",o.pageRoot+"../public/images/TDC1/searchIcon.png");
            searchBarIcon.html(img);
            bl = parseInt(searchBarIcon.css("border-left-width"));
            br = parseInt(searchBarIcon.css("border-right-width"));
            searchBarIcon.height(searchBar.height() - bl - br );

            // search field
            var searchForm = $('<form name="tdcBrowserSearch" id="searchForm">');
            searchBar.append(searchForm);

            searchForm.width(searchBar.width() - searchBarIcon.width() - 39);
            searchForm.css("display", "inline");

            var searchField = $('<input name="tdcBrowserSearchValue" id="searchField" >');
            searchField.attr("value", "search");
            searchField.css("padding-left", "4px");
            searchField.css("border-top-right-radius", o.controlRadius);
            searchField.css("border-bottom-right-radius", o.controlRadius);
            searchField.css("position", "relative");
            searchField.css("top", "-1px");

            searchField.height((searchBar.height() - 1) + "px");
            searchField.width(searchForm.width()+2);
            searchForm.append(searchField);

            labelWidth -= searchBar.outerWidth(true);
        }

        // Resize the label
        label.width(labelWidth - 85);
        },
    ////.
    // Functions
    ////
    _displayList: function(e) {
        //var rootId = e.data.root.attr("id");
        //var content = $("#" + rootId + " #content");
        //content.html("foo");

        listButton = $("#listBtn");
        listButton.addClass("tdcButtonSelected widgetControlActive");
        listButton.height(listButton.height() +1);
        thumbButton = $("#thumbnailBtn");
        thumbButton.removeClass("tdcButtonSelected widgetControlActive");
        thumbButton.height(thumbButton.height() - 1);
    },
    _displayThumb: function(e) {
        listButton = $("#listBtn");
        listButton.removeClass("tdcButtonSelected widgetControlActive");
        listButton.height(listButton.height() -1);
        thumbButton = $("#thumbnailBtn");
        thumbButton.addClass("tdcButtonSelected widgetControlActive");
        thumbButton.height(thumbButton.height() + 1);
    }
});

////////////////////////////
//
// conent plugin
//
////////////////////////////    
$.widget("tdc.tdcBrowserContent", {
    options: {
        player:null,
        height: "400px",
        pageRoot:"",
        mode: "browse"
    },
    // Set up the widget
    _create: function() {
        var self = this;
        var e = self.element;
        var o = self.options;
        
        // set the root;
        e.width(o.width);
        e.addClass("content");
        e.height(o.height);
        this.loadData(self);
    },
    loadData: function(data) {
        var e = data.element;
        var o = data.options;

        if (o.mode === "browse") {
            listButton = $("#listBtn");
            listButton.addClass("tdcButtonSelected"); 
            listButton.height(listButton.height()+1);

            var service = o.pageRoot+"ws/videolist/0/10";
            $.getJSON(service, function(data) {
                if (data.length > 0) {
                    var listTable = $("<table width='100%' id='itemsList' cellspacing='0px'>");
                    listTable.css("position","relative");
                    listTable.css("z-index","10");
                    var rowclass = 0;
                    for (i = 0; i < data.length; i += 1) {
                        item = data[i];
                        tr = $("<tr>");
                        tr.addClass("row" + rowclass);
                        td = $("<td >");
                        td.addClass("icon");
                        img = $("<img>");
                        img.attr("src",o.pageRoot+"../public/images/icons/"+item.icon);
                        td.append(img);
                        tr.append(td);
                        
                        td = $("<td>");
                        var aa = $("<a>");
                        aa.addClass("videoName");
                        aa.html(item.name);
                        aa.click(item.id,function(ev){
                            $(o.player).tdcPlayer("loadVideo",ev.data)
                        });
                        td.append(aa);
                        lab = $("<label>");
                        lab.addClass("author");
                        lab.html("Author: "+item.author);
                        td.append(lab);
                        lab = $("<label>");
                        lab.addClass("trt");
                        lab.html("Trt: "+item.trt);
                        td.append(lab);
                        tr.append(td);

                        td = $("<td>");
                        td.addClass("viewsRating");
                        lab = $("<label>");
                        lab.addClass("views");
                        lab.html("Views: "+item.views);
                        td.append(lab);
                        lab = $("<label>");
                        lab.addClass("rating");
                        lab.html("Rating: "+item.views);
                        td.append(lab);
                        
                        tr.append(td);
                        
                        listTable.append(tr);
                        rowclass = 1 - rowclass;
                    }
                    e.append(listTable);

                    // do we need a scroll bar?
                    if (tr.height() * data.length > e.height()) {
                        $(listTable).tdcScrollBar({scrollSpeed:15});
                    }
                }
            });
        }
    }
});


////////////////////////////
//
// paginator plugin
//
////////////////////////////
$.widget("tdc.tdcBrowserPaginator", {
    options: {
        show: true,
        height: "15px",
        borderRadius: "inherit"
    },
    // Set up the widget
    _create: function() {
        var self = this;
        var e = self.element;
        var o = self.options;

        // set the root;
        e.width(o.width);
        e.addClass("tdcQBNDPaginator windowBar");
        e.height(o.height);
        e.css("border-bottom-left-radius", o.borderRadius);
        e.css("border-bottom-right-radius", o.borderRadius);
    }
});



$.widget( "tdc.tdcQuickBrowser", {
    options: {
        width: "100%",
        spacer:"5px",
        borderRadius: "0px",
        navbar: $.tdc.tdcBrowserNavbar.prototype.options,
        content: $.tdc.tdcBrowserContent.prototype.options,
        paginator: $.tdc.tdcBrowserPaginator.prototype.options,
    },
    // Set up the widget
    _create: function() {
        var self = this;
        var e = self.element;
        var o = self.options;
        var parent = e.parent();

        // set the root;
        if (o.width === "100%") {
            bl = parseInt(e.css("border-left-width"));            
            br = parseInt(e.css("border-right-width"));            
            pr = parseInt(e.css("padding-right"));            
            pl = parseInt(e.css("padding-left"));            
            e.width(parent.width() - (parseInt(o.spacer) * 2) - bl - br -pl -pr );
        } else {
            e.width(o.width);
        }
        e.addClass("tdcBrowser");
        e.css("border-radius", o.borderRadius);
        e.css("margin",o.spacer);
        e.css("margin-top",parseInt(o.spacer) * 2);

        ////////////////////////
        // Navbar
        ///////////////////////
        if (o.navbar.show) {
            var navBar = $("<div id='tdcQuickBrowserNavbar'>");
            e.append(navBar);
            if (o.navbar.borderRadius === "inherit") o.navbar.borderRadius = o.borderRadius;
            $(navBar).tdcBrowserNavbar(o.navbar);
        }

        ////////////////////////
        // Content Area
        ///////////////////////
        var content = $('<div id="tdcQuickKBrowserContent">');
        e.append(content);
        if (o.content.borderRadius === "inherit")
            o.content.borderRadius = o.borderRadius;
        $(content).tdcBrowserContent(o.content);

        ////////////////////////
        // Paginator Area
        ///////////////////////
        if (o.paginator.show) {
            var paginator = $("<div id='tdcQuickBrowserPaginator'>");
            e.append(paginator);
            $(paginator).tdcBrowserPaginator(o.paginator);
        }
    }
});

}( jQuery ) );
