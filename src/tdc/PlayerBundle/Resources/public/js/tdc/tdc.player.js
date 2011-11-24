(function( $ ) {
    $.widget( "tdc.tdcPlayer", {
        options:{
            videoId:null,
            pageRoot:"",
            spacer:5,
            cornerRadius:"5px",
            screen: {
                height:"500px"
            },
            navBar: {
                width:"200px"
                }
        },
        _create: function() {
            var self = this;
            var e = this.element;
            var o = this.options;

            // Create the nav bar
            var navBar = $('<div id="tdcNavBar">');
            navBar.hide();
            navBar.css("margin",o.spacer);
            e.prepend(navBar);
            $(navBar).tdcNavbar({
                width:o.navBar.width,
                height:o.screen.height,
                cornerRadius:o.cornerRadius
            });

            // Create the video player
            rootUrl = o.pageRoot;
            rootUrl = rootUrl.substring(0,rootUrl.lastIndexOf("/"));
            rootUrl = rootUrl.substring(0,rootUrl.lastIndexOf("/"));
            flowplayer("tdcVideoScreen", 
                {
                    src:rootUrl+"/flowplayer/flowplayer-3.2.7.swf",
                    wmode:'transparent',
                },
                {
                    "canvas":
                    {
                        backgroundColor: '#000000',
                        backgroundGradient: 'none',
                        borderRadius:'22',
                        border:'0px solid #000000' 
                    },
                    "clip":
                    {
                        "url":"http://vod01.netdna.com/vod/demo.flowplayer/Extremists.flv",
                        "scaling":"fit",
                        "autoPlay":false
                    },
                    "plugins":
                    {
                        "rtmp":
                        {
                            "netConnectionUrl":"rtmp://rtmp01.hddn.com/fpplay",
                            "url":"flowplayer.rtmp-3.2.3.swf"
                        },
                        "controls": {

                              // location of the controlbar plugin
                             url: 'flowplayer.controls-3.2.5.swf',
                             // display properties such as size, location and opacity
                             opacity: 0.8,
                             borderRadius:15,
                             // styling properties (will be applied to all plugins)
                             backgroundGradient: 'low',
                             onHidden: function(){
                                var content = $f().getPlugin("myContent");
                                content.animate({"opacity":0});
                             },
                             onShowed: function(){
                                var content = $f().getPlugin("myContent");
                                content.animate({"opacity":1});
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
                            html:"<img src=\"https://www.activaterewards.com/img/icons/close-window-icon.png\" />",
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
        },
        toggleNavBar: function(){
            var e = this.element;
            var o = this.options;
            var videoScreen = e.children('#tdcVideoScreen');
            var navBar = e.children('#tdcNavBar');
            var nbBorderL = parseInt(navBar.css("border-left-width"));
            var nbBorderR = parseInt(navBar.css("border-right-width"));
            if (parseInt(videoScreen.css("margin-left")) === o.spacer) {
            videoScreen.animate({
                                "width":videoScreen.width() - navBar.width() - o.spacer,
                                "margin-left":navBar.width() + (2 * o.spacer) + nbBorderL+ nbBorderR
                                },500,function(){
                                    navBar.fadeIn();
                                });
            } else {
            navBar.fadeOut(500,function(){
                            videoScreen.animate({
                                "width":videoScreen.width() + navBar.width() + o.spacer,
                                "margin-left":o.spacer
                                },500);
                          });


            }
        },
        toggleVideoInfo: function() {
            var showSize = 150;
            var header = $("#headerBar");
            if (header.height() < showSize) {
                header.animate({
                    height:150
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
                $(".videoTitleName").html(data.name);
                $(".videoTitleName").click(function(){
                    self.toggleVideoInfo();
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
                tocTokens = data.toc.split(",");
                navobj = {};
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
                $("#tdcNavBar").tdcNavbar("loadData",navobj);
            });
        }
    });
}( jQuery ) );
