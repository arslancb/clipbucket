'use strict';

exports.__esModule = true;

var _events = require('./utils/events.js');

var Events = _interopRequireWildcard(_events);

function _interopRequireWildcard(obj) { if (obj && obj.__esModule) { return obj; } else { var newObj = {}; if (obj != null) { for (var key in obj) { if (Object.prototype.hasOwnProperty.call(obj, key)) newObj[key] = obj[key]; } } newObj['default'] = obj; return newObj; } }

var EventTarget = function EventTarget() {}; /**
                                              * @file event-target.js
                                              */


EventTarget.prototype.allowedEvents_ = {};

EventTarget.prototype.on = function (type, fn) {
  // Remove the addEventListener alias before calling Events.on
  // so we don't get into an infinite type loop
  var ael = this.addEventListener;

  this.addEventListener = function () {};
  Events.on(this, type, fn);
  this.addEventListener = ael;
};

EventTarget.prototype.addEventListener = EventTarget.prototype.on;

EventTarget.prototype.off = function (type, fn) {
  Events.off(this, type, fn);
};

EventTarget.prototype.removeEventListener = EventTarget.prototype.off;

EventTarget.prototype.one = function (type, fn) {
  // Remove the addEventListener alias before calling Events.on
  // so we don't get into an infinite type loop
  var ael = this.addEventListener;

  this.addEventListener = function () {};
  Events.one(this, type, fn);
  this.addEventListener = ael;
};

EventTarget.prototype.trigger = function (event) {
  var type = event.type || event;

  if (typeof event === 'string') {
    event = { type: type };
  }
  event = Events.fixEvent(event);

  if (this.allowedEvents_[type] && this['on' + type]) {
    this['on' + type](event);
  }

  Events.trigger(this, event);
};

// The standard DOM EventTarget.dispatchEvent() is aliased to trigger()
EventTarget.prototype.dispatchEvent = EventTarget.prototype.trigger;

exports['default'] = EventTarget;
