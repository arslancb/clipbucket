# Video.js Resolution Switcher [![Build Status](https://travis-ci.org/kmoskwiak/videojs-resolution-switcher.svg?branch=master)](https://travis-ci.org/kmoskwiak/videojs-resolution-switcher) 

Resolution switcher for [video.js v5](https://github.com/videojs/video.js)

## Example

[Working examples](examples) of the plugin you can check out if you're having trouble. Or check out this [demo](https://kmoskwiak.github.io/videojs-resolution-switcher/).

## Getting Started

Install plugin with

**npm**
```
npm i videojs-resolution-switcher
```

or **bower**
```
bower install videojs-resolution-switcher
```


### Setup sources dynamically:

```html
<video id='video' class="video-js vjs-default-skin"></video>
<script src="video.js"></script>
<script src="videojs-resolution-switcher.js"></script>
<script>
  videojs('video', {
    controls: true,
    plugins: {
        videoJsResolutionSwitcher: {
          default: 'high',
          dynamicLabel: true
        }
      }
  }, function(){
  
    // Add dynamically sources via updateSrc method
    player.updateSrc([
        {
          src: 'http://media.xiph.org/mango/tears_of_steel_1080p.webm',
          type: 'video/webm',
          label: '360'
        },
        {
          src: 'http://mirrorblender.top-ix.org/movies/sintel-1024-surround.mp4',
          type: 'video/mp4',
          label: '720'
        }
      ])

      player.on('resolutionchange', function(){
        console.info('Source changed to %s', player.src())
      })
      
  })
</script>
```

### Or use `<source>` tags:

```html

<video id="video" class="video-js vjs-default-skin" width="1000" controls data-setup='{}'>
   <source src="http://mirrorblender.top-ix.org/movies/sintel-1024-surround.mp4" type='video/mp4' label='SD' />
   <source src="http://media.xiph.org/mango/tears_of_steel_1080p.webm" type='video/webm' label='HD'/>
</video>
<script>
  videojs('video').videoJsResolutionSwitcher()
</script>

```


### YouTube tech

YouTube tech form https://github.com/eXon/videojs-youtube

```html
<video id='video' class="video-js vjs-default-skin"></video>
<script src="../lib/videojs-resolution-switcher.js"></script>
<script>
	videojs('video', {
		controls: true,
		techOrder:  ["youtube"],
		sources: [{ "type": "video/youtube", "src": "https://www.youtube.com/watch?v=iD_MyDbP_ZE"}],
		plugins: {
			videoJsResolutionSwitcher: {
				default: 'low',
				dynamicLabel: true
			}
		}
	}, function(){
		var player = this;
		player.on('resolutionchange', function(){
			console.info('Source changed')
		})
	});

</script>

```

### Flash tech

When using flash tech `preload="auto"` is required.

## Source options

Sources can passed to player using `updateSrc` method or `<source>` tag as shown above. Avalible options for each source are:
* label - `String` (required) is shown in menu (ex. 'SD' or '360p')
* res - `Number` is resolution of video used for sorting (ex. 360 or 1080)

## Plugin options

You can pass options to plugin like this:

```javascript

videojs('video', {
      controls: true,
      muted: true,
      width: 1000,
      plugins: {
        videoJsResolutionSwitcher: {
          default: 'low'
        }
      }
    }, function(){
      // this is player
    })
```
### Avalible options:
* default - `{Number}|'low'|'high'` - default resolution. If any `Number` is passed plugin will try to choose source based on `res` parameter. If `low` or `high` is passed, plugin will choose respectively worse or best resolution (if `res` parameter is specified). If `res` parameter is not specified plugin assumes that sources array is sorted from best to worse.
* dynamicLabel - `{Boolean}` - if `true` current label will be displayed in control bar. By default gear icon is displayed.
* customSourcePicker - `{Function}` - custom function for selecting source.


## Methods


### updateSrc([source])
Returns video.js player object if used as setter. If `source` is not passed it acts like [player.src()](http://docs.videojs.com/docs/api/player.html#Methodssrc)
```javascript

// Update video sources
player.updateSrc([
  { type: "video/mp4", src: "http://www.example.com/path/to/video.mp4", label: 'SD' },
  { type: "video/mp4", src: "http://www.example.com/path/to/video.mp4", label: 'HD' },
  { type: "video/mp4", src: "http://www.example.com/path/to/video.mp4", label: '4k' }
])

```
#### PARAMETERS:
| name | type | required | description |
|:----:|:----:|:--------:|:-----------:|
| source| array| no | array of sources |

### currentResolution([label], [customSourcePicker])
If used as getter returns current resolution object:
```javascript
  {
    label: 'SD', // current label
    sources: [
      { type: "video/webm", src: "http://www.example.com/path/to/video.webm", label: 'SD' },
      { type: "video/mp4", src: "http://www.example.com/path/to/video.mp4", label: 'SD' }
    ] // array of sources with current label
  }
```

If used as setter returns video.js player object.


```javascript

// Get current resolution
player.currentResolution(); // returns object {label '', sources: []}

// Set resolution
player.currentResolution('SD'); // returns videojs player object
```
#### PARAMETERS:
| name | type | required | description |
|:----:|:----:|:--------:|:-----------:|
| label| string| no | label name |
| customSourcePicker | function | no | cutom function to choose source |

#### customSourcePicker
If there is more than one source with the same label, player will choose source automatically. This behavior can be changed if `customSourcePicker` is passed.

`customSourcePicker` must return `player` object.
```javascript
player.currentResolution('SD', function(_player, _sources, _label){
  return _player.src(_sources[0]); \\ Always select first source in array
});
```
`customSourcePicker` accepst 3 arguments.

| name | type | required | description |
|:----:|:----:|:--------:|:-----------:|
| player| Object | yes | videojs player object |
| sources | Array | no | array of sources |
| label | String | no | name of label |

`customSourcePicker` may be passed in options when player is initialized:
```javascript

var myCustomSrcPicker = function(_p, _s, _l){
  // select any source you want
  return _p.src(selectedSource);
}

videojs('video', {
      controls: true,
      muted: true,
      width: 1000,
      plugins: {
        videoJsResolutionSwitcher: {
          default: 'low',
          customSourcePicker: myCustomSrcPicker
        }
      }
    }, function(){
      // this is player
    })
```


### getGroupedSrc()
Returns sources grouped by label, resolution and type.


## Events

### resolutionchange `EVENT`

> Fired when resolution is changed
