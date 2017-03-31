'use strict';

exports.__esModule = true;

var _player = require('./player.js');

var _player2 = _interopRequireDefault(_player);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { 'default': obj }; }

/**
 * The method for registering a video.js plugin
 *
 * @param  {String} name The name of the plugin
 * @param  {Function} init The function that is run when the player inits
 * @method plugin
 */
var plugin = function plugin(name, init) {
  _player2['default'].prototype[name] = init;
}; /**
    * @file plugins.js
    */
exports['default'] = plugin;
