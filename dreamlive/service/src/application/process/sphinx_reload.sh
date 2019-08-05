#!/bin/sh
#/usr/local/sphinx/bin/indexer --merge user user_incr --rotate &&
/usr/local/sphinx/bin/indexer -c /usr/local/sphinx/etc/sphinx.conf --rotate user
