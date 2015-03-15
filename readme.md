IMGRESIZE
========
Easiest way for resize img
We glad to introduce easiest way to resize any img on air by server side.


How to integrate:
--------
1. Upload archive to your server in root directory of your project and unzip it
>unzip /path/to/zipfile

2. Set to folder recursively chmod 0755
>chmod -R 0755 /path/to/folder

3. Add this line to .htaccess somewhere on top of file:
>RewriteRule (.+\.(png|jpg|gif|tiff)[:\dxa-z]+)$ /imgResize/resizeOnAir.php?file=$1 [NC,L]

4. Congrulation! All works!


For more info and support, please visit as on http://imgresize.mobypolo.com
---------