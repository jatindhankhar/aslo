import os
import sys

SETTINGS_DIR = os.path.realpath(os.path.join(os.path.dirname(__file__), \
os.path.pardir, os.path.pardir,  "site/app/config/migrations"))

sys.path.append(SETTINGS_DIR)

import settings

s = settings.params

### IT Change this to connect to the slave that sphinx reads from
MYSQL_PASS = s['password']
MYSQL_USER = s['user']
MYSQL_HOST = s['host']
MYSQL_NAME = s['database']
### /IT

CATALOG_PATH = '/opt/local/data/sphinx'
LOG_PATH     = '/opt/local/log/searchd'
