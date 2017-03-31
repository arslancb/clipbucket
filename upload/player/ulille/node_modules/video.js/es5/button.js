'use strict';

exports.__esModule = true;

var _clickableComponent = require('./clickable-component.js');

var _clickableComponent2 = _interopRequireDefault(_clickableComponent);

var _component = require('./component');

var _component2 = _interopRequireDefault(_component);

var _log = require('./utils/log.js');

var _log2 = _interopRequireDefault(_log);

var _object = require('object.assign');

var _object2 = _interopRequireDefault(_object);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { 'default': obj }; }

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _possibleConstructorReturn(self, call) { if (!self) { throw new ReferenceError("this hasn't been initialised - super() hasn't been called"); } return call && (typeof call === "object" || typeof call === "function") ? call : self; }

function _inherits(subClass, superClass) { if (typeof superClass !== "function" && superClass !== null) { throw new TypeError("Super expression must either be null or a function, not " + typeof superClass); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, enumerable: false, writable: true, configurable: true } }); if (superClass) Object.setPrototypeOf ? Object.setPrototypeOf(subClass, superClass) : subClass.__proto__ = superClass; } /**
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                * @file button.js
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                */


/**
 * Base class for all buttons
 *
 * @param {Object} player  Main Player
 * @param {Object=} options Object of option names and values
 * @extends ClickableComponent
 * @class Button
 */
var Button = function (_ClickableComponent) {
  _inherits(Button, _ClickableComponent);

  function Button(player, options) {
    _classCallCheck(this, Button);

    return _possibleConstructorReturn(this, _ClickableComponent.call(this, player, options));
  }

  /**
   * Create the component's DOM element
   *
   * @param {String=} type Element's node type. e.g. 'div'
   * @param {Object=} props An object of properties that should be set on the element
   * @param {Object=} attributes An object of attributes that should be set on the element
   * @return {Element}
   * @method createEl
   */


  Button.prototype.createEl = function createEl() {
    var tag = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : 'button';
    var props = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : {};
    var attributes = arguments.length > 2 && arguments[2] !== undefined ? arguments[2] : {};

    props = (0, _object2['default'])({
      className: this.buildCSSClass()
    }, props);

    if (tag !== 'button') {
      _log2['default'].warn('Creating a Button with an HTML element of ' + tag + ' is deprecated; use ClickableComponent instead.');

      // Add properties for clickable element which is not a native HTML button
      props = (0, _object2['default'])({
        tabIndex: 0
      }, props);

      // Add ARIA attributes for clickable element which is not a native HTML button
      attributes = (0, _object2['default'])({
        role: 'button'
      }, attributes);
    }

    // Add attributes for button element
    attributes = (0, _object2['default'])({

      // Necessary since the default button type is "submit"
      'type': 'button',

      // let the screen reader user know that the text of the button may change
      'aria-live': 'polite'
    }, attributes);

    var el = _component2['default'].prototype.createEl.call(this, tag, props, attributes);

    this.createControlTextEl(el);

    return el;
  };

  /**
   * Adds a child component inside this button
   *
   * @param {String|Component} child The class name or instance of a child to add
   * @param {Object=} options Options, including options to be passed to children of the child.
   * @return {Component} The child component (created by this process if a string was used)
   * @deprecated
   * @method addChild
   */


  Button.prototype.addChild = function addChild(child) {
    var options = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : {};

    var className = this.constructor.name;

    _log2['default'].warn('Adding an actionable (user controllable) child to a Button (' + className + ') is not supported; use a ClickableComponent instead.');

    // Avoid the error message generated by ClickableComponent's addChild method
    return _component2['default'].prototype.addChild.call(this, child, options);
  };

  /**
   * Handle KeyPress (document level) - Extend with specific functionality for button
   *
   * @method handleKeyPress
   */


  Button.prototype.handleKeyPress = function handleKeyPress(event) {

    // Ignore Space (32) or Enter (13) key operation, which is handled by the browser for a button.
    if (event.which === 32 || event.which === 13) {
      return;
    }

    // Pass keypress handling up for unsupported keys
    _ClickableComponent.prototype.handleKeyPress.call(this, event);
  };

  return Button;
}(_clickableComponent2['default']);

_component2['default'].registerComponent('Button', Button);
exports['default'] = Button;
