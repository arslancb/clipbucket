'use strict';

exports.__esModule = true;

var _clickableComponent = require('../clickable-component.js');

var _clickableComponent2 = _interopRequireDefault(_clickableComponent);

var _component = require('../component.js');

var _component2 = _interopRequireDefault(_component);

var _object = require('object.assign');

var _object2 = _interopRequireDefault(_object);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { 'default': obj }; }

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _possibleConstructorReturn(self, call) { if (!self) { throw new ReferenceError("this hasn't been initialised - super() hasn't been called"); } return call && (typeof call === "object" || typeof call === "function") ? call : self; }

function _inherits(subClass, superClass) { if (typeof superClass !== "function" && superClass !== null) { throw new TypeError("Super expression must either be null or a function, not " + typeof superClass); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, enumerable: false, writable: true, configurable: true } }); if (superClass) Object.setPrototypeOf ? Object.setPrototypeOf(subClass, superClass) : subClass.__proto__ = superClass; } /**
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                * @file menu-item.js
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                */


/**
 * The component for a menu item. `<li>`
 *
 * @param {Player|Object} player
 * @param {Object=} options
 * @extends Button
 * @class MenuItem
 */
var MenuItem = function (_ClickableComponent) {
  _inherits(MenuItem, _ClickableComponent);

  function MenuItem(player, options) {
    _classCallCheck(this, MenuItem);

    var _this = _possibleConstructorReturn(this, _ClickableComponent.call(this, player, options));

    _this.selectable = options.selectable;

    _this.selected(options.selected);

    if (_this.selectable) {
      // TODO: May need to be either menuitemcheckbox or menuitemradio,
      //       and may need logical grouping of menu items.
      _this.el_.setAttribute('role', 'menuitemcheckbox');
    } else {
      _this.el_.setAttribute('role', 'menuitem');
    }
    return _this;
  }

  /**
   * Create the component's DOM element
   *
   * @param {String=} type Desc
   * @param {Object=} props Desc
   * @return {Element}
   * @method createEl
   */


  MenuItem.prototype.createEl = function createEl(type, props, attrs) {
    return _ClickableComponent.prototype.createEl.call(this, 'li', (0, _object2['default'])({
      className: 'vjs-menu-item',
      innerHTML: this.localize(this.options_.label),
      tabIndex: -1
    }, props), attrs);
  };

  /**
   * Handle a click on the menu item, and set it to selected
   *
   * @method handleClick
   */


  MenuItem.prototype.handleClick = function handleClick() {
    this.selected(true);
  };

  /**
   * Set this menu item as selected or not
   *
   * @param  {Boolean} selected
   * @method selected
   */


  MenuItem.prototype.selected = function selected(_selected) {
    if (this.selectable) {
      if (_selected) {
        this.addClass('vjs-selected');
        this.el_.setAttribute('aria-checked', 'true');
        // aria-checked isn't fully supported by browsers/screen readers,
        // so indicate selected state to screen reader in the control text.
        this.controlText(', selected');
      } else {
        this.removeClass('vjs-selected');
        this.el_.setAttribute('aria-checked', 'false');
        // Indicate un-selected state to screen reader
        // Note that a space clears out the selected state text
        this.controlText(' ');
      }
    }
  };

  return MenuItem;
}(_clickableComponent2['default']);

_component2['default'].registerComponent('MenuItem', MenuItem);
exports['default'] = MenuItem;
