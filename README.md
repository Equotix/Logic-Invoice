# Logic Invoice

Logic Invoice is an open source accounting and invoicing solution built with PHP. For more information, visit http://www.logicinvoice.com

## Requirements

1. PHP 5.3 and above
2. MySQL database
3. Linux / Windows web server (Apache preferred)


## Installation Instructions

1. Upload all the files INSIDE the 'upload' folder onto your web server.
2. Ensure the following folders are writable (0755 or 0777)
   - /
   - /admin/
   - /system/cache/
3. On your preferred web browser, load the application (www.your-website.com/)
4. Follow the installation instructions to complete the installation
5. Upon completing installation, set up a cron job to trigger admin/cron.php hourly / daily.
6. To use SEO URLs, rename .htaccess.txt to .htaccess (remove .txt extension)

## Documentation

For more information on installation and administration, do use our documentation. Documentation is available at http://docs.logicinvoice.com.

## Running with docker

WARNING: USE ONLY FOR DEVELOPMENT.
```
docker-compose up -d
```

MySQL hostname: `database`
MySQL database: `database`
MySQL user:     `root`
MySQL password: `helloworld123`

## Reporting Bugs

Please report bugs through github.

## Security

We take security very seriously at Logic Invoice. If you discover any major security flaw, please contact us directly and do not disclose publicly until the issue has been resolved.

## License

Licensed under the GNU General Public License Version 3; you may not use this work except in compliance with the License. You may obtain a copy of the license in the LICENSE file.
