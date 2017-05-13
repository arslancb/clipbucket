"use strict";

exports.__esModule = true;
exports.newGUID = newGUID;
/**
 * @file guid.js
 *
 * Unique ID for an element or function
 * @type {Number}
 * @private
 */
var _guid = 1;

/**
 * Get the next unique ID
 *
 * @return {String}
 * @function newGUID
 */
function newGUID() {
  return _guid++;
}
