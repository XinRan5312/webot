<?php
	include_once('config.php');
	include_once('saetv2.ex.class.php');

	class WeiboPoster {
		var $c;
    var $memcache;

		function __construct($allow_debug = false) {
        $this->memcache = new Memcache;
        $this->memcache->connect(MC_HOST, 11211) or die ("Could not connect");

        if ( !$this->memcache->get(MC_KEY) ) {
              $o = new SaeTOAuthV2( WB_AKEY , WB_SKEY );
              $code_url = $o->getAuthorizeURL( WB_CALLBACK_URL );
if ( $allow_debug ) {
   echo '<a href="' . $code_url . '">' . $code_url . '</a><br /><br />';
}
        }

        $this->c = new SaeTClientV2( WB_AKEY, WB_SKEY, $this->memcache->get(MC_KEY));
        $this->c->set_debug($allow_debug);
		}

		public function update($description) {
			$result = $this->c->update( 
			   '微信网友说: ' . $description);
		}

		public function post($username, $imageUrl, $description) {
			$result = $this->c->upload( 
			   '微信网友说: ' . $description,
                           $imageUrl);
		}
	}
?>