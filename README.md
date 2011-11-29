
These scripts fetch data from ioBridge JSON-API, store it in a MongoDB database and display it as charts and tables. Configured to use temperature, humidity and lightness sensors on ioBridge channels 1, 2 and 3.


How to use the tools
--------------------

1. Get ioBridge module, set it up etc.
2. Set these files on a server with MongoDB PHP driver
3. Set up a MongoDB database (e.g. [https://mongolab.com/](MongoLab))
4. Save your database details and api key into a file (example on the root directory), save this file outside your root directory and set the path in include/database.php
5. Set a cronjob to fetch data from ioBridge API (example in the root directory)
