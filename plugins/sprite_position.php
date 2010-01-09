<?php


	/**
	 * Automatic position computing for simple sprites
	 * Makes use of css sprites even more verbose than before, but takes care of the calculations for you
	 * 
	 * Usage:
	 * #elementA { -cssp-sprite:width height; -cssp-sprite-position:row column; }
	 * 
	 * Example of a 256*256 pixel 2*2 image sprite:
	 * #elementA { -cssp-sprite:128px 128px; -cssp-sprite-position:1 1; } Top left 128*128 section
	 * #elementB { -cssp-sprite:128px 128px; -cssp-sprite-position:1 2; } Top right 128*128 section
	 * #elementC { -cssp-sprite:128px 128px; -cssp-sprite-position:2 1; } Bottom left 128*128 section
	 * #elementD { -cssp-sprite:128px 128px; -cssp-sprite-position:2 2; } Bottom right 128*128 section
	 * 
	 * Status:  Stable
	 * Version: 1.0
	 * 
	 * @param mixed &$parsed
	 * @return void
	 */
	function sprite_position(&$parsed){
		foreach($parsed as $block => $css){
			foreach($parsed[$block] as $selector => $styles){
				if(isset($parsed[$block][$selector]['-cssp-sprite']) && isset($parsed[$block][$selector]['-cssp-sprite-position'])){
					//$sprite = explode(' ', trim($parsed[$block][$selector]['sprite']));
					$unitpattern = '/(.*?)(%|em|ex|px|in|cm|mm|pt|pc)(.*?)(%|em|ex|px|in|cm|mm|pt|pc)/';
					preg_match_all($unitpattern ,$parsed[$block][$selector]['-cssp-sprite'], $sprite);
					if(count($sprite) == 5){ // 5 matches = useful "sprite" value
						$sectionWidth = trim($sprite[1][0]);
						$sectionHeight = trim($sprite[3][0]);
						$sections = explode(' ', trim($parsed[$block][$selector]['-cssp-sprite-position']));
						$x = ($sectionWidth * $sections[0] - $sectionWidth) *-1;
						$y = ($sectionHeight * $sections[1] - $sectionHeight) *-1;
						if($x != 0){
							$x .= $sprite[2][0];
						}
						if($y != 0){
							$y .= $sprite[4][0];
						}
						$parsed[$block][$selector]['background-position'] = $x.' '.$y;
						unset($parsed[$block][$selector]['-cssp-sprite']);
						unset($parsed[$block][$selector]['-cssp-sprite-position']);
					}
				}
			}
		}
	}


?>