<?php
	require 'WeiboPoster.php';

	class ImagePoster {
		var $bigArray;
		var $weibo;

		function __construct() {
			$this->bigArray = new Memcache;
      $this->bigArray->connect(MC_HOST, 11211) or die ("Could not connect");
			$this->weibo = new WeiboPoster();
		}

		public function addImage($username, $imageUrl) {
			$this->bigArray->set($username, $imageUrl);
		}

		public function addText($username, $description) {
			if ( $this->hasUser($username) ) {
				$this->weibo->post($username, $this->bigArray->get($username), $description);
				$this->bigArray->delete($username);
			}
		}

		public function hasUser($username) {
			return $this->bigArray->get($username);
		}

		public function get($username) {
			return $this->bigArray->get($username);
		}

		private function isValueUrl($value) {
			return !strncmp($value, 'http://', strlen('http://'));
		}
	}
?>