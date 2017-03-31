'use strict';

exports.__esModule = true;

var _component = require('../component');

var _component2 = _interopRequireDefault(_component);

var _events = require('../utils/events.js');

var Events = _interopRequireWildcard(_events);

var _fn = require('../utils/fn.js');

var Fn = _interopRequireWildcard(_fn);

var _log = require('../utils/log.js');

var _log2 = _interopRequireDefault(_log);

var _tuple = require('safe-json-parse/tuple');

var _tuple2 = _interopRequireDefault(_tuple);

var _window = require('global/window');

var _window2 = _interopRequireDefault(_window);

function _interopRequireWildcard(obj) { if (obj && obj.__esModule) { return obj; } else { var newObj = {}; if (obj != null) { for (var key in obj) { if (Object.prototype.hasOwnProperty.call(obj, key)) newObj[key] = obj[key]; } } newObj['default'] = obj; return newObj; } }

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { 'default': obj }; }

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _possibleConstructorReturn(self, call) { if (!self) { throw new ReferenceError("this hasn't been initialised - super() hasn't been called"); } return call && (typeof call === "object" || typeof call === "function") ? call : self; }

function _inherits(subClass, superClass) { if (typeof superClass !== "function" && superClass !== null) { throw new TypeError("Super expression must either be null or a function, not " + typeof superClass); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, enumerable: false, writable: true, configurable: true } }); if (superClass) Object.setPrototypeOf ? Object.setPrototypeOf(subClass, superClass) : subClass.__proto__ = superClass; } /**
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                * @file text-track-settings.js
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                */


function captionOptionsMenuTemplate(uniqueId, dialogLabelId, dialogDescriptionId) {
  var template = '\n    <div role="document">\n      <div role="heading" aria-level="1" id="' + dialogLabelId + '" class="vjs-control-text">Captions Settings Dialog</div>\n      <div id="' + dialogDescriptionId + '" class="vjs-control-text">Beginning of dialog window. Escape will cancel and close the window.</div>\n      <div class="vjs-tracksettings">\n        <div class="vjs-tracksettings-colors">\n          <fieldset class="vjs-fg-color vjs-tracksetting">\n            <legend>Text</legend>\n            <label class="vjs-label" for="captions-foreground-color-' + uniqueId + '">Color</label>\n            <select id="captions-foreground-color-' + uniqueId + '">\n              <option value="#FFF" selected>White</option>\n              <option value="#000">Black</option>\n              <option value="#F00">Red</option>\n              <option value="#0F0">Green</option>\n              <option value="#00F">Blue</option>\n              <option value="#FF0">Yellow</option>\n              <option value="#F0F">Magenta</option>\n              <option value="#0FF">Cyan</option>\n            </select>\n            <span class="vjs-text-opacity vjs-opacity">\n              <label class="vjs-label" for="captions-foreground-opacity-' + uniqueId + '">Transparency</label>\n              <select id="captions-foreground-opacity-' + uniqueId + '">\n                <option value="1" selected>Opaque</option>\n                <option value="0.5">Semi-Opaque</option>\n              </select>\n            </span>\n          </fieldset>\n          <fieldset class="vjs-bg-color vjs-tracksetting">\n            <legend>Background</legend>\n            <label class="vjs-label" for="captions-background-color-' + uniqueId + '">Color</label>\n            <select id="captions-background-color-' + uniqueId + '">\n              <option value="#000" selected>Black</option>\n              <option value="#FFF">White</option>\n              <option value="#F00">Red</option>\n              <option value="#0F0">Green</option>\n              <option value="#00F">Blue</option>\n              <option value="#FF0">Yellow</option>\n              <option value="#F0F">Magenta</option>\n              <option value="#0FF">Cyan</option>\n            </select>\n            <span class="vjs-bg-opacity vjs-opacity">\n              <label class="vjs-label" for="captions-background-opacity-' + uniqueId + '">Transparency</label>\n              <select id="captions-background-opacity-' + uniqueId + '">\n                <option value="1" selected>Opaque</option>\n                <option value="0.5">Semi-Transparent</option>\n                <option value="0">Transparent</option>\n              </select>\n            </span>\n          </fieldset>\n          <fieldset class="window-color vjs-tracksetting">\n            <legend>Window</legend>\n            <label class="vjs-label" for="captions-window-color-' + uniqueId + '">Color</label>\n            <select id="captions-window-color-' + uniqueId + '">\n              <option value="#000" selected>Black</option>\n              <option value="#FFF">White</option>\n              <option value="#F00">Red</option>\n              <option value="#0F0">Green</option>\n              <option value="#00F">Blue</option>\n              <option value="#FF0">Yellow</option>\n              <option value="#F0F">Magenta</option>\n              <option value="#0FF">Cyan</option>\n            </select>\n            <span class="vjs-window-opacity vjs-opacity">\n              <label class="vjs-label" for="captions-window-opacity-' + uniqueId + '">Transparency</label>\n              <select id="captions-window-opacity-' + uniqueId + '">\n                <option value="0" selected>Transparent</option>\n                <option value="0.5">Semi-Transparent</option>\n                <option value="1">Opaque</option>\n              </select>\n            </span>\n          </fieldset>\n        </div> <!-- vjs-tracksettings-colors -->\n        <div class="vjs-tracksettings-font">\n          <div class="vjs-font-percent vjs-tracksetting">\n            <label class="vjs-label" for="captions-font-size-' + uniqueId + '">Font Size</label>\n            <select id="captions-font-size-' + uniqueId + '">\n              <option value="0.50">50%</option>\n              <option value="0.75">75%</option>\n              <option value="1.00" selected>100%</option>\n              <option value="1.25">125%</option>\n              <option value="1.50">150%</option>\n              <option value="1.75">175%</option>\n              <option value="2.00">200%</option>\n              <option value="3.00">300%</option>\n              <option value="4.00">400%</option>\n            </select>\n          </div>\n          <div class="vjs-edge-style vjs-tracksetting">\n            <label class="vjs-label" for="captions-edge-style-' + uniqueId + '">Text Edge Style</label>\n            <select id="captions-edge-style-' + uniqueId + '">\n              <option value="none" selected>None</option>\n              <option value="raised">Raised</option>\n              <option value="depressed">Depressed</option>\n              <option value="uniform">Uniform</option>\n              <option value="dropshadow">Dropshadow</option>\n            </select>\n          </div>\n          <div class="vjs-font-family vjs-tracksetting">\n            <label class="vjs-label" for="captions-font-family-' + uniqueId + '">Font Family</label>\n            <select id="captions-font-family-' + uniqueId + '">\n              <option value="proportionalSansSerif" selected>Proportional Sans-Serif</option>\n              <option value="monospaceSansSerif">Monospace Sans-Serif</option>\n              <option value="proportionalSerif">Proportional Serif</option>\n              <option value="monospaceSerif">Monospace Serif</option>\n              <option value="casual">Casual</option>\n              <option value="script">Script</option>\n              <option value="small-caps">Small Caps</option>\n            </select>\n          </div>\n        </div> <!-- vjs-tracksettings-font -->\n        <div class="vjs-tracksettings-controls">\n          <button class="vjs-default-button">Defaults</button>\n          <button class="vjs-done-button">Done</button>\n        </div>\n      </div> <!-- vjs-tracksettings -->\n    </div> <!--  role="document" -->\n  ';

  return template;
}

function getSelectedOptionValue(target) {
  var selectedOption = void 0;

  // not all browsers support selectedOptions, so, fallback to options
  if (target.selectedOptions) {
    selectedOption = target.selectedOptions[0];
  } else if (target.options) {
    selectedOption = target.options[target.options.selectedIndex];
  }

  return selectedOption.value;
}

function setSelectedOption(target, value) {
  if (!value) {
    return;
  }

  var i = void 0;

  for (i = 0; i < target.options.length; i++) {
    var option = target.options[i];

    if (option.value === value) {
      break;
    }
  }

  target.selectedIndex = i;
}

/**
 * Manipulate settings of texttracks
 *
 * @param {Object} player  Main Player
 * @param {Object=} options Object of option names and values
 * @extends Component
 * @class TextTrackSettings
 */

var TextTrackSettings = function (_Component) {
  _inherits(TextTrackSettings, _Component);

  function TextTrackSettings(player, options) {
    _classCallCheck(this, TextTrackSettings);

    var _this = _possibleConstructorReturn(this, _Component.call(this, player, options));

    _this.hide();

    // Grab `persistTextTrackSettings` from the player options if not passed in child options
    if (options.persistTextTrackSettings === undefined) {
      _this.options_.persistTextTrackSettings = _this.options_.playerOptions.persistTextTrackSettings;
    }

    Events.on(_this.$('.vjs-done-button'), 'click', Fn.bind(_this, function () {
      this.saveSettings();
      this.hide();
    }));

    Events.on(_this.$('.vjs-default-button'), 'click', Fn.bind(_this, function () {
      this.$('.vjs-fg-color > select').selectedIndex = 0;
      this.$('.vjs-bg-color > select').selectedIndex = 0;
      this.$('.window-color > select').selectedIndex = 0;
      this.$('.vjs-text-opacity > select').selectedIndex = 0;
      this.$('.vjs-bg-opacity > select').selectedIndex = 0;
      this.$('.vjs-window-opacity > select').selectedIndex = 0;
      this.$('.vjs-edge-style select').selectedIndex = 0;
      this.$('.vjs-font-family select').selectedIndex = 0;
      this.$('.vjs-font-percent select').selectedIndex = 2;
      this.updateDisplay();
    }));

    Events.on(_this.$('.vjs-fg-color > select'), 'change', Fn.bind(_this, _this.updateDisplay));
    Events.on(_this.$('.vjs-bg-color > select'), 'change', Fn.bind(_this, _this.updateDisplay));
    Events.on(_this.$('.window-color > select'), 'change', Fn.bind(_this, _this.updateDisplay));
    Events.on(_this.$('.vjs-text-opacity > select'), 'change', Fn.bind(_this, _this.updateDisplay));
    Events.on(_this.$('.vjs-bg-opacity > select'), 'change', Fn.bind(_this, _this.updateDisplay));
    Events.on(_this.$('.vjs-window-opacity > select'), 'change', Fn.bind(_this, _this.updateDisplay));
    Events.on(_this.$('.vjs-font-percent select'), 'change', Fn.bind(_this, _this.updateDisplay));
    Events.on(_this.$('.vjs-edge-style select'), 'change', Fn.bind(_this, _this.updateDisplay));
    Events.on(_this.$('.vjs-font-family select'), 'change', Fn.bind(_this, _this.updateDisplay));

    if (_this.options_.persistTextTrackSettings) {
      _this.restoreSettings();
    }
    return _this;
  }

  /**
   * Create the component's DOM element
   *
   * @return {Element}
   * @method createEl
   */


  TextTrackSettings.prototype.createEl = function createEl() {
    var uniqueId = this.id_;
    var dialogLabelId = 'TTsettingsDialogLabel-' + uniqueId;
    var dialogDescriptionId = 'TTsettingsDialogDescription-' + uniqueId;

    return _Component.prototype.createEl.call(this, 'div', {
      className: 'vjs-caption-settings vjs-modal-overlay',
      innerHTML: captionOptionsMenuTemplate(uniqueId, dialogLabelId, dialogDescriptionId),
      tabIndex: -1
    }, {
      'role': 'dialog',
      'aria-labelledby': dialogLabelId,
      'aria-describedby': dialogDescriptionId
    });
  };

  /**
   * Get texttrack settings
   * Settings are
   * .vjs-edge-style
   * .vjs-font-family
   * .vjs-fg-color
   * .vjs-text-opacity
   * .vjs-bg-color
   * .vjs-bg-opacity
   * .window-color
   * .vjs-window-opacity
   *
   * @return {Object}
   * @method getValues
   */


  TextTrackSettings.prototype.getValues = function getValues() {
    var textEdge = getSelectedOptionValue(this.$('.vjs-edge-style select'));
    var fontFamily = getSelectedOptionValue(this.$('.vjs-font-family select'));
    var fgColor = getSelectedOptionValue(this.$('.vjs-fg-color > select'));
    var textOpacity = getSelectedOptionValue(this.$('.vjs-text-opacity > select'));
    var bgColor = getSelectedOptionValue(this.$('.vjs-bg-color > select'));
    var bgOpacity = getSelectedOptionValue(this.$('.vjs-bg-opacity > select'));
    var windowColor = getSelectedOptionValue(this.$('.window-color > select'));
    var windowOpacity = getSelectedOptionValue(this.$('.vjs-window-opacity > select'));
    var fontPercent = _window2['default'].parseFloat(getSelectedOptionValue(this.$('.vjs-font-percent > select')));

    var result = {
      fontPercent: fontPercent,
      fontFamily: fontFamily,
      textOpacity: textOpacity,
      windowColor: windowColor,
      windowOpacity: windowOpacity,
      backgroundOpacity: bgOpacity,
      edgeStyle: textEdge,
      color: fgColor,
      backgroundColor: bgColor
    };

    for (var name in result) {
      if (result[name] === '' || result[name] === 'none' || name === 'fontPercent' && result[name] === 1.00) {
        delete result[name];
      }
    }
    return result;
  };

  /**
   * Set texttrack settings
   * Settings are
   * .vjs-edge-style
   * .vjs-font-family
   * .vjs-fg-color
   * .vjs-text-opacity
   * .vjs-bg-color
   * .vjs-bg-opacity
   * .window-color
   * .vjs-window-opacity
   *
   * @param {Object} values Object with texttrack setting values
   * @method setValues
   */


  TextTrackSettings.prototype.setValues = function setValues(values) {
    setSelectedOption(this.$('.vjs-edge-style select'), values.edgeStyle);
    setSelectedOption(this.$('.vjs-font-family select'), values.fontFamily);
    setSelectedOption(this.$('.vjs-fg-color > select'), values.color);
    setSelectedOption(this.$('.vjs-text-opacity > select'), values.textOpacity);
    setSelectedOption(this.$('.vjs-bg-color > select'), values.backgroundColor);
    setSelectedOption(this.$('.vjs-bg-opacity > select'), values.backgroundOpacity);
    setSelectedOption(this.$('.window-color > select'), values.windowColor);
    setSelectedOption(this.$('.vjs-window-opacity > select'), values.windowOpacity);

    var fontPercent = values.fontPercent;

    if (fontPercent) {
      fontPercent = fontPercent.toFixed(2);
    }

    setSelectedOption(this.$('.vjs-font-percent > select'), fontPercent);
  };

  /**
   * Restore texttrack settings
   *
   * @method restoreSettings
   */


  TextTrackSettings.prototype.restoreSettings = function restoreSettings() {
    var err = void 0;
    var values = void 0;

    try {
      var _safeParseTuple = (0, _tuple2['default'])(_window2['default'].localStorage.getItem('vjs-text-track-settings'));

      err = _safeParseTuple[0];
      values = _safeParseTuple[1];


      if (err) {
        _log2['default'].error(err);
      }
    } catch (e) {
      _log2['default'].warn(e);
    }

    if (values) {
      this.setValues(values);
    }
  };

  /**
   * Save texttrack settings to local storage
   *
   * @method saveSettings
   */


  TextTrackSettings.prototype.saveSettings = function saveSettings() {
    if (!this.options_.persistTextTrackSettings) {
      return;
    }

    var values = this.getValues();

    try {
      if (Object.getOwnPropertyNames(values).length > 0) {
        _window2['default'].localStorage.setItem('vjs-text-track-settings', JSON.stringify(values));
      } else {
        _window2['default'].localStorage.removeItem('vjs-text-track-settings');
      }
    } catch (e) {
      _log2['default'].warn(e);
    }
  };

  /**
   * Update display of texttrack settings
   *
   * @method updateDisplay
   */


  TextTrackSettings.prototype.updateDisplay = function updateDisplay() {
    var ttDisplay = this.player_.getChild('textTrackDisplay');

    if (ttDisplay) {
      ttDisplay.updateDisplay();
    }
  };

  return TextTrackSettings;
}(_component2['default']);

_component2['default'].registerComponent('TextTrackSettings', TextTrackSettings);

exports['default'] = TextTrackSettings;
