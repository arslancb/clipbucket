'use strict';

exports.__esModule = true;

var _component = require('./component');

var _component2 = _interopRequireDefault(_component);

var _dom = require('./utils/dom.js');

var Dom = _interopRequireWildcard(_dom);

var _events = require('./utils/events.js');

var Events = _interopRequireWildcard(_events);

var _fn = require('./utils/fn.js');

var Fn = _interopRequireWildcard(_fn);

var _log = require('./utils/log.js');

var _log2 = _interopRequireDefault(_log);

var _document = require('global/document');

var _document2 = _interopRequireDefault(_document);

var _object = require('object.assign');

var _object2 = _interopRequireDefault(_object);

function _interopRequireWildcard(obj) { if (obj && obj.__esModule) { return obj; } else { var newObj = {}; if (obj != null) { for (var key in obj) { if (Object.prototype.hasOwnProperty.call(obj, key)) newObj[key] = obj[key]; } } newObj['default'] = obj; return newObj; } }

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { 'default': obj }; }

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _possibleConstructorReturn(self, call) { if (!self) { throw new ReferenceError("this hasn't been initialised - super() hasn't been called"); } return call && (typeof call === "object" || typeof call === "function") ? call : self; }

function _inherits(subClass, superClass) { if (typeof superClass !== "function" && superClass !== null) { throw new TypeError("Super expression must either be null or a function, not " + typeof superClass); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, enumerable: false, writable: true, configurable: true } }); if (superClass) Object.setPrototypeOf ? Object.setPrototypeOf(subClass, superClass) : subClass.__proto__ = superClass; } /**
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                * @file button.js
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                */


/**
 * Clickable Component which is clickable or keyboard actionable, but is not a native HTML button
 *
 * @param {Object} player  Main Player
 * @param {Object=} options Object of option names and values
 * @extends Component
 * @class ClickableComponent
 */
var ClickableComponent = function (_Component) {
  _inherits(ClickableComponent, _Component);

  function ClickableComponent(player, options) {
    _classCallCheck(this, ClickableComponent);

    var _this = _possibleConstructorReturn(this, _Component.call(this, player, options));

    _this.emitTapEvents();

    _this.on('tap', _this.handleClick);
    _this.on('click', _this.handleClick);
    _this.on('focus', _this.handleFocus);
    _this.on('blur', _this.handleBlur);
    return _this;
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


  ClickableComponent.prototype.createEl = function createEl() {
    var tag = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : 'div';
    var props = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : {};
    var attributes = arguments.length > 2 && arguments[2] !== undefined ? arguments[2] : {};

    props = (0, _object2['default'])({
      className: this.buildCSSClass(),
      tabIndex: 0
    }, props);

    if (tag === 'button') {
      _log2['default'].error('Creating a ClickableComponent with an HTML element of ' + tag + ' is not supported; use a Button instead.');
    }

    // Add ARIA attributes for clickable element which is not a native HTML button
    attributes = (0, _object2['default'])({
      'role': 'button',

      // let the screen reader user know that the text of the element may change
      'aria-live': 'polite'
    }, attributes);

    var el = _Component.prototype.createEl.call(this, tag, props, attributes);

    this.createControlTextEl(el);

    return el;
  };

  /**
   * create control text
   *
   * @param {Element} el Parent element for the control text
   * @return {Element}
   * @method controlText
   */


  ClickableComponent.prototype.createControlTextEl = function createControlTextEl(el) {
    this.controlTextEl_ = Dom.createEl('span', {
      className: 'vjs-control-text'
    });

    if (el) {
      el.appendChild(this.controlTextEl_);
    }

    this.controlText(this.controlText_, el);

    return this.controlTextEl_;
  };

  /**
   * Controls text - both request and localize
   *
   * @param {String}  text Text for element
   * @param {Element=} el Element to set the title on
   * @return {String}
   * @method controlText
   */


  ClickableComponent.prototype.controlText = function controlText(text) {
    var el = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : this.el();

    if (!text) {
      return this.controlText_ || 'Need Text';
    }

    var localizedText = this.localize(text);

    this.controlText_ = text;
    this.controlTextEl_.innerHTML = localizedText;
    el.setAttribute('title', localizedText);

    return this;
  };

  /**
   * Allows sub components to stack CSS class names
   *
   * @return {String}
   * @method buildCSSClass
   */


  ClickableComponent.prototype.buildCSSClass = function buildCSSClass() {
    return 'vjs-control vjs-button ' + _Component.prototype.buildCSSClass.call(this);
  };

  /**
   * Adds a child component inside this clickable-component
   *
   * @param {String|Component} child The class name or instance of a child to add
   * @param {Object=} options Options, including options to be passed to children of the child.
   * @return {Component} The child component (created by this process if a string was used)
   * @method addChild
   */


  ClickableComponent.prototype.addChild = function addChild(child) {
    var options = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : {};

    // TODO: Fix adding an actionable child to a ClickableComponent; currently
    // it will cause issues with assistive technology (e.g. screen readers)
    // which support ARIA, since an element with role="button" cannot have
    // actionable child elements.

    // let className = this.constructor.name;
    // log.warn(`Adding a child to a ClickableComponent (${className}) can cause issues with assistive technology which supports ARIA, since an element with role="button" cannot have actionable child elements.`);

    return _Component.prototype.addChild.call(this, child, options);
  };

  /**
   * Enable the component element
   *
   * @return {Component}
   * @method enable
   */


  ClickableComponent.prototype.enable = function enable() {
    this.removeClass('vjs-disabled');
    this.el_.setAttribute('aria-disabled', 'false');
    return this;
  };

  /**
   * Disable the component element
   *
   * @return {Component}
   * @method disable
   */


  ClickableComponent.prototype.disable = function disable() {
    this.addClass('vjs-disabled');
    this.el_.setAttribute('aria-disabled', 'true');
    return this;
  };

  /**
   * Handle Click - Override with specific functionality for component
   *
   * @method handleClick
   */


  ClickableComponent.prototype.handleClick = function handleClick() {};

  /**
   * Handle Focus - Add keyboard functionality to element
   *
   * @method handleFocus
   */


  ClickableComponent.prototype.handleFocus = function handleFocus() {
    Events.on(_document2['default'], 'keydown', Fn.bind(this, this.handleKeyPress));
  };

  /**
   * Handle KeyPress (document level) - Trigger click when Space or Enter key is pressed
   *
   * @method handleKeyPress
   */


  ClickableComponent.prototype.handleKeyPress = function handleKeyPress(event) {

    // Support Space (32) or Enter (13) key operation to fire a click event
    if (event.which === 32 || event.which === 13) {
      event.preventDefault();
      this.handleClick(event);
    } else if (_Component.prototype.handleKeyPress) {

      // Pass keypress handling up for unsupported keys
      _Component.prototype.handleKeyPress.call(this, event);
    }
  };

  /**
   * Handle Blur - Remove keyboard triggers
   *
   * @method handleBlur
   */


  ClickableComponent.prototype.handleBlur = function handleBlur() {
    Events.off(_document2['default'], 'keydown', Fn.bind(this, this.handleKeyPress));
  };

  return ClickableComponent;
}(_component2['default']);

_component2['default'].registerComponent('ClickableComponent', ClickableComponent);
exports['default'] = ClickableComponent;
