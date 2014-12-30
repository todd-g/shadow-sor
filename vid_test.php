<?php

require_once('includes/opener.php');



?>
<div class="row">
  <div class="large-8 columns">
   
  
<div id="widget_2880" class="widget_7 ">          
        <!-- chrome -->
                  
  <!-- common to all embeds -->
  <link rel="stylesheet" href="http://blive-cdn.bproductions.com/public/css/flowplayer/minimalist.css?cb=7633">
  <link rel="stylesheet" href="http://blive-cdn.bproductions.com/public/css/blive-player.css?cb=7633">
  <script src="http://blive.bproductions.com/public/js/blive/blive-player.js"></script>
  <script type="text/javascript">
    var options_autoplay    = true;
    var options_mute      = false;

      

  </script>
  
  <style>
  
    .blive_player
    {
      background-color:#111111;
        width: 100%;
        height: 100%;
        /*height: auto !important;*/
        height: auto;
        min-height:100%;
    }
  
    .fp-fullscreen {
      top:auto !important;
      bottom:-3px !important;
      right:1px !important;
      z-index:11 !important;
    }
    .is-touch .fp-fullscreen {
      display: none;
    }
    .fp-volume {
      right:20px !important;
      width:80px !important;
    }
    .fp-volumeslider {width: 68px !important;}

    .floater {

      position: fixed !important;
      bottom:80px !important;
      background: #fff !important;
      padding: 10px !important;
      right:10px !important;
      width: 200px !important;

    }

    .player_wrapper {
      position:absolute; width: 100%; height: auto; 
    }

    #double_wrap {position:relative; display: inline-block; width: 100%; height: auto;}
  </style>

  <div id="double_wrap">

    <div id="blive_player_2087_wrapper" class="player_wrapper">
      <object type="application/x-shockwave-flash" data="http://blive-cdn.bproductions.com/public/js/jwplayer/jwplayer.flash.swf" width="100%" height="100%" bgcolor="#000000" id="blive_player_2087" name="blive_player_2087" tabindex="0" style="position: absolute;"><param name="allowfullscreen" value="true"><param name="allowscriptaccess" value="always"><param name="seamlesstabbing" value="true"><param name="wmode" value="opaque"></object>
      <div id="blive_player_2087_aspect" style="display: block; margin-top: 56.25%;"></div>
      <div id="blive_player_2087_jwpsrv" style="position: absolute; top: 0px; z-index: 10;"></div>
    </div>
    <div id="player_detector" style="z-index:1; height:1px; width:1px; position:absolute; top:350px; left:0px;"></div>
  </div><!-- /end double wrap -->
  
  <!-- protocol specific tpls :: rtmp -->
      <style>
  .blive_player 
  {
    background-image:url(http://blive-static-cdn.bproductions.com/video-thumbnails/production_886_2880.jpg?t=1403544075);
    background-repeat: no-repeat;
     -webkit-background-size: cover;
     -moz-background-size: cover;
     -o-background-size: cover;
     background-size: cover;

     position:absolute;
  }
</style>

<script type="text/javascript" src="http://blive-cdn.bproductions.com/public/js/jwplayer/jwplayer.js?cb=7633"></script>
<script type="text/javascript">jwplayer.key="+fh+xIC5PCP6Tc4LQYqLHUbc5QDV43y9GHZHNoRHyd4=";</script>

<script type="text/javascript">
  $(function() 
    {
      var jw_2087_playheadTime = 0;
    
        var jw_2087 = jwplayer("blive_player_2087").setup(
        {
analytics: {
enabled: false,
cookies: false
},
              // content

              playlist        : [
            {
                  
                                  image         : "http://blive-static-cdn.bproductions.com/video-thumbnails/production_886_2880.jpg?t=1403544075",
                            
                              file    : "http://blive-video.bproductions.com/000886-002087-pu5/hls/desktop-playlist.m3u8",
              
                  provider  : "http://blive-cdn.bproductions.com/public/js/jwplayer/HLSProvider6.swf?cb=7633&ext=.swf",
              type    : "hls"
            }
          ],  
          
          // HLSProvider settings
          hls_debug         : true,
          hls_debug2        : false,
          hls_minbufferlength   : 10,
          hls_maxbufferlength   : 120,
          hls_startfromlowestlevel: false,
          
              // sizing
              aspectratio       : "16:9",
              width         : "100%",
              
              // settings
              autostart       : options_autoplay,
              mute          : options_mute,
              
              // player design, details
              //skin          : 'http://blive.bproductions.com/public/js/jwplayer/skins/glow.xml',
              skin: 'glow',
              abouttext       : 'Video streaming by BLive',
              aboutlink       : 'http://blivedigital.com'
          }
      );

      video_players[2880] = new BlivePlayer(
          {
            'playFunction'    : function(){ jwplayer("blive_player_2087").play(true); }
          , 'pauseFunction'   : function(){ jwplayer("blive_player_2087").pause(); }
          , 'seekFunction'    : function(seconds){ 
                          jwplayer("blive_player_2087").seek(seconds); 
                        }
          , 'getTimeFunction'   : function(){ return jw_2087_playheadTime; }
          , 'getDurationFunction' : function(){ return jwplayer("blive_player_2087").getDuration(); }
          , 'getFinishedFunction' : function(){ return jwplayer("blive_player_2087").onComplete(); }
          , 'video_asset_id'    : 2087
          }   
        );

      jw_2087.onReady(function() {
        video_players[2880].sendLoadEvent();
        speed_app.pushAnalyticsEvent(20, 'jw player');
        console.log("sent comment event");
      });

      jw_2087.onPlay(function() {
        video_players[2880].sendPlayEvent();
      });

      jw_2087.onComplete(function() {
        video_players[2880].sendFinishedEvent();
      });

      jw_2087.onSeek(function() {
        video_players[2880].seekCallback();
      });

      jw_2087.onTime(function(event) {
        var newTime = 0;

        if(parseFloat(event.position) > 0)
        {
          newTime = parseFloat(event.position);
        } else if (parseFloat(event.offset) > 0) {
          newTime = parseFloat(event.offset);
        }
                
        jw_2087_playheadTime = newTime;
      });
    
      });
</script>   
                                                                                          
        </div>


        <div style="height:3000px; position:relative; display:block; width:30px;">
        
        </div>

  </div>
</div>
<script>

$('#mover').click(function(){

  reshapePlayer(1);

});

$('#unmover').click(function(){

  reshapePlayer(0);

});


function reshapePlayer(toggle){

  if(toggle == 1){
    

      $('.blive_iframe').attr("style","width:340px; height:194px; opacity:0.8; position:fixed; bottom:20px; right:20px; border:5px solid #000; -webkit-box-shadow: 0 0 3px 3px #ccc;box-shadow: 0 0 3px 3px #ccc; ");
      


  } else {
     $('.blive_iframe').attr("style","");
  }



}


(function ($) {

   var $window = $(window),
       _watch = [],
       _buffer;

   function test($el) {
      var docViewTop = $window.scrollTop(),
          docViewBottom = docViewTop + $window.height(),
          elemTop = $el.offset().top,
          elemBottom = elemTop + $el.height();

      return ((elemBottom >= docViewTop) && (elemTop <= docViewBottom)
                && (elemBottom <= docViewBottom) &&  (elemTop >= docViewTop) );
   }

   $window.on('scroll', function ( e ) {

         if ( !_buffer ) {

            _buffer = setTimeout(function () {

               checkInView( e );

               _buffer = null;

            }, 300);
         }

      });

   function checkInView( e ) {

      $.each(_watch, function () {

         if ( test( this.element ) ) {
            if ( !this.invp ) {
               this.invp = true;
               if ( this.options.scrolledin ) 
                   this.options.scrolledin.call( this.element, e );
                
               this.element.trigger( 'scrolledin', e );
            }
         } else if ( this.invp ) {
            this.invp = false;
            if ( this.options.scrolledout ) 
                this.options.scrolledout.call( this.element, e );
             
            this.element.trigger( 'scrolledout', e );
         }
      });
   }

   function monitor( element, options ) {
      var item = { element: element, options: options, invp: false };
      _watch.push(item);
      return item;
   }

   function unmonitor( item ) {
      for ( var i=0;i<_watch.length;i++ ) {
         if ( _watch[i] === item ) {
            _watch.splice( i, 1 );
            item.element = null;
            break;
         }
      }
      console.log( _watch );
   }

   var pluginName = 'scrolledIntoView',
       settings = {
         scrolledin: null,
         scrolledout: null
       }


   $.fn[pluginName] = function( options ) {

         var options = $.extend({}, settings, options);

         this.each( function () {

               var $el = $(this),
                   instance = $.data( this, pluginName );

               if ( instance ) {
                  instance.options = options;
               } else {
                  $.data( this, pluginName, monitor( $el, options ) );
                  $el.on( 'remove', $.proxy( function () {

                        $.removeData(this, pluginName);
                        unmonitor( instance );

                     }, this ) );
               }
            });

         return this;
      }


})(jQuery);


$('#player_detector')
      .scrolledIntoView()
      .on('scrolledin', function () { 
              console.log( 'player in'  );
              reshapePlayer(0);
              //jwplayer("blive_player_2087").play(true);

               })
      .on('scrolledout', function () { 
              console.log( 'player out' ); 
                reshapePlayer(1);
                //jwplayer("blive_player_2087").pause(true);
            });



</script>

    
<?php require_once('includes/closer.php'); ?>
