'use strict';

exports.__esModule = true;

var _button = require('./button');

var _button2 = _interopRequireDefault(_button);

var _component = require('./component');

var _component2 = _interopRequireDefault(_component);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { 'default': obj }; }

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _possibleConstructorReturn(self, call) { if (!self) { throw new ReferenceError("this hasn't been initialised - super() hasn't been called"); } return call && (typeof call === "object" || typeof call === "function") ? call : self; }

function _inherits(subClass, superClass) { if (typeof superClass !== "function" && superClass !== null) { throw new TypeError("Super expression must either be null or a function, not " + typeof superClass); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, enumerable: false, writable: true, configurable: true } }); if (superClass) Object.setPrototypeOf ? Object.setPrototypeOf(subClass, superClass) : subClass.__proto__ = superClass; }

/**
 * The `CloseButton` component is a button which fires a "close" event
 * when it is activated.
 *
 * @extends Button
 * @class CloseButton
 */
var CloseButton = function (_Button) {
  _inherits(CloseButton, _Button);

  function CloseButton(player, options) {
    _classCallCheck(this, CloseButton);

    var _this = _possibleConstructorReturn(this, _Button.call(this, player, options));

    _this.controlText(options && options.controlText || _this.localize('Close'));
    return _this;
  }

  CloseButton.prototype.buildCSSClass = function buildCSSClass() {
    return 'vjs-close-button ' + _Button.prototype.buildCSSClass.call(this);
  };

  CloseButton.prototype.handleClick = function handleClick() {
    this.trigger({ type: 'close', bubbles: false });
  };

  return CloseButton;
}(_button2['default']);

_component2['default'].registerComponent('CloseButton', CloseButton);
exports['default'] = CloseButton;
