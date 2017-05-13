'use strict';

exports.__esModule = true;

var _trackButton = require('../track-button.js');

var _trackButton2 = _interopRequireDefault(_trackButton);

var _component = require('../../component.js');

var _component2 = _interopRequireDefault(_component);

var _audioTrackMenuItem = require('./audio-track-menu-item.js');

var _audioTrackMenuItem2 = _interopRequireDefault(_audioTrackMenuItem);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { 'default': obj }; }

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _possibleConstructorReturn(self, call) { if (!self) { throw new ReferenceError("this hasn't been initialised - super() hasn't been called"); } return call && (typeof call === "object" || typeof call === "function") ? call : self; }

function _inherits(subClass, superClass) { if (typeof superClass !== "function" && superClass !== null) { throw new TypeError("Super expression must either be null or a function, not " + typeof superClass); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, enumerable: false, writable: true, configurable: true } }); if (superClass) Object.setPrototypeOf ? Object.setPrototypeOf(subClass, superClass) : subClass.__proto__ = superClass; } /**
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                * @file audio-track-button.js
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                */


/**
 * The base class for buttons that toggle specific text track types (e.g. subtitles)
 *
 * @param {Player|Object} player
 * @param {Object=} options
 * @extends TrackButton
 * @class AudioTrackButton
 */
var AudioTrackButton = function (_TrackButton) {
  _inherits(AudioTrackButton, _TrackButton);

  function AudioTrackButton(player) {
    var options = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : {};

    _classCallCheck(this, AudioTrackButton);

    options.tracks = player.audioTracks && player.audioTracks();

    var _this = _possibleConstructorReturn(this, _TrackButton.call(this, player, options));

    _this.el_.setAttribute('aria-label', 'Audio Menu');
    return _this;
  }

  /**
   * Allow sub components to stack CSS class names
   *
   * @return {String} The constructed class name
   * @method buildCSSClass
   */


  AudioTrackButton.prototype.buildCSSClass = function buildCSSClass() {
    return 'vjs-audio-button ' + _TrackButton.prototype.buildCSSClass.call(this);
  };

  /**
   * Create a menu item for each audio track
   *
   * @return {Array} Array of menu items
   * @method createItems
   */


  AudioTrackButton.prototype.createItems = function createItems() {
    var items = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : [];

    var tracks = this.player_.audioTracks && this.player_.audioTracks();

    if (!tracks) {
      return items;
    }

    for (var i = 0; i < tracks.length; i++) {
      var track = tracks[i];

      items.push(new _audioTrackMenuItem2['default'](this.player_, {
        track: track,
        // MenuItem is selectable
        selectable: true
      }));
    }

    return items;
  };

  return AudioTrackButton;
}(_trackButton2['default']);

AudioTrackButton.prototype.controlText_ = 'Audio Track';
_component2['default'].registerComponent('AudioTrackButton', AudioTrackButton);
exports['default'] = AudioTrackButton;
