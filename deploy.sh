#!/bin/sh
rsync -avz ./ -e "ssh -p 5022"  ddkjywcr@node171-eu.n0c.com:~/public_html/kh-immobilier --include=public/build --include=public/bundles --include=public/.htaccess --include=.env --exclude=".env.local" --exclude="public/images" --exclude="public/documents" --exclude=MailHog_windows_386.exe --exclude=deploy.sh
