#!/bin/sh
symfony server:stop
yarn encore dev
symfony server:start -d
symfony open:local
