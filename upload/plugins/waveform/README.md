#Plugin ClipBucket - Waveform

## Requirement - *Minimum requis*
/!\ This plugin need the Expand Video Manager plugin was installed in order to work.

*/!\ Ce plugin nécessite que le plugin Expand Video Manager soit installé pour pouvoir fonctionner.*

## Installation
Go to the plugin administration panel and install the "Waveform" plugin.

*Activer le plugin "Waveform" depuis la rubrique plugin de l'administration.*

## Usage
An item is added in video edit administration page. Click, wait and see :)

*Une entrée est ajoutée dans la page d'administration d'édition de video. Cliquer, attendre et regarder :)*

![Waveform screenshoot](https://raw.githubusercontent.com/UHDF/clipbucket/develop/upload/plugins/waveform/waveform_capture.png)

Move your mouse over the waveform will display a line and indicates the time in the video. When you click on, the video start to play. A moving cursor appear while video is playing.

The button "Silence Finder" will display (after small computing) a list of link where there is no sound. The default "Seuil" is 0. It search for real no sound. Anyway, in the real life it becomes hard to get no sound (equals to 0). You can change the "Seuil" with max to 58.

*En déplaçant votre souris sur la forme d'onde, un trait apparé et le temps indiqué change. Lorsque vous cliquez dessus, la vidéo commence à jouer à partir de ce moment. Un curseur se déplace pendant que la vidéo et lu.*

*Le bouton "Silence Finder" affiche (après une petit calcul) une liste de lien ou il n'y à pas de son. Le seuil par défault est 0. Cela cherche les vrais silences. Quoiqu'il en soit, dans la vraie vie il est difficile d'obtenir du silence (égal à 0). Vous pouvez changer le "Seuil" avec un maximum de 58.*

## ChangeLog
### [1.0] - 2016-10-28
#### Added
- Generate a waveform scaled on duration
- Display silence duration

#### Changed
- Scale computing of waveform
- Computing silence finder function

### [0.2] - 2016-10-24
#### Added
- Cursor on mouseover event
- Play video onclick on image
- Listen event while video is playing, paused or ended

#### Changed
- Modify style of generated waveform image
- Use canvas tag instead of img tag
- Client display width adjustement

### [0.1] - 2016-10-20
#### Added
- Simple Waveform display
