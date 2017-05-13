'use strict';

exports.__esModule = true;
exports.setTextContent = exports.createStyleElement = undefined;

var _document = require('global/document');

var _document2 = _interopRequireDefault(_document);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { 'default': obj }; }

var createStyleElement = exports.createStyleElement = function createStyleElement(className) {
  var style = _document2['default'].createElement('style');

  style.className = className;

  return style;
};

var setTextContent = exports.setTextContent = function setTextContent(el, content) {
  if (el.styleSheet) {
    el.styleSheet.cssText = content;
  } else {
    el.textContent = content;
  }
};
