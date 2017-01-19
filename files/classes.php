<?php
	class Main {
		private $content;
		public $title;
		public $view;

		public function set_content($text) {
			$this -> content = $text;
		}

		public function get_content() {
			return $this -> content;
		}

		public function set_title($text) {
			$this -> title = $text;
		}

		public function get_title() {
			echo $this -> title;
		}
		public function set_view($name) {
			$this -> view = $name;
		}

	}
?>
