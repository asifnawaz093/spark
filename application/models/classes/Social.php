<?php
class Social{
    
    public function fbInstane(){
        require_once("/facebook/src/facebook.php");
        $config = array(
            'appId' => '623987157712991',
            'secret' => 'bc2c3469723a15b2302cb15079062186',
            'fileUpload' => true, // optional
            'allowSignedRequest' => false, // optional, but should be set to false for non-canvas apps
        );
        return new Facebook($config);
    }
    
    public function isFbLoggedIn(){
        $facebook = $this->fbInstane();
        return $facebook->getUser();
    }
    
    public function fbLoginUrl(){
        $facebook = $this->fbInstane();
        return "<a href='".$facebook->getLoginUrl()."'>Login With Facebook</a>";
    }
    
    
    public function twitterShare(){
        return '<a href="https://twitter.com/share" class="twitter-share-button" data-url=""
            data-via="Visiomi" data-lang="en" data-related="anywhereTheJavascriptAPI" data-count="none">Tweet</a>
            <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="https://platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");
            </script>';

    }
    
    
    public function linkedInShare(){
        return '<script src="//platform.linkedin.com/in.js" type="text/javascript">
            lang: en_US
          </script>
          <script type="IN/Share"></script>';
    }
    
   public function googleShare(){
        return 
            "<div class='g-plus' data-action='share'></div>
            <script type='text/javascript'>
              (function() {
                var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
                po.src = 'https://apis.google.com/js/platform.js';
                var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
              })();
            </script>";
    }
    
    public function googleRecommend(){
        return "
        <div class='g-plusone' data-annotation='none' data-size='medium' data-width='300'></div>
        <script type='text/javascript'>
          (function() {
            var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
            po.src = 'https://apis.google.com/js/platform.js';
            var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
          })();
        </script>";
    }
    
    public function FbShare($page){
        //return "<div id='fb-root'></div>
        //    <script>
        //    window.fbAsyncInit = function() {
        //    FB.init({appId: '1510632212507430', status: true, cookie: true,
        //    xfbml: true});
        //    };
        //    (function() {
        //    var e = document.createElement('script'); e.async = true;
        //    e.src = document.location.protocol +
        //    '//connect.facebook.net/en_US/all.js';
        //    document.getElementById('fb-root').appendChild(e);
        //    }());
        //    </script>";
        return "<a href='#' onclick=\"
          window.open(
            'https://www.facebook.com/sharer/sharer.php?u=$page', 
            'facebook-share-dialog', 
            'width=626,height=436'); 
          return false;\">
        <img class='my_social_width' src='https://bylyngo.com/dev/images/fb-footer.png' alt='fb'> </a>";
    }
    
    
    public function likeButtons($page='',$show_faces=false,$send=true){
        return '<div id="fb-root"></div>
            <script>(function(d, s, id) {
              var js, fjs = d.getElementsByTagName(s)[0];
              if (d.getElementById(id)) return;
              js = d.createElement(s); js.id = id;
              js.src = "//connect.facebook.net/en_US/all.js#xfbml=1&appId=150194128524761";
              fjs.parentNode.insertBefore(js, fjs);
            }(document, "script", "facebook-jssdk"));</script>
            
            <div class="fb-like" data-href="" data-width=""
            data-height="" data-colorscheme="light" data-layout="button_count" data-action="like"
            data-show-faces="'.$show_faces.'" data-send="'.$send.'"></div>
            ';
    }
    
      public function share(){
        return "<span class='st_facebook_large' displayText='Facebook'></span>
                <span class='st_twitter_large' displayText='Tweet'></span>
                <span class='st_linkedin_large' displayText='LinkedIn'></span>
                <span class='st_googleplus_large' displayText='Google +'></span>
                <span class='st_blogger_large' displayText='Blogger'></span>
                <span class='st_tumblr_large' displayText='Tumblr'></span>
                <span class='st_pinterest_large' displayText='Pinterest'></span>
                <span class='st_email_large' displayText='Email'></span>";
    }
    
    
    
    public function share2($sipath){
        return '<span style="margin-top:-10px;display:inline-block; position:relative; top:-6px;">' . $this->FbShare($sipath) . '</span> <span style="margin-top:10px;display:inline-block;">' . $this->googleRecommend() . " " . $this->linkedInShare() . " " . $this->twitterShare() . "</span>"; 
    }
    
    public function commentBox($page=""){ 
        return '<div id="fb-root"></div>
            <script>(function(d, s, id) {
              var js, fjs = d.getElementsByTagName(s)[0];
              if (d.getElementById(id)) return;
              js = d.createElement(s); js.id = id;
              js.src = "//connect.facebook.net/en_US/all.js#xfbml=1&appId=150194128524761";
              fjs.parentNode.insertBefore(js, fjs);
            }(document, "script", "facebook-jssdk"));
            </script>
                    <div class="fb-comments" data-href="'.$page.'" data-colorscheme="light"
                    data-numposts="10" data-width="650px"></div>
                    <script>$(".postToProfileCheckbox").attr("checked",true);</script>';

    }
    
}