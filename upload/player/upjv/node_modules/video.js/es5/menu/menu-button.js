'use strict';

exports.__esModule = true;

var _clickableComponent = require('../clickable-component.js');

var _clickableComponent2 = _interopRequireDefault(_clickableComponent);

var _component = require('../component.js');

var _component2 = _interopRequireDefault(_component);

var _menu = require('./menu.js');

var _menu2 = _interopRequireDefault(_menu);

var _dom = require('../utils/dom.js');

var Dom = _interopRequireWildcard(_dom);

var _fn = require('../utils/fn.js');

var Fn = _interopRequireWildcard(_fn);

var _toTitleCase = require('../utils/to-title-case.js');

var _toTitleCase2 = _interopRequireDefault(_toTitleCase);

function _interopRequireWildcard(obj) { if (obj && obj.__esModule) { return obj; } else { var newObj = {}; if (obj != null) { for (var key in obj) { if (Object.prototype.hasOwnProperty.call(obj, key)) newObj[key] = obj[key]; } } newObj['default'] = obj; return newObj; } }

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { 'default': obj }; }

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _possibleConstructorReturn(self, call) { if (!self) { throw new ReferenceError("this hasn't been initialised - super() hasn't been called"); } return call && (typeof call === "object" || typeof call === "function") ? call : self; }

function _inherits(subClass, superClass) { if (typeof superClass !== "function" && superClass !== null) { throw new TypeError("Super expression must either be null or a function, not " + typeof superClass); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, enumerable: false, writable: true, configurable: true } }); if (superClass) Object.setPrototypeOf ? Object.setPrototypeOf(subClass, superClass) : subClass.__proto__ = superClass; } /**
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                * @file menu-button.js
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                */


/**
 * A button class with a popup menu
 *
 * @param {Player|Object} player
 * @param {Object=} options
 * @extends Button
 * @class MenuButton
 */
var MenuButton = function (_ClickableComponent) {
  _inherits(MenuButton, _ClickableComponent);

  function MenuButton(player) {
    var options = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : {};

    _classCallCheck(this, MenuButton);

    var _this = _possibleConstructorReturn(this, _ClickableComponent.call(this, player, options));

    _this.update();

    _this.enabled_ = true;

    _this.el_.setAttribute('aria-haspopup', 'true');
    _this.el_.setAttribute('role', 'menuitem');
    _this.on('keydown', _this.handleSubmenuKeyPress);
    return _this;
  }

  /**
   * Update menu
   *
   * @method update
   */


  MenuButton.prototype.update = function update() {
    var menu = this.createMenu();

    if (this.menu) {
      this.removeChild(this.menu);
    }

    this.menu = menu;
    this.addChild(menu);

    /**
     * Track the state of the menu button
     *
     * @type {Boolean}
     * @private
     */
    this.buttonPressed_ = false;
    this.el_.setAttribute('aria-expanded', 'false');

    if (this.items && this.items.length === 0) {
      this.hide();
    } else if (this.items && this.items.length > 1) {
      this.show();
    }
  };

  /**
   * Create menu
   *
   * @return {Menu} The constructed menu
   * @method createMenu
   */


  MenuButton.prototype.createMenu = function createMenu() {
    var menu = new _menu2['default'](this.player_);

    // Add a title list item to the top
    if (this.options_.title) {
      var title = Dom.createEl('li', {
        className: 'vjs-menu-title',
        innerHTML: (0, _toTitleCase2['default'])(this.options_.title),
        tabIndex: -1
      });

      menu.children_.unshift(title);
      Dom.insertElFirst(title, menu.contentEl());
    }

    this.items = this.createItems();

    if (this.items) {
      // Add menu items to the menu
      for (var i = 0; i < this.items.length; i++) {
        menu.addItem(this.items[i]);
      }
    }

    return menu;
  };

  /**
   * Create the list of menu items. Specific to each subclass.
   *
   * @method createItems
   */


  MenuButton.prototype.createItems = function createItems() {};

  /**
   * Create the component's DOM element
   *
   * @return {Element}
   * @method createEl
   */


  MenuButton.prototype.createEl = function createEl() {
    return _ClickableComponent.prototype.createEl.call(this, 'div', {
      className: this.buildCSSClass()
    });
  };

  /**
   * Allow sub components to stack CSS class names
   *
   * @return {String} The constructed class name
   * @method buildCSSClass
   */


  MenuButton.prototype.buildCSSClass = function buildCSSClass() {
    var menuButtonClass = 'vjs-menu-button';

    // If the inline option is passed, we want to use different styles altogether.
    if (this.options_.inline === true) {
      menuButtonClass += '-inline';
    } else {
      menuButtonClass += '-popup';
    }

    return 'vjs-menu-button ' + menuButtonClass + ' ' + _ClickableComponent.prototype.buildCSSClass.call(this);
  };

  /**
   * When you click the button it adds focus, which
   * will show the menu indefinitely.
   * So we'll remove focus when the mouse leaves the button.
   * Focus is needed for tab navigation.
   * Allow sub components to stack CSS class names
   *
   * @method handleClick
   */


  MenuButton.prototype.handleClick = function handleClick() {
    this.one(this.menu.contentEl(), 'mouseleave', Fn.bind(this, function (e) {
      this.unpressButton();
      this.el_.blur();
    }));
    if (this.buttonPressed_) {
      this.unpressButton();
    } else {
      this.pressButton();
    }
  };

  /**
   * Handle key press on menu
   *
   * @param {Object} event Key press event
   * @method handleKeyPress
   */


  MenuButton.prototype.handleKeyPress = function handleKeyPress(event) {

    // Escape (27) key or Tab (9) key unpress the 'button'
    if (event.which === 27 || event.which === 9) {
      if (this.buttonPressed_) {
        this.unpressButton();
      }
      // Don't preventDefault for Tab key - we still want to lose focus
      if (event.which !== 9) {
        event.preventDefault();
      }
      // Up (38) key or Down (40) key press the 'button'
    } else if (event.which === 38 || event.which === 40) {
      if (!this.buttonPressed_) {
        this.pressButton();
        event.preventDefault();
      }
    } else {
      _ClickableComponent.prototype.handleKeyPress.call(this, event);
    }
  };

  /**
   * Handle key press on submenu
   *
   * @param {Object} event Key press event
   * @method handleSubmenuKeyPress
   */


  MenuButton.prototype.handleSubmenuKeyPress = function handleSubmenuKeyPress(event) {

    // Escape (27) key or Tab (9) key unpress the 'button'
    if (event.which === 27 || event.which === 9) {
      if (this.buttonPressed_) {
        this.unpressButton();
      }
      // Don't preventDefault for Tab key - we still want to lose focus
      if (event.which !== 9) {
        event.preventDefault();
      }
    }
  };

  /**
   * Makes changes based on button pressed
   *
   * @method pressButton
   */


  MenuButton.prototype.pressButton = function pressButton() {
    if (this.enabled_) {
      this.buttonPressed_ = true;
      this.menu.lockShowing();
      this.el_.setAttribute('aria-expanded', 'true');
      // set the focus into the submenu
      this.menu.focus();
    }
  };

  /**
   * Makes changes based on button unpressed
   *
   * @method unpressButton
   */


  MenuButton.prototype.unpressButton = function unpressButton() {
    if (this.enabled_) {
      this.buttonPressed_ = false;
      this.menu.unlockShowing();
      this.el_.setAttribute('aria-expanded', 'false');
      // Set focus back to this menu button
      this.el_.focus();
    }
  };

  /**
   * Disable the menu button
   *
   * @return {Component}
   * @method disable
   */


  MenuButton.prototype.disable = function disable() {
    // Unpress, but don't force focus on this button
    this.buttonPressed_ = false;
    this.menu.unlockShowing();
    this.el_.setAttribute('aria-expanded', 'false');

    this.enabled_ = false;

    return _ClickableComponent.prototype.disable.call(this);
  };

  /**
   * Enable the menu button
   *
   * @return {Component}
   * @method disable
   */


  MenuButton.prototype.enable = function enable() {
    this.enabled_ = true;

    return _ClickableComponent.prototype.enable.call(this);
  };

  return MenuButton;
}(_clickableComponent2['default']);

_component2['default'].registerComponent('MenuButton', MenuButton);
exports['default'] = MenuButton;
