#!/bin/sh
cat /tmp/default.conf.mustache | /usr/bin/mo > /etc/nginx/http.d/default.conf
supervisord -c /etc/supervisor.conf
