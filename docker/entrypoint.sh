#!/bin/sh
set -eu

php yii migrate --interactive=0

exec "$@"
