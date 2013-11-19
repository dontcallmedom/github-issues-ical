A simple PHP script that looks up one’s assigned issues on GitHub and exports them as an iCalendar feed (VTODO).

Due dates are taken from  the milestone’s due date to which issues are linked (if any).

## Set up
Install PHP dependencies via composer:
```bash
$ curl -s http://getcomposer.org/installer | php
$ php composer.phar install
```

Configure the script to your own username and access token in index.php