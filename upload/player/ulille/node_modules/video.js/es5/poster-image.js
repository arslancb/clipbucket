'use strict';

exports.__esModule = true;

var _clickableComponent = require('./clickable-component.js');

var _clickableComponent2 = _interopRequireDefault(_clickableComponent);

var _component = require('./component.js');

var _component2 = _interopRequireDefault(_component);

var _fn = require('./utils/fn.js');

var Fn = _interopRequireWildcard(_fn);

var _dom = require('./utils/dom.js');

var Dom = _interopRequireWildcard(_dom);

var _browser = require('./utils/browser.js');

var browser = _interopRequireWildcard(_browser);

function _interopRequireWildcard(obj) { if (obj && obj.__esModule) { return obj; } else { var newObj = {}; if (obj != null) { for (var key in obj) { if (Object.prototype.hasOwnProperty.call(obj, key)) newObj[key] = obj[key]; } } newObj['default'] = obj; return newObj; } }

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { 'default': obj }; }

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _possibleConstructorReturn(self, call) { if (!self) { throw new ReferenceError("this hasn't been initialised - super() hasn't been called"); } return call && (typeof call === "object" || typeof call === "function") ? call : self; }

function _inherits(subClass, superClass) { if (typeof superClass !== "function" && superClass !== null) { throw new TypeError("Super expression must either be null or a function, not " + typeof superClass); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, enumerable: false, writable: true, configurable: true } }); if (superClass) Object.setPrototypeOf ? Object.setPrototypeOf(subClass, superClass) : subClass.__proto__ = superClass; } /**
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                * @file poster-image.js
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                */


/**
 * The component that handles showing the poster image.
 *
 * @param {Player|Object} player
 * @param {Object=} options
 * @extends Button
 * @class PosterImage
 */
var PosterImage = function (_ClickableComponent) {
  _inherits(PosterImage, _ClickableComponent);

  function PosterImage(player, options) {
    _classCallCheck(this, PosterImage);

    var _this = _possibleConstructorReturn(this, _ClickableComponent.call(this, player, options));

    _this.update();
    player.on('posterchange', Fn.bind(_this, _this.update));
    return _this;
  }

  /**
   * Clean up the poster image
   *
   * @method dispose
   */


  PosterImage.prototype.dispose = function dispose() {
    this.player().off('posterchange', this.update);
    _ClickableComponent.prototype.dispose.call(this);
  };

  /**
   * Create the poster's image element
   *
   * @return {Element}
   * @method createEl
   */


  PosterImage.prototype.createEl = function createEl() {
    var el = Dom.createEl('div', {
      className: 'vjs-poster',

      // Don't want poster to be tabbable.
      tabIndex: -1
    });

    // To ensure the poster image resizes while maintaining its original aspect
    // ratio, use a div with `background-size` when available. For browsers that
    // do not support `background-size` (e.g. IE8), fall back on using a regular
    // img element.
    if (!browser.BACKGROUND_SIZE_SUPPORTED) {
      this.fallbackImg_ = Dom.createEl('img');
      el.appendChild(this.fallbackImg_);
    }

    return el;
  };

  /**
   * Event handler for updates to the player's poster source
   *
   * @method update
   */


  PosterImage.prototype.update = function update() {
    var url = this.player().poster();

    this.setSrc(url);

    // If there's no poster source we should display:none on this component
    // so it's not still clickable or right-clickable
    if (url) {
      this.show();
    } else {
      this.hide();
    }
  };

  /**
   * Set the poster source depending on the display method
   *
   * @param {String} url The URL to the poster source
   * @method setSrc
   */


  PosterImage.prototype.setSrc = function setSrc(url) {
    if (this.fallbackImg_) {
      this.fallbackImg_.src = url;
    } else {
      var backgroundImage = '';

      // Any falsey values should stay as an empty string, otherwise
      // this will throw an extra error
      if (url) {
        backgroundImage = 'url("' + url + '")';
      }

      this.el_.style.backgroundImage = backgroundImage;
    }
  };

  /**
   * Event handler for clicks on the poster image
   *
   * @method handleClick
   */


  PosterImage.prototype.handleClick = function handleClick() {
    // We don't want a click to trigger playback when controls are disabled
    // but CSS should be hiding the poster to prevent that from happening
    if (this.player_.paused()) {
      this.player_.play();
    } else {
      this.player_.pause();
    }
  };

  return PosterImage;
}(_clickableComponent2['default']);

_component2['default'].registerComponent('PosterImage', PosterImage);
exports['default'] = PosterImage;
