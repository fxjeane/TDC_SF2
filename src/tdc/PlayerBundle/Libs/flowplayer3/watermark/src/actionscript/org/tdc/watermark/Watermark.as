/* 
 * This file is part of Flowplayer, http://flowplayer.org 
 *
 * By: Anssi Piirainen, <support@flowplayer.org> 
 * Copyright (c) 2008, 2009 Flowplayer Oy
 * 
 * Released under the MIT License: 
 * http://www.opensource.org/licenses/mit-license.php 
 */
 
 package org.tdc.watermark {	
    import flash.display.Sprite;
	import flash.display.DisplayObject;
    import flash.text.TextField;
	import flash.text.TextFieldAutoSize;	
    import flash.text.TextFormat;
    import flash.utils.Timer;
	import flash.events.TimerEvent;
	import flash.filters.DropShadowFilter;

	import mx.effects.Fade;
	import org.flowplayer.model.Plugin;	
    import org.flowplayer.model.PluginModel;	
    import org.flowplayer.view.Flowplayer;		
    import org.flowplayer.view.AbstractSprite;
	import org.flowplayer.model.PlayerEvent;
	import org.flowplayer.model.ClipEvent;

	public class Watermark extends AbstractSprite implements Plugin { 
        // Internal objects
		private var _userText:TextField;
		private var _copyrightText:TextField;
		private var _model:PluginModel;
		private var _player:Flowplayer;
		private var _screenObj:DisplayObject;
		private var _wmShowTimer:Timer;
        private var _wmHideTimer:Timer;
        private var _format:TextFormat;
        
        private var _fadeOut:Fade;
        private var _fadeIn:Fade;
		        
		private const SMALLTEXT:Number = 0.04;
		private const LARGETEXT:Number = 0.05;
		
		// config variables
		private var _enable:Boolean;		
        private var _userName:String = "";
		private var _copyrightName:String = "";
		private var _hideTime:Number;
		private var _showTime:Number;
		private var _wmColor:uint = 0xff0000;
		private var _dropShadow:Boolean = false;
		private var _userScale:Number = 1;
		private var _copyrightScale:Number = 1;
		
        public function Watermark() {
		
		    visible = false;
		    _wmShowTimer    = new Timer(15000);
            _wmHideTimer    = new Timer(45000);
	        _wmShowTimer.addEventListener(TimerEvent.TIMER,hideUserWM);
	        _wmHideTimer.addEventListener(TimerEvent.TIMER,showUserWM);
	        
	        _fadeOut = new Fade();
	        _fadeOut.alphaFrom = 0.5;
	        _fadeOut.alphaTo = 0;
	        _fadeIn = new Fade();
	        _fadeIn.alphaFrom = 0;
	        _fadeIn.alphaTo = 0.5;		
        }
		
		public override function set width(newWidth:Number):void {			
            super.width = newWidth;		
        }		
        
        public override function set height(newHeight:Number):void {			
            super.width = newHeight;		
        }
	
	    override protected function onResize():void {			
            //setSize(width, height);			
            x = 0;			
            y = 0;		
        }
			
        private function createWMObjects():void {
            _format = new TextFormat();			
            _format.font = "Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Bitstream Vera, Verdana, Arial, _sans, _serif";			
            _format.size = 10;			
            _format.bold = true;
			_format.color = _wmColor;
			
			if (_userName != "") {
                _userText = new TextField();
			    _userText.defaultTextFormat = _format;			    
                _userText.text = _userName;
			    _userText.autoSize = TextFieldAutoSize.LEFT;
    		    addChild(_userText);
            }

            _format.size = 0.15 * Math.round(Math.pow(_screenObj.width,0.8));
			if (_copyrightName != "") {
                _copyrightText = new TextField();
			    _copyrightText.defaultTextFormat = _format;			    
                _copyrightText.text = _copyrightName;
			    _copyrightText.autoSize = TextFieldAutoSize.LEFT;			    
                addChild(_copyrightText);
            } 
            
            if (_dropShadow) {
               // Apply the drop shadow filter to the box.
                var shadow:DropShadowFilter = new DropShadowFilter();
                shadow.distance = 3;
                shadow.angle = 25;
                shadow.blurX = shadow.blurY = 7;
                filters = [shadow];
            }
            
            resizeText();
            _fadeOut.target = (this);
            _fadeIn.target = (this);
        }				
        // Gets the default configuration for this plugin. //
        public function getDefaultConfig():Object {			
            return {top:0,left:0,width:'100%'};		
        }
        
        public function onConfig(configProps:PluginModel):void {
			_model=configProps;
			_userName = _model.config.userName;
			_copyrightName = _model.config.copyrightName;
			
			if (_model.config.wmOpacity) {
			    _fadeOut.alphaFrom = _model.config.wmOpacity;
			    _fadeIn.alphaTo = _model.config.wmOpacity;
			}
			if (_model.config.wmColor)
			    _wmColor = _model.config.wmColor
			    			
			if (_model.config.showTime)
			    _wmShowTimer.delay = _model.config.showTime * 1000;
			if (_model.config.hideTime)
			    _wmHideTimer.delay = _model.config.hideTime * 1000; 
			if (_model.config.dropShadow)
			    _dropShadow = _model.config.dropShadow == 'true';    

			if (_model.config.userScale)
			    _userScale = _model.config.userScale; 
			if (_model.config.copyrightScale)
			    _copyrightScale = _model.config.copyrightScale;
	    }
		
        private function resizeText():void {
            _format.size = _userScale * 0.3 * Math.round(Math.pow(_screenObj.width,0.8));
            _userText.setTextFormat(_format);
            _format.size = _copyrightScale * 0.15 * Math.round(Math.pow(_screenObj.width,0.8));
            _copyrightText.setTextFormat(_format);
            
        }
        		
		private function onPlayerFullscreenEvent(event:PlayerEvent):void 
		{
            resizeText();
			centerMsg();
	        var fooevent:TimerEvent = new TimerEvent("void");
	        showUserWM(fooevent);
		}
		
		private function centerMsg():void{
    		x = 0; y = 0;
            	
            _copyrightText.y = (_player.currentClip.height ) - (_copyrightText.textHeight) - 10;
			_copyrightText.x = (_screenObj.width * 0.5)  - (_copyrightText.textWidth * 0.5);

		}
		
		private function startTimers(clip:ClipEvent):void
		{
            resizeText();
    		centerMsg();
		    var fooevent:TimerEvent = new TimerEvent("void");
	        showUserWM(fooevent);
		}

		private function stopTimers(clip:ClipEvent):void
		{
	        _fadeOut.play();
	        _wmHideTimer.stop();
	        _wmShowTimer.stop();
		}
		
        private function showUserWM(event:TimerEvent):void
        {
			visible = true; 
			var xDelta:Number = _screenObj.width - _player.currentClip.width;
			var yDelta:Number = _screenObj.height - _player.currentClip.height;
			
			var minX:Number = xDelta * 0.5 + 10;
	        var minY:Number = yDelta * 0.5 + 10;
	        var maxX:Number = _screenObj.width - _userText.textWidth - (xDelta * 0.5) - 10;
	        var maxY:Number = _screenObj.height - _userText.textHeight - 
	                          _copyrightText.textHeight - (yDelta * 0.5) - 10;
	        var xval:Number = Math.round(Math.random() * maxX);
	        var yval:Number = Math.round(Math.random() * maxY);
	        
	        _userText.x = Math.max(minX,Math.min(maxX,xval));
	        _userText.y = Math.max(minY,Math.min(maxY,yval));
	
	        _fadeIn.play();;
	        _wmHideTimer.stop();
	        _wmShowTimer.start();
		
        }

        private function hideUserWM(event:TimerEvent):void
        {
            _fadeOut.play();
	        _wmHideTimer.start();
	        _wmShowTimer.stop();
        }

        public function onLoad(player:Flowplayer):void {
			_player = player;
			_screenObj = _player.screen.getDisplayObject();
			player.onFullscreen(onPlayerFullscreenEvent);
			player.onFullscreenExit(onPlayerFullscreenEvent);
			player.playlist.onStart(startTimers);
			player.playlist.onResume(startTimers);
			player.playlist.onPause(stopTimers);
			player.playlist.onStop(stopTimers);
			player.playlist.onFinish(stopTimers);
			createWMObjects();
			_model.dispatchOnLoad();		
            }	
        }
}
