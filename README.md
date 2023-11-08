# Simple-Status-Page
Simple Status Page for [PHP Server Monitor](https://github.com/phpservermon "PHP Server Monitor").

![](https://raw.githubusercontent.com/lsalp/Simple-Status-Page/main/Screenshot.png)

## Installation
Place the file status.php in the folder of the PHP Server Monitor installation.
Copy the file codeToRegexp.php.sample in the same folder with the name codeToRegexp.
Edit this file and add a php list of (random) code translated to regexp applied to your labels.
then go to /status.php?code=mycode and you will see the public page for the list of server matching the regexp defined for the code mycode.
If you plan to use it outside the installations folder, make sure to adjust the path of the config file.

Tested with PHP Server Monitor v3.6.0.
