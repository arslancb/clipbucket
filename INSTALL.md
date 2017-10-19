# Install

## CentOS 7

Installing ffmpeg, flvtool2, mp4box and ImageMagick

This is a quick guide to installing the following in CentOS 7:

- ffmpeg
- flvtool2
- mp4box
- imagemagick

CentOS 7 requires a tweak to the process I’d used for CentOS 6.
The links below are generally suitable for EL7.

### Installing ffmpeg

If you don’t have the EPEL repo installed already:

    sudo yum -y install epel-release

Import a repo from Nux (this is third party, obviously):

    sudo rpm --import http://li.nux.ro/download/nux/RPM-GPG-KEY-nux.ro
    sudo rpm -Uvh http://li.nux.ro/download/nux/dextop/el7/x86_64/nux-dextop-release-0-5.el7.nux.noarch.rpm

Install ffmpeg from this repo:

    sudo yum -y install ffmpeg ffmpeg-devel

Confirm it’s working:

    ffmpeg


### flvtool2

This is easiest to grab via RubyGems.

Install Ruby and RubyGems

    yum install -y ruby rubygems

And then it’s a simple gem install:

    gem install flvtool2


### mp4box (GPAC)

This one is quite easy on CentOS 7:

    yum install -y gpac mediainfo

### imagemagick

This package is much more common, so the install is also more straightforward.

Install any prerequisites:

    yum install -y gcc php-devel php-pear

Install the package itself:

    yum install -y ImageMagick ImageMagick-devel

Install it into PHP:

    pecl install imagick
    echo "extension=imagick.so" > /etc/php.d/imagick.ini


Finishing up.

Last, but not least:

    service httpd restart
