var Responsivr;

(function($) {
	Responsivr = (function() {
		var $t = this;
		
		$t.__state = '';
		
		$t.__defaultState = '';
		
		$t.__sizes = {};
		
		$t.__defaultBehavior = {};
		
		$t.__callEnter = function(key) {
			if (key === '' || key === $t.__getDefaultBehaviorState()) {
				if ($t.__defaultBehavior.hasOwnProperty("enter")) {
					$t.__defaultBehavior.enter();
				}
			} else {
				if ($t.__sizes.hasOwnProperty(key)
						&& $t.__sizes[key].hasOwnProperty("enter")) {
					$t.__sizes[key].enter();
				}
			}
		};
		
		$t.__callExit = function(key) {
			if (key === '' || key === $t.__getDefaultBehaviorState()) {
				if ($t.__defaultBehavior.hasOwnProperty("exit")) {
					$t.__defaultBehavior.exit();
				}
			} else {
				if ($t.__sizes.hasOwnProperty(key)
						&& $t.__sizes[key].hasOwnProperty("exit")) {
					$t.__sizes[key].exit();
				}
			}
		};
		
		$t.__getMode = function() {
			var mode = $t.__getDefaultBehaviorState();
			var minimalSize = -1;
			var width = $(window).width();
			for (var size in $t.__sizes) {
				var currentSize = $t.__sizes[size];
				if (width < currentSize.upTo && (minimalSize > currentSize.upTo || minimalSize == -1)) {
					mode = size;
					minimalSize = currentSize.upTo;
				}
			}
			return mode;
		};
		
		$t.__response = function() {
			var oldState = $t.__state;
			var newState = $t.__getMode();
			if (oldState != newState) {
				$t.__state = newState;
				$t.__callExit(oldState);
				$t.__callEnter(newState);
			}
		};
		
		$t.__setDefaultBehaviorState = function() {
			$t.__defaultState = '';
			if ($t.__defaultBehavior.hasOwnProperty("alias")) {
				$t.__defaultState = $t.__defaultBehavior.alias;
			}
		};
		
		$t.__getDefaultBehaviorState = function() {
			return $t.__defaultState;
		};
		
		$t.run = function(sizes, defaultBehavior, initCallback) {
			$t.__sizes = sizes;
			$t.__defaultBehavior = defaultBehavior;
			$t.__setDefaultBehaviorState();
			$t.__state = $t.__getDefaultBehaviorState();

			$(window).on('resize', $t.__response);
			if (initCallback !== null) {
				initCallback();
			}
		};
		
		$t.is = function(mode) {
			var currentMode = $t.__getMode();
			return (mode === currentMode);
		};
		
		$t.isNot = function(mode) {
			var currentMode = $t.__getMode();
			return (mode !== currentMode);
		};
		
		return this;
	})();
})(jQuery);