Run ClipBucket 1.7.x on Win32 (limejoe)

Step 1:
Download the Win32 binaries from http://clipbucket.org/scm/viewvc.php/etc/1.x/1.7/win32/?root=clipbucket

Step 2:
Add IUSR_machine privileges to execute CLI from webserver.

Step 3: Copy win32 php folder contents to your php installation root dir. And copy dll's from ext dir to the ext dir in your php path.

step 4: Setup admin_area with encoding tools path. Make sure to point to .exe's. As pointing to the directory alone will nto work. eg. c:/ffmpeg/ffmpeg.exe or c:/php/php-win.exe. !important

Step 5: Modify conversion.conf.php to user ffmpeg.win32.php isntead of ffmpeg.class.php.

Your CB version should now work on windows!

Have fun.
