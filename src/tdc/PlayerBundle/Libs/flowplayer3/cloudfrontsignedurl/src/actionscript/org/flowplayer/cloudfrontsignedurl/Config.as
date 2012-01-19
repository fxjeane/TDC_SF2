/*
 * This file is part of Flowplayer, http://flowplayer.org
 *
 * By: Thomas Dubois, thomas _at_ flowplayer.org
 * Copyright (c) 2010 Flowplayer Oy
 *
 * Released under the MIT License:
 * http://www.opensource.org/licenses/mit-license.php
 */

package org.flowplayer.cloudfrontsignedurl {

    public class Config {
        private var _privateKey:String = "-----BEGIN RSA PRIVATE KEY-----MIICXAIBAAKBgQCGTznQ0QQ0R+upONObVMdF+ExHfICm/9oykz1aiSmROYC8dyDqLv81s78xIi5MYOKmCwtDM7B1aAi2CdzobGUu8UiP3FNDa0wyqSFRBLFdJYP2TvieUBQdLf5huVzgTR5WwXkP7cXITS4TyhPynUgS2PHQQNkPLlLr3bJhdLLmkQIDAQABAoGAOUJlf6Tcif1vdsGHVV2bZzUoMAHgR1IkkBM9wO9hDUzamX6gRbarjxWGmUfdPSrA9dEXiBrtS+CCdjlWOLCdofYqyXf+0F6d2IbRLNtL4dCejWL6tYVR7vkk1dKiGGvKT0b0tma/sg1xUb40D3szVboCPcTo9FsZm+9+4RhCUAECQQDwzWAOpSNCbaCoqYK+JdrxyutS+gXK0rSLJTJr+7iipuZkF8h6A6b6j63SmrI3E/c8cXWQFwWH6nKhmainMRhRAkEAjslBJmg7FZI8fcuXL7SL339yiwnXvc+3xZgj+3e0DfubdBpiuYVIrKMGDuBefuE72dqNRnK3L+aYTcXQcyuaQQJBAJvxxQV2+JCqgmL39A3EYjg4W5HPDTU+o2GBY1f/GqFrSMUFifVKrDaUGdPMDyIQMgrYx3PbFN/iev4gjtJHvyECQHpDQCMX+we0iVGZ4+I4cdC1e6osimyAkbaDWMQ5spaKjIj1EvYSd7FDgqt4WdHBIROg/XmGPu+oP1/Q83W6r0ECQDXieZVQqk75RknGvs9zkaGAp7YlqJWw7gmxQHJVe9zCmeywCaflv47pL0WhxVnMmacdOaBLDuO55N5DSkwDDas=-----END RSA PRIVATE KEY-----";
		private var _keyPairId:String  = "APKAJSVZYWMGYS4NSWKQ";		
		private var _timeToLive:Number = 5*60;
		private var _domains:Array	   = [];
		
		
        public function get privateKey():String {
            return _privateKey;
        }

        public function set privateKey(val:String):void {
            _privateKey = val;
        }

		public function get keyPairId():String {
            return _keyPairId;
        }

        public function set keyPairId(val:String):void {
            _keyPairId = val;
        }

        public function toString():String {
            return "[Config] keyPairId = '" + _keyPairId + "'";
        }

        public function get timeToLive():Number {
            return _timeToLive;
        }

        public function set timeToLive(val:Number):void {
            _timeToLive = val;
        }

        public function get domains():Array {
            return _domains;
        }

        public function set domains(value:Array):void {
            _domains = value;
        }
    }
}
