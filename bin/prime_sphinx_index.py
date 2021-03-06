#!/usr/bin/env python2.6

import os
import sys
import re
import mimetypes
import atexit

from time import time
from lockfile import FileLock, AlreadyLocked

SETTINGS_DIR = os.path.realpath(os.path.join(os.path.dirname(__file__), \
os.path.pardir,  "site/app/config/migrations"))

sys.path.append(SETTINGS_DIR)

import settings

import warnings

warnings.simplefilter('ignore')
import MySQLdb
warnings.resetwarnings()

s  = settings.params

### IT Change this to connect to the slave that sphinx reads from
MYSQL_PASS = s['password']
MYSQL_USER = s['user']
MYSQL_HOST = s['host']
MYSQL_NAME = s['database']
### /IT

def get_connection():
    db = MySQLdb.connect(passwd=MYSQL_PASS, db=MYSQL_NAME,
    host=MYSQL_HOST, user=MYSQL_USER)
    return db

class SphinxIndexPrimer:
    def __init__(self):
        self.db = get_connection()

    def prime_index(self):
        self.init_table()
        self.populate_feed()
        self.copy_tmp()
        self.db.commit()
        self.db.close()
    
    def copy_tmp(self):
        self.db.query("""DROP TABLE IF EXISTS sphinx_index_feed""")
        self.db.query("""
        RENAME TABLE sphinx_index_feed_tmp TO sphinx_index_feed
        """)
        

    def init_table(self):
        db = self.db
        db.query("""DROP TABLE IF EXISTS sphinx_index_feed_tmp""")

        db.query("""CREATE TABLE sphinx_index_feed_tmp (
          `id` int(11) unsigned NOT NULL auto_increment,

          app_id int(2) unsigned,

          addon_id int(11) unsigned NOT NULL,
          authors varchar(255) default NULL,
          tags varchar(255) default NULL,
          type int(11) unsigned NOT NULL default '0',
          addon_versions varchar(255) default NULL,
          status tinyint(2) unsigned NOT NULL default '0',
          averagerating varchar(255) DEFAULT NULL,
          weeklydownloads int(11) unsigned NOT NULL DEFAULT '0',
          totaldownloads int(11) unsigned NOT NULL DEFAULT '0',
          inactive tinyint(1) unsigned NOT NULL DEFAULT '1',

          locale varchar(10) NOT NULL DEFAULT '',
          locale_ord int(10) unsigned NOT NULL,
          name text,
          homepage text,
          description text,
          summary text,
          developercomments text,

          max_ver bigint(20) unsigned,
          min_ver bigint(20) unsigned,
          created int(11),
          modified int(11),
          PRIMARY KEY (id),
          UNIQUE KEY (addon_id, app_id, locale)
        ) ENGINE=InnoDB
        """)
        
        print "Index tmp table initialized."

    def add_basic_data(self):
        print "Priming index with addons/addon_id/locale data"

        self.db.query("""
        INSERT IGNORE INTO
            sphinx_index_feed_tmp
            (addon_id, app_id, locale, locale_ord, type, status,
            averagerating, weeklydownloads, totaldownloads, inactive, name,
            created)
        SELECT
            a.id, av.application_id, name.locale, CRC32(locale), addontype_id,
            status, averagerating, weeklydownloads, totaldownloads, inactive,
            LTRIM(name.localized_string) AS name,
            UNIX_TIMESTAMP(a.created) AS created
        FROM
            translations name,
            addons a
        LEFT JOIN versions v ON v.addon_id = a.id
        LEFT JOIN applications_versions av ON av.version_id = v.id
        WHERE a.name = name.id;
        """)

    def add_translated_data(self):
        print "Adding translated data"
        fields = ['homepage', 'description', 'summary', 'developercomments']
        c      = self.db.cursor()
        # c2     = self.db.cursor()

        translations = {}

        for field in fields:
            q = """
            SELECT a.id, locale, localized_string
            FROM translations, addons a
            WHERE translations.id = a.%s
            """ % field

            c.execute(q)
            rows = c.fetchall()

            for row in rows:
                addon_id = row[0]
                locale   = row[1]
                text     = row[2]

                if not addon_id in translations:
                    translations[addon_id]={}
                if not locale in translations[addon_id]:
                    translations[addon_id][locale] = {}
                translations[addon_id][locale][field] = text

        for addon_id,locales in translations.items():
            for locale,data in locales.items():
                q = """
                UPDATE sphinx_index_feed_tmp
                SET homepage=%s, description=%s, summary=%s, developercomments=%s
                WHERE addon_id=%s AND locale=%s
                """
                c.execute(q, (data.get('homepage'), data.get('description'),
                data.get('summary'), data.get('developercomments'),
                addon_id, locale))

        # c2.close()
        c.close()

    def add_modified_date(self):
        msg = "Adding date modified"
        q = """
        SELECT v.addon_id,
          UNIX_TIMESTAMP(MAX(IFNULL(f.datestatuschanged, f.created)))
        FROM versions AS v
        INNER JOIN files AS f ON f.status = 4 AND f.version_id = v.id
        GROUP BY v.addon_id
        """
        self.add_data(query=q, msg=msg, field='modified')

    def add_authors(self):
        msg = "Adding authors"
        gq = """
        SELECT
            addon_id,
            GROUP_CONCAT(IFNULL(nickname, CONCAT(firstname, ' ', lastname)))
        FROM addons_users au, users u
        WHERE au.user_id = u.id AND listed =1 GROUP BY addon_id
        """

        self.add_data(query=gq, msg=msg, field='authors', max_len=255)

    def add_tags(self):
        msg = "Adding tag information."
        gq = """
        SELECT addon_id, GROUP_CONCAT(tag_text)
        FROM users_tags_addons, tags
        WHERE tags.id = tag_id
        GROUP BY addon_id
        """

        self.add_data(query=gq, msg=msg, field='tags', max_len=255)

    def add_versions(self):
        msg = "Adding version info"
        q = """
        SELECT addon_id,
        GROUP_CONCAT(DISTINCT version ORDER BY modified DESC)
        FROM versions GROUP BY addon_id
        """
        pq = "SET SESSION group_concat_max_len = 2000"
        self.add_data(query=q, msg=msg, field='addon_versions',
        max_len=255, pre_query=pq)

    def add_data(self, query, field, msg, max_len=None,
    pre_query=None):
        c = self.db.cursor()
        print msg
        if pre_query:
            c.execute(pre_query)
            
        warnings.simplefilter('ignore')
        c.execute(query)
        warnings.resetwarnings()
        rows = c.fetchall()
        c2 = self.db.cursor()
        for row in rows:
            addon_id = row[0]
            items    = row[1]

            if max_len:
                items = items[:max_len]

            c2.execute("""
            UPDATE sphinx_index_feed_tmp SET %s=%%s WHERE addon_id=%%s
            """ % field, (items, addon_id))

        c2.close()
        c.close()

    def add_appversions(self):
        print "Updating max/min versions"

        q = """
        SELECT
          a.id,
          av.application_id,
          min(min.version_int),
          max(max.version_int)
        FROM
          addons a,
          versions v,
          files f,
          applications_versions av,
          appversions max,
          appversions min
        WHERE
          f.version_id = v.id
          AND v.addon_id = a.id
          AND av.version_id = v.id
          AND av.max = max.id
          AND av.min = min.id
          AND f.status = 4
        GROUP BY application_id, a.id ;
        """
        c  = self.db.cursor()
        c2 = self.db.cursor()
        c.execute(q)
        rows = c.fetchall()

        for row in rows:
            addon_id = row[0]
            app_id = row[1]
            min_ver = row[2]
            max_ver = row[3]

            c2.execute("""
            UPDATE sphinx_index_feed_tmp SET min_ver=%s, max_ver=%s
            WHERE addon_id = %s AND app_id = %s
            """, (min_ver,max_ver,addon_id,app_id))

        c2.close()
        c.close()

    def populate_feed(self):
        """
        Fill the feed with the data to be indexed.
        """
        t = Timer()
        self.add_basic_data()
        print t.elapsed(reset=True)
        self.add_authors()
        print t.elapsed(reset=True)
        self.add_tags()
        print t.elapsed(reset=True)
        self.add_versions()
        print t.elapsed(reset=True)
        self.add_translated_data()
        print t.elapsed(reset=True)
        self.add_appversions()
        print t.elapsed(reset=True)
        self.add_modified_date()
        print t.elapsed(reset=True)


class Timer:
    def __init__(self):
        self.start = time()

    def elapsed(self, reset=False):
        elapsed = time()-self.start
        if reset:
            self.start = time()
        return elapsed


if __name__ == "__main__":
    # lock this
    lock = FileLock("/tmp/sphinx-%s" % MYSQL_NAME)
    try:
        lock.acquire(0)
        atexit.register(lock.release)
        s = SphinxIndexPrimer()
        s.prime_index()
    except AlreadyLocked:
        sys.stderr.write("Another indexer is running")
        sys.exit(-1)
