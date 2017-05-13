'use strict';

exports.__esModule = true;
exports.BACKGROUND_SIZE_SUPPORTED = exports.TOUCH_ENABLED = exports.IE_VERSION = exports.IS_IE8 = exports.IS_CHROME = exports.IS_EDGE = exports.IS_FIREFOX = exports.IS_NATIVE_ANDROID = exports.IS_OLD_ANDROID = exports.ANDROID_VERSION = exports.IS_ANDROID = exports.IOS_VERSION = exports.IS_IOS = exports.IS_IPOD = exports.IS_IPHONE = exports.IS_IPAD = undefined;

var _document = require('global/document');

var _document2 = _interopRequireDefault(_document);

var _window = require('global/window');

var _window2 = _interopRequireDefault(_window);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { 'default': obj }; }

/**
 * @file browser.js
 */
var USER_AGENT = _window2['default'].navigator && _window2['default'].navigator.userAgent || '';
var webkitVersionMap = /AppleWebKit\/([\d.]+)/i.exec(USER_AGENT);
var appleWebkitVersion = webkitVersionMap ? parseFloat(webkitVersionMap.pop()) : null;

/*
 * Device is an iPhone
 *
 * @type {Boolean}
 * @constant
 * @private
 */
var IS_IPAD = exports.IS_IPAD = /iPad/i.test(USER_AGENT);

// The Facebook app's UIWebView identifies as both an iPhone and iPad, so
// to identify iPhones, we need to exclude iPads.
// http://artsy.github.io/blog/2012/10/18/the-perils-of-ios-user-agent-sniffing/
var IS_IPHONE = exports.IS_IPHONE = /iPhone/i.test(USER_AGENT) && !IS_IPAD;
var IS_IPOD = exports.IS_IPOD = /iPod/i.test(USER_AGENT);
var IS_IOS = exports.IS_IOS = IS_IPHONE || IS_IPAD || IS_IPOD;

var IOS_VERSION = exports.IOS_VERSION = function () {
  var match = USER_AGENT.match(/OS (\d+)_/i);

  if (match && match[1]) {
    return match[1];
  }
  return null;
}();

var IS_ANDROID = exports.IS_ANDROID = /Android/i.test(USER_AGENT);
var ANDROID_VERSION = exports.ANDROID_VERSION = function () {
  // This matches Android Major.Minor.Patch versions
  // ANDROID_VERSION is Major.Minor as a Number, if Minor isn't available, then only Major is returned
  var match = USER_AGENT.match(/Android (\d+)(?:\.(\d+))?(?:\.(\d+))*/i);

  if (!match) {
    return null;
  }

  var major = match[1] && parseFloat(match[1]);
  var minor = match[2] && parseFloat(match[2]);

  if (major && minor) {
    return parseFloat(match[1] + '.' + match[2]);
  } else if (major) {
    return major;
  }
  return null;
}();

// Old Android is defined as Version older than 2.3, and requiring a webkit version of the android browser
var IS_OLD_ANDROID = exports.IS_OLD_ANDROID = IS_ANDROID && /webkit/i.test(USER_AGENT) && ANDROID_VERSION < 2.3;
var IS_NATIVE_ANDROID = exports.IS_NATIVE_ANDROID = IS_ANDROID && ANDROID_VERSION < 5 && appleWebkitVersion < 537;

var IS_FIREFOX = exports.IS_FIREFOX = /Firefox/i.test(USER_AGENT);
var IS_EDGE = exports.IS_EDGE = /Edge/i.test(USER_AGENT);
var IS_CHROME = exports.IS_CHROME = !IS_EDGE && /Chrome/i.test(USER_AGENT);
var IS_IE8 = exports.IS_IE8 = /MSIE\s8\.0/.test(USER_AGENT);
var IE_VERSION = exports.IE_VERSION = function (result) {
  return result && parseFloat(result[1]);
}(/MSIE\s(\d+)\.\d/.exec(USER_AGENT));

var TOUCH_ENABLED = exports.TOUCH_ENABLED = !!('ontouchstart' in _window2['default'] || _window2['default'].DocumentTouch && _document2['default'] instanceof _window2['default'].DocumentTouch);
var BACKGROUND_SIZE_SUPPORTED = exports.BACKGROUND_SIZE_SUPPORTED = 'backgroundSize' in _document2['default'].createElement('video').style;
