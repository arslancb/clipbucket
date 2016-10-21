/*
 *  Copyright (c) 2013 Funny or Die, Inc.
 *  http://www.funnyordie.com
 *  https://github.com/funnyordie/videojs-relatedCarousel/blob/master/LICENSE.md
 */

(function(vjs) {
  var extend = function(obj) {
      var arg, i, k;
      for (i = 1; i < arguments.length; i++) {
        arg = arguments[i];
        for (k in arg) {
          if (arg.hasOwnProperty(k)) {
            obj[k] = arg[k];
          }
        }
      }
      return obj;
    },
    defaults = [
      {
        imageSrc: '',
        title: '',
        url: ''
      }
    ];

  vjs.plugin('relatedCarousel', function(options) {
    var player = this,
      settings = extend([], defaults, options || []),
      carousel = function() {
        this.controlBarButton = document.createElement('div');

        this.holderDiv = document.createElement('div');
        this.title = document.createElement('h5');

        this.viewport = document.createElement('div');
        this.items = document.createElement('ul');

        this.leftButton = document.createElement('div');
        this.leftButtonContent = document.createElement('div');

        this.rightButton = document.createElement('div');
        this.rightButtonContent = document.createElement('div');

        this.config = null;
        this.currentPosition = 0;
        this.maxPosition = 0;
        this.currentVideoIndex = -1;
        this.isOpen = false;
        this.callbacksEnabled = true;
      };

    carousel.prototype.open = function() {
      if (!this.isOpen) {
        if (!this.holderDiv.className.match(/(?:^|\s)active(?!\S)/g)) {
          this.holderDiv.className = this.holderDiv.className + " active";
        }
      }
      this.isOpen = true;
    };

    carousel.prototype.close = function() {
      if (this.isOpen) {
        if (this.holderDiv.className.match(/(?:^|\s)active(?!\S)/g)) {
          this.holderDiv.className = this.holderDiv.className.replace(/(?:^|\s)active(?!\S)/g, '')
        }
      }
      this.isOpen = false;
    };

    carousel.prototype.toggle = function() {
      if (this.isOpen) {
        this.close();
      } else {
        this.open();
      }
    };

    carousel.prototype.initiateVideo = function(index, config, trigger) {
      if (config.callback !== undefined) {
        if (this.callbacksEnabled) {
          this.currentVideoIndex = index;
          config.callback(player, config, {
            trigger: trigger,
            newIndex: this.currentVideoIndex
          });
        }
      } else {
        this.currentVideoIndex = index;
        this.close();
        if (config.src !== undefined) {
          player.src(config.src);
          player.play();
        } else {
          window.location = config.url;
        }
      }
    };

    carousel.prototype.onItemClick = function(index, element, config) {
      var self = this;
      element.onclick = function(e) {
        e.preventDefault();
        self.initiateVideo(index, config, e);
      };
    };

    carousel.prototype.buildCarousel = function(config) {
      this.config = config;
      this.items.innerHTML = '';
      this.maxPosition = (-110) * (this.config.length - 1)

      // Initialize carousel items
      for (var i = 0; i < this.config.length; i++) {
        var item = document.createElement('li');
        item.className = 'carousel-item';

        var img = document.createElement('img');
        img.src = this.config[i].imageSrc;
        img.className = 'vjs-carousel-thumbnail';
        img.alt = this.config[i].title;
        img.style.width = '100%';

        var anchor = document.createElement('a');

        if (!this.config[i].url) {
          this.config[i].url = '#';
        }

        anchor.href = this.config[i].url;
        anchor.title = this.config[i].title;
        anchor.appendChild(img);

        this.onItemClick(i, anchor, this.config[i]);

        var title = document.createElement('div');
        title.className = 'carousel-item-title';
        title.innerHTML = this.config[i].title;
        anchor.appendChild(title);

        item.appendChild(anchor);
        this.items.appendChild(item);
      }

      this.currentVideoIndex = -1;
      this.currentPosition = 0;
      this.items.style.left = this.currentPosition + 'px';
    };

    player.carousel = new carousel();

    /* Menu Button */
    player.carousel.controlBarButton.className = 'vjs-button vjs-control vjs-related-carousel-button icon-videojs-carousel-toggle icon-related';
    player.carousel.controlBarButton.title = "More Videos";
     
    player.carousel.holderDiv.className = 'vjs-related-carousel-holder';
    player.carousel.title.innerHTML = 'More Videos';
    player.carousel.viewport.className = 'vjs-carousel-viewport';
    player.carousel.items.className = 'carousel-items';
    player.carousel.leftButton.className = 'vjs-carousel-left-button';
    player.carousel.leftButtonContent.className = 'icon-videojs-carousel-left icon-prev';
    player.carousel.rightButton.className = 'vjs-carousel-right-button';
    player.carousel.rightButtonContent.className = 'icon-videojs-carousel-right icon-next';

    // Add all items to DOM
    var controlBarChilds =  player.controlBar.el().childNodes;
    for (var i = 0; i < controlBarChilds.length; i++) {
        if (controlBarChilds[i].id == 'vjs-cb-logo'){
            cbVjsLogo = controlBarChilds[i];
        }
    }
    player.controlBar.el().insertBefore(player.carousel.controlBarButton,cbVjsLogo);
    player.carousel.holderDiv.appendChild(player.carousel.title);
    player.el().appendChild(player.carousel.holderDiv);
    player.carousel.holderDiv.appendChild(player.carousel.viewport);
    player.carousel.viewport.appendChild(player.carousel.items);
    player.carousel.leftButton.appendChild(player.carousel.leftButtonContent);
    player.carousel.holderDiv.appendChild(player.carousel.leftButton);
    player.carousel.rightButton.appendChild(player.carousel.rightButtonContent);
    player.carousel.holderDiv.appendChild(player.carousel.rightButton);

    // Add event handlers
    player.carousel.controlBarButton.onclick = function(e) {
      player.carousel.toggle();
    };
    player.carousel.leftButton.onclick = function() {
      if (player.carousel.currentPosition === 0) {
        return;
      }
      player.carousel.currentPosition = player.carousel.currentPosition + 110;
      player.carousel.items.style.left = player.carousel.currentPosition + 'px';
    };

    player.carousel.rightButton.onclick = function() {
      if (player.carousel.currentPosition <= player.carousel.maxPosition) {
        return;
      }
      player.carousel.currentPosition = player.carousel.currentPosition - 110;
      player.carousel.items.style.left = player.carousel.currentPosition + 'px';
    };

    player.carousel.buildCarousel(settings);

    // Player events
    player.on('mouseout', function() {
      if (!player.carousel.holderDiv.className.match(/(?:^|\s)vjs-fade-out(?!\S)/g)) {
        player.carousel.holderDiv.className = player.carousel.holderDiv.className + " vjs-fade-out";
      }
    });
    player.on('mouseover', function() {
      player.carousel.holderDiv.className = player.carousel.holderDiv.className.replace(/(?:^|\s)vjs-fade-out(?!\S)/g, '');
    });
    player.on('timeupdate', function() {
      /*if (player.ended()) {
        if (player.carousel.currentVideoIndex === player.carousel.config.length) {
          return;
        }

        player.carousel.initiateVideo(player.carousel.currentVideoIndex + 1, player.carousel.config[player.carousel.currentVideoIndex + 1], player);
      }*/
    });
  });
}(videojs));
