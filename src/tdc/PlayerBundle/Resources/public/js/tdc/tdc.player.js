(function( $ ) {
    $.widget( "tdc.tdcPlayer", {
        options:{
            videoId:null,
            splashImage:"bundles/tdcplayer/images/screenBG.jpg",
            pageRoot:"",
            spacer:5,
            cornerRadius:"5px",
            username: "user",
            screen: {
                height:"500px"
            },
            navBar: {
                width:"200px"
                },
            browser: {
                queryUrl:"foo",
                start:0,
                max:10
            }
        },
        _create: function() {
            var self = this;
            var e = this.element;
            var o = this.options;

            /////////////////
            //
            // Navigation bar 
            //
            /////////////////
            // Create the nav bar
            var navBar = $('<div id="tdcNavBar">');
            navBar.hide();
            navBar.css("margin", "0px "+o.spacer+"px");
            e.prepend(navBar);
            $(navBar).tdcNavbar({
                width:o.navBar.width,
                height:o.screen.height,
                cornerRadius:o.cornerRadius
            });

            /////////////////
            //
            // Video Player 
            //
            /////////////////
            // Create the video player
            rootUrl = o.pageRoot;
            rootUrl = rootUrl.substring(0,rootUrl.lastIndexOf("/"));
            rootUrl = rootUrl.substring(0,rootUrl.lastIndexOf("/"));
            flowplayer("tdcVideoScreen", 
                {
                    src:rootUrl+"/flowplayer/flowplayer-3.2.7.swf",
                    wmode:'transparent'
                },
                {
                    "canvas":
                    {
                        background:'#000000 url('+rootUrl+"/"+o.splashImage+') no-repeat 0 0',
                        backgroundGradient: 'none',
                        backgroundColor:'#000000',
                        borderRadius:'0',
                        border:'0px solid #000000' 
                    },
                    "clip":
                    {
                        "scaling":"fit",
                        "provider":"rtmp",
                        "autoPlay":false,
                        "urlrResolver": "cloudfront",
                        onStart: function() {
                           this.getPlugin("canvas").css({backgroundImage: ""}); 
                        }
                    },
                    "plugins":
                    {
                        "rtmp":
                        {
                            "netConnectionUrl":"rtmp://senhwy3svazum.cloudfront.net/cfx/st",
                            "url":"flowplayer.rtmp-3.2.3.swf"
                        },
                        "cloudfront": {
                            "url": "flowplayer.cloudfrontsignedurl.swf"
                        },
                        "watermark": {
                            "url": "tdc.watermark.swf",
                            "userName":o.username,
                            "copyrightName":'Copyright - TD Channel',
                            "wmOpacity":0.25,
                            "dropShadow":'true',
                            "zIndex":2
                        },
                        "controls": {
                              // location of the controlbar plugin
                             url: 'flowplayer.controls-3.2.5.swf',
                             // display properties such as size, location and opacity
                             opacity: 0.8,
                             borderRadius:0,
                             // styling properties (will be applied to all plugins)
                             backgroundGradient: 'low',
                            onShowed: function () {
                                var content = $f().getPlugin("myContent");
                                content.animate({"opacity":1});
                            },
                            onHidden: function(){
                                var content = $f().getPlugin("myContent");
                                content.animate({"opacity":0});
                             },
                             // tooltips (since 3.1)
                             tooltips: {
                                 buttons: true,
                                 fullscreen: 'Enter fullscreen mode'
                             }
                        },
                        myContent: {
                            // location of the plugin
                            "url": 'flowplayer.content-3.2.0.swf',
                            backgroundGradient:'none',
                            backgroundColor:'transparent',
                            border:'0px solid #000',
                            top: 0,
                            left: 0,
                            width:40,
                            html:"<img src=\"../public/images/TDC1/playerCloseSidebar.png\" />",
                            borderRadius: 0,
                            // clicking on the plugin hides it (but you can do anything)
                            onClick: function() {
                                self.toggleNavBar();
                            }
                        }
                    }
                });
            
            // Size up the videoPlayer
            var videoScreen = e.children('#tdcVideoScreen');
            videoScreen.css("position","relative");
            videoScreen.css("margin",o.spacer);
            videoScreen.css("border-radius",o.cornerRadius);
            videoScreen.height(o.screen.height);

            // load the video
            self.loadVideo(o.videoId);
            this.toggleNavBar();

            /////////////////
            //
            // Quick browser
            //
            /////////////////
            var browser = $("<div id='tdcQuickBrowserRoot'>");
            e.append(browser);
            // apply the plugin
            $(browser).tdcQuickBrowser({
                spacer:o.spacer,
                borderRadius:o.cornerRadius,
                navbar:{
                    controlRadius:o.cornerRadius,
                    pageRoot:o.pageRoot
                },
                content:{
                    player:e,
                    pageRoot:o.pageRoot
                },
                paginator: {
                    queryUrl:o.browser.queryUrl,
                    start:o.browser.start,
                    max:o.browser.max
                }
            });
        },
        toggleNavBar: function(){
            var e = this.element;
            var o = this.options;
            var videoScreen = e.children('#tdcVideoScreen');
            var navBar = e.children('#tdcNavBar');
            var nbBorderL = parseInt(navBar.css("border-left-width"));
            var nbBorderR = parseInt(navBar.css("border-right-width"));
            // show navbar
            if (parseInt(videoScreen.css("margin-left")) === o.spacer) {
                videoScreen.animate({
                                    "width":videoScreen.width() - navBar.width() - o.spacer,
                                    "margin-left":navBar.width() + (2 * o.spacer) + nbBorderL+ nbBorderR
                                    },500,function(){
                                        navBar.fadeIn();
                                        var content = $f().getPlugin("myContent");
                                        if (content.setHtml) {
                                            var html="<img src=\"../public/images/TDC1/playerCloseSidebar.png\" />";
                                            content.setHtml(html);
                                            var canvas = $f().getPlugin("canvas");
                                            canvas.css({
                                                    background:'#000000 url('+rootUrl+"/"+o.splashImage+') no-repeat 0 0'
                                                });
                                        }
                                    });
            } else {
                // hide navbar
                navBar.fadeOut(500,function(){
                                videoScreen.animate({
                                    "width":videoScreen.width() + navBar.width() + o.spacer,
                                    "margin-left":o.spacer
                                    },500,function(){
                                        var content = $f().getPlugin("myContent");
                                        var html="<img src=\"../public/images/TDC1/playerOpenSidebar.png\" />";
                                        content.setHtml(html);
                                        var canvas = $f().getPlugin("canvas");
                                        canvas.css({
                                                background:'#000000 url('+rootUrl+"/"+o.splashImage+') no-repeat 100 0',
                                                backgroundColor:'#000000'
                                            });
                                    });
                              });

            }
        },
        toggleVideoInfo: function() {
            var showSize = 170;
            var header = $("#headerBar");
            if (header.height() < showSize) {
                header.animate({
                    height:showSize
                });
            } else {
                header.animate({
                    height:30
                });
            }
        },
        loadVideo: function(id){
            var self = this;
            var service = this.options.pageRoot+"ws/video/"+id;

            $.getJSON(service, function(data) {
                // set the title of the video
                var videoname = data.name;
                if (data.subtitle != "")
                    videoname += " - "+data.subtitle;

                $(".videoTitleName").html(videoname);
                $(".videoTitleName").click(function(){
                    //self.toggleVideoInfo();
                    $( "#tdcVideoInfo" ).toggle("blind", {} , 500 );
                });
                $("#infoButton").click(function(){
                    //self.toggleVideoInfo();
                    $( "#tdcVideoInfo" ).toggle("blind", {} , 500 );
                });
                // set the info of the video
                var infoDiv = $("#tdcVideoInfo");
                var infoTbl = $("<table>");
                infoDiv.append(infoTbl);
                var tr = $("<tr>");
                var td = $("<td>Author:</td>");
                tr.append(td);
                td = $("<td>"+data.author+"</td>");
                tr.append(td);
                td = $("<td>Views: "+data.views+"</td>");
                tr.append(td);
                td = $("<td>Trt: "+data.trt+"</td>");
                tr.append(td);
                infoTbl.append(tr);
                // summary
                tr = $("<tr>");
                td = $("<td>Summary:</td>");
                tr.append(td);
                td = $("<td colspan=3>"+data.summary+"</td>");
                tr.append(td);
                infoTbl.append(tr);

                // create the navigation object
                navobj = {};
                if ((data.toc !== "") && (data.toc.indexOf(",") !== -1)) {
                    tocTokens = data.toc.split(",");
                    links = [];
                    for (i = 0; i<tocTokens.length;i+=1){
                        itemTokens = tocTokens[i].split("%");
                        linkName = itemTokens[0]
                        linkTimeTokens = itemTokens[1].split(":");
                        hours = parseInt(linkTimeTokens[0]) * 60 * 60;
                        minutes = parseInt(linkTimeTokens[1]) * 60;
                        seconds = parseInt(linkTimeTokens[2]);
                        linkTime = hours + minutes + seconds;
                        links.push({"title":linkName,"link":linkTime});
                    }
                    navobj["index"] = links;
                }

                $("#tdcNavBar").tdcNavbar("loadData",navobj);
                
                // set the video stream
                if ($f().isLoaded()) {
                        $f().setClip({"url":"mp4:videos/"+data.filepath});
                        $f().play();
                } else {
                    $f().onLoad(function(){
                        $f().setClip(
                            {"url":"mp4:videos/"+data.filepath});
                    });
                }
            });
        }
    });
}( jQuery ) );
