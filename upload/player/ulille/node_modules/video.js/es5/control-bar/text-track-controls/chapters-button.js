'use strict';

exports.__esModule = true;

var _textTrackButton = require('./text-track-button.js');

var _textTrackButton2 = _interopRequireDefault(_textTrackButton);

var _component = require('../../component.js');

var _component2 = _interopRequireDefault(_component);

var _textTrackMenuItem = require('./text-track-menu-item.js');

var _textTrackMenuItem2 = _interopRequireDefault(_textTrackMenuItem);

var _chaptersTrackMenuItem = require('./chapters-track-menu-item.js');

var _chaptersTrackMenuItem2 = _interopRequireDefault(_chaptersTrackMenuItem);

var _menu = require('../../menu/menu.js');

var _menu2 = _interopRequireDefault(_menu);

var _dom = require('../../utils/dom.js');

var Dom = _interopRequireWildcard(_dom);

var _toTitleCase = require('../../utils/to-title-case.js');

var _toTitleCase2 = _interopRequireDefault(_toTitleCase);

function _interopRequireWildcard(obj) { if (obj && obj.__esModule) { return obj; } else { var newObj = {}; if (obj != null) { for (var key in obj) { if (Object.prototype.hasOwnProperty.call(obj, key)) newObj[key] = obj[key]; } } newObj['default'] = obj; return newObj; } }

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { 'default': obj }; }

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _possibleConstructorReturn(self, call) { if (!self) { throw new ReferenceError("this hasn't been initialised - super() hasn't been called"); } return call && (typeof call === "object" || typeof call === "function") ? call : self; }

function _inherits(subClass, superClass) { if (typeof superClass !== "function" && superClass !== null) { throw new TypeError("Super expression must either be null or a function, not " + typeof superClass); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, enumerable: false, writable: true, configurable: true } }); if (superClass) Object.setPrototypeOf ? Object.setPrototypeOf(subClass, superClass) : subClass.__proto__ = superClass; } /**
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                * @file chapters-button.js
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                */


/**
 * The button component for toggling and selecting chapters
 * Chapters act much differently than other text tracks
 * Cues are navigation vs. other tracks of alternative languages
 *
 * @param {Object} player  Player object
 * @param {Object=} options Object of option names and values
 * @param {Function=} ready    Ready callback function
 * @extends TextTrackButton
 * @class ChaptersButton
 */
var ChaptersButton = function (_TextTrackButton) {
  _inherits(ChaptersButton, _TextTrackButton);

  function ChaptersButton(player, options, ready) {
    _classCallCheck(this, ChaptersButton);

    var _this = _possibleConstructorReturn(this, _TextTrackButton.call(this, player, options, ready));

    _this.el_.setAttribute('aria-label', 'Chapters Menu');
    return _this;
  }

  /**
   * Allow sub components to stack CSS class names
   *
   * @return {String} The constructed class name
   * @method buildCSSClass
   */


  ChaptersButton.prototype.buildCSSClass = function buildCSSClass() {
    return 'vjs-chapters-button ' + _TextTrackButton.prototype.buildCSSClass.call(this);
  };

  /**
   * Create a menu item for each text track
   *
   * @return {Array} Array of menu items
   * @method createItems
   */


  ChaptersButton.prototype.createItems = function createItems() {
    var items = [];
    var tracks = this.player_.textTracks();

    if (!tracks) {
      return items;
    }

    for (var i = 0; i < tracks.length; i++) {
      var track = tracks[i];

      if (track.kind === this.kind_) {
        items.push(new _textTrackMenuItem2['default'](this.player_, { track: track }));
      }
    }

    return items;
  };

  /**
   * Create menu from chapter buttons
   *
   * @return {Menu} Menu of chapter buttons
   * @method createMenu
   */


  ChaptersButton.prototype.createMenu = function createMenu() {
    var _this2 = this;

    var tracks = this.player_.textTracks() || [];
    var chaptersTrack = void 0;
    var items = this.items || [];

    for (var i = tracks.length - 1; i >= 0; i--) {

      // We will always choose the last track as our chaptersTrack
      var track = tracks[i];

      if (track.kind === this.kind_) {
        chaptersTrack = track;

        break;
      }
    }

    var menu = this.menu;

    if (menu === undefined) {
      menu = new _menu2['default'](this.player_);

      var title = Dom.createEl('li', {
        className: 'vjs-menu-title',
        innerHTML: (0, _toTitleCase2['default'])(this.kind_),
        tabIndex: -1
      });

      menu.children_.unshift(title);
      Dom.insertElFirst(title, menu.contentEl());
    } else {
      // We will empty out the menu children each time because we want a
      // fresh new menu child list each time
      items.forEach(function (item) {
        return menu.removeChild(item);
      });
      // Empty out the ChaptersButton menu items because we no longer need them
      items = [];
    }

    if (chaptersTrack && (chaptersTrack.cues === null || chaptersTrack.cues === undefined)) {
      chaptersTrack.mode = 'hidden';

      var remoteTextTrackEl = this.player_.remoteTextTrackEls().getTrackElementByTrack_(chaptersTrack);

      if (remoteTextTrackEl) {
        remoteTextTrackEl.addEventListener('load', function (event) {
          return _this2.update();
        });
      }
    }

    if (chaptersTrack && chaptersTrack.cues && chaptersTrack.cues.length > 0) {
      var cues = chaptersTrack.cues;

      for (var _i = 0, l = cues.length; _i < l; _i++) {
        var cue = cues[_i];

        var mi = new _chaptersTrackMenuItem2['default'](this.player_, {
          cue: cue,
          track: chaptersTrack
        });

        items.push(mi);

        menu.addChild(mi);
      }
    }

    if (items.length > 0) {
      this.show();
    }
    // Assigning the value of items back to this.items for next iteration
    this.items = items;
    return menu;
  };

  return ChaptersButton;
}(_textTrackButton2['default']);

ChaptersButton.prototype.kind_ = 'chapters';
ChaptersButton.prototype.controlText_ = 'Chapters';

_component2['default'].registerComponent('ChaptersButton', ChaptersButton);
exports['default'] = ChaptersButton;
