
	
tdc.playerToolbar = tdc.playerToolbar || function (parent) {
    this.gutter = document.createElement('div');
    this.gutter.id = "TDCPlayer_ToolBar"; 
    $(this.gutter).addClass("widgetGutter");
    $(parent).append(this.gutter);
   
    this.navBar = document.createElement('div');
    this.navBar.id = "TDCPlayer_ToolBar_Nav"; 
    $(this.gutter).append(this.navBar);
    
    var navul = document.createElement('ul');
    var tmpli = document.createElement('li');
    $(navul).append(tmpli);
    
    this.indexBtn = document.createElement('a');
    this.indexBtn.id = "TDCPlayer_IndexBtn";
    $(this.indexBtn).append("Ind");
    $(this.indexBtn).attr("href","#TDCPlayer_IndexPane");
	$(tmpli).append(this.indexBtn);

    tmpli = document.createElement('li');
    $(navul).append(tmpli);
    this.bookmarkBtn = document.createElement('a');
    this.bookmarkBtn.id = "TDCPlayer_BookmarkBtn";
    $(this.bookmarkBtn).append("Bmk");
    $(this.bookmarkBtn).attr("href","#TDCPlayer_BookmarkPane");
    $(tmpli).append(this.bookmarkBtn);

    tmpli = document.createElement('li');
    $(navul).append(tmpli);
    this.filesBtn = document.createElement('a');
    this.filesBtn.id = "TDCPlayer_FilesBtn";
    $(this.filesBtn).append("Fil");
    $(this.filesBtn).attr("href","#TDCPlayer_FilesPane");
    $(tmpli).append(this.filesBtn);

    tmpli = document.createElement('li');
    $(navul).append(tmpli);
    this.socialBtn = document.createElement('a');
    this.socialBtn.id = "TDCPlayer_SocialBtn";
    $(this.socialBtn).attr("href","#TDCPlayer_SocialPane");
    $(this.socialBtn).append("Soc");
	$(tmpli).append(this.socialBtn);

    this.resizeTabs = function() {
        var tabs = $("#TDCPlayer_ToolBar_Nav ul li");
        var headerSize = ($("#TDCPlayer_ToolBar_Nav").width() / tabs.length) - (2) ;
        $(tabs).width(headerSize);
        $("#TDCPlayer_ToolBar_Nav ul li").first().addClass("buttonLeftEdge"); 
        $("#TDCPlayer_ToolBar_Nav ul li").last().addClass("buttonRightEdge");
    };
    var resizeTabs = this.resizeTabs;

    $(this.navBar).append(navul);
    $(this.navBar).tabs({
                        load: function(event,ui) {
                            resizeTabs();
                        }
                     });
    return this;		
}

tdc.videoScreen = tdc.videoScreen || function (parent) {
    this.gutter = document.createElement('div');
    this.gutter.id = "TDCPlayer_VideoScreenGutter";
    $(this.gutter).addClass("widgetGutter");

    this.videoscreen = document.createElement('div');
    this.videoscreen.id = "TDCPlayer_VideoScreen";
    $(this.gutter).append(this.videoscreen);
 
    $(parent).append(this.gutter);
    return this;
}

tdc.quickBrowser = tdc.quickBrowser || function (parent) {
    this.gutter = document.createElement('div');
    this.gutter.id = "TDCPlayer_QuickBrowserGutter";
    $(this.gutter).addClass("widgetGutter");

    this.browser = document.createElement('div');
    this.browser.id = "TDCPlayer_QuickBrowser";
    $(this.gutter).append(this.browser);

    // create the navigation bar
    this.navBar = document.createElement('div');
    $(this.navBar).addClass("ui-widget-header ui-corner-all"); 
    $(this.navBar).append("<h1>NavBar</h1>");
    $(this.browser).append(this.navBar);

    this.navBarHistoryGutter = document.createElement('div');
    this.navBarHistoryGutter.id= "TDCPlayer_Nav_HistoryGutter";
    $(this.navBarHistoryGutter).addClass("TDC_ControlGutter"); 
    $(this.browser).append(this.navBarHistoryGutter);
    
    this.navBarBackBtn = document.createElement('div');
    this.navBarBackBtn.id= "TDCPlayer_Nav_BackBtn";
    $(this.navBarBackBtn).addClass("TDCPlayer_Button"); 
    $(this.navBarBackBtn).append("<");
    $(this.navBarHistoryGutter).append(this.navBarBackBtn);

    this.navBarForwardBtn = document.createElement('div');
    this.navBarForwardBtn.id= "TDCPlayer_Nav_ForwardBtn";
    $(this.navBarForwardBtn).addClass("TDCPlayer_Button"); 
    $(this.navBarForwardBtn).append(">");
    $(this.navBarHistoryGutter).append(this.navBarForwardBtn);

    this.navBarModeGutter = document.createElement('div');
    this.navBarModeGutter.id= "TDCPlayer_Nav_ModeGutter";
    $(this.navBarModeGutter).addClass("TDC_ControlGutter"); 
    $(this.browser).append(this.navBarModeGutter);

    this.navBarSearchGutter = document.createElement('div');
    this.navBarSearchGutter.id= "TDCPlayer_Nav_SearchGutter";
    $(this.navBarSearchGutter).addClass("TDC_ControlGutter"); 
    $(this.browser).append(this.navBarSearchGutter);


    $(parent).append(this.gutter);
}

tdc.player = tdc.player || function(){
    var that = this;
	this.app = document.createElement("div");
	this.app.id ="TDCPlayer_MainWindow";
	$("body").prepend(this.app);

    this.app.toolbar = tdc.playerToolbar(this.app);
	this.app.videoscreen = tdc.videoScreen(this.app);
	this.app.quickbrowser = tdc.quickBrowser(this.app);     
    // State variables
    this.app.toolbarVisible = false;
    // Functions
    this.toggleNavBar = function() {
        if (!that.app.toolbarVisible) {
            $('#TDCPlayer_VideoScreen').animate({
                                        width:"542px"
                                    },500);

            $('#TDCPlayer_VideoScreenGutter').animate({
                                        "margin-left":"208px",
                                        width:"540px"
                                    },500,function() {
                                        $('#TDCPlayer_ToolBar').fadeIn(500);
                                        that.app.toolbar.resizeTabs();
                                    });
            that.app.toolbarVisible= true;
        } else {
            $('#TDCPlayer_ToolBar').fadeOut(500,function()
                {   
                    $('#TDCPlayer_VideoScreen').animate({
                                                width:"742px"
                                            },500);

                    $('#TDCPlayer_VideoScreenGutter').animate({
                                                "margin-left":"0px",
                                                width:"740px"},500);
                }
            );
            that.app.toolbarVisible= false;
        }
    };
	return this;
};

