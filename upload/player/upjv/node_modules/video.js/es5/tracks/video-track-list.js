'use strict';

exports.__esModule = true;

var _trackList = require('./track-list');

var _trackList2 = _interopRequireDefault(_trackList);

var _browser = require('../utils/browser.js');

var browser = _interopRequireWildcard(_browser);

var _document = require('global/document');

var _document2 = _interopRequireDefault(_document);

function _interopRequireWildcard(obj) { if (obj && obj.__esModule) { return obj; } else { var newObj = {}; if (obj != null) { for (var key in obj) { if (Object.prototype.hasOwnProperty.call(obj, key)) newObj[key] = obj[key]; } } newObj['default'] = obj; return newObj; } }

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { 'default': obj }; }

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _possibleConstructorReturn(self, call) { if (!self) { throw new ReferenceError("this hasn't been initialised - super() hasn't been called"); } return call && (typeof call === "object" || typeof call === "function") ? call : self; }

function _inherits(subClass, superClass) { if (typeof superClass !== "function" && superClass !== null) { throw new TypeError("Super expression must either be null or a function, not " + typeof superClass); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, enumerable: false, writable: true, configurable: true } }); if (superClass) Object.setPrototypeOf ? Object.setPrototypeOf(subClass, superClass) : subClass.__proto__ = superClass; } /**
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                * @file video-track-list.js
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                */


/**
 * disable other video tracks before selecting the new one
 *
 * @param {Array|VideoTrackList} list list to work on
 * @param {VideoTrack} track the track to skip
 */
var disableOthers = function disableOthers(list, track) {
  for (var i = 0; i < list.length; i++) {
    if (track.id === list[i].id) {
      continue;
    }
    // another audio track is enabled, disable it
    list[i].selected = false;
  }
};

/**
* A list of possiblee video tracks. Most functionality is in the
 * base class Tracklist and the spec for VideoTrackList is located at:
 * @link https://html.spec.whatwg.org/multipage/embedded-content.html#videotracklist
 *
 * interface VideoTrackList : EventTarget {
 *   readonly attribute unsigned long length;
 *   getter VideoTrack (unsigned long index);
 *   VideoTrack? getTrackById(DOMString id);
 *   readonly attribute long selectedIndex;
 *
 *   attribute EventHandler onchange;
 *   attribute EventHandler onaddtrack;
 *   attribute EventHandler onremovetrack;
 * };
 *
 * @param {VideoTrack[]} tracks a list of video tracks to instantiate the list with
 # @extends TrackList
 * @class VideoTrackList
 */

var VideoTrackList = function (_TrackList) {
  _inherits(VideoTrackList, _TrackList);

  function VideoTrackList() {
    var _this, _ret;

    var tracks = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : [];

    _classCallCheck(this, VideoTrackList);

    var list = void 0;

    // make sure only 1 track is enabled
    // sorted from last index to first index
    for (var i = tracks.length - 1; i >= 0; i--) {
      if (tracks[i].selected) {
        disableOthers(tracks, tracks[i]);
        break;
      }
    }

    // IE8 forces us to implement inheritance ourselves
    // as it does not support Object.defineProperty properly
    if (browser.IS_IE8) {
      list = _document2['default'].createElement('custom');
      for (var prop in _trackList2['default'].prototype) {
        if (prop !== 'constructor') {
          list[prop] = _trackList2['default'].prototype[prop];
        }
      }
      for (var _prop in VideoTrackList.prototype) {
        if (_prop !== 'constructor') {
          list[_prop] = VideoTrackList.prototype[_prop];
        }
      }
    }

    list = (_this = _possibleConstructorReturn(this, _TrackList.call(this, tracks, list)), _this);
    list.changing_ = false;

    Object.defineProperty(list, 'selectedIndex', {
      get: function get() {
        for (var _i = 0; _i < this.length; _i++) {
          if (this[_i].selected) {
            return _i;
          }
        }
        return -1;
      },
      set: function set() {}
    });

    return _ret = list, _possibleConstructorReturn(_this, _ret);
  }

  VideoTrackList.prototype.addTrack_ = function addTrack_(track) {
    var _this2 = this;

    if (track.selected) {
      disableOthers(this, track);
    }

    _TrackList.prototype.addTrack_.call(this, track);
    // native tracks don't have this
    if (!track.addEventListener) {
      return;
    }
    track.addEventListener('selectedchange', function () {
      if (_this2.changing_) {
        return;
      }
      _this2.changing_ = true;
      disableOthers(_this2, track);
      _this2.changing_ = false;
      _this2.trigger('change');
    });
  };

  VideoTrackList.prototype.addTrack = function addTrack(track) {
    this.addTrack_(track);
  };

  VideoTrackList.prototype.removeTrack = function removeTrack(track) {
    _TrackList.prototype.removeTrack_.call(this, track);
  };

  return VideoTrackList;
}(_trackList2['default']);

exports['default'] = VideoTrackList;
