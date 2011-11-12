tdc = window.tdc || {};

tdc.loadSubModules = function (){
    var source = Array.apply(null, arguments);
    var script = document.createElement('script');
    script.setAttribute('src', source.shift());
    script.setAttribute('type', 'text/javascript');
    document.getElementsByTagName('head')[0].appendChild(script);
    if(source.length)arguments.callee.apply(null, source);

};

//tdc.addTag = document.createElement;

// GS Constants
tdc.MIN_LEVEL=1;
tdc.MAX_LEVEL=40;
tdc.MAX_LEVEL_DIFFERENCE = 4;
