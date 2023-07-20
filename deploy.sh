#!/bin/sh
rsync -avz ./ -e "ssh -p 5022"  ddkjywcr@node171-eu.n0c.com:~/public_html/kh-immobilier --include=public/build --include=public/bundles --include=public/.htaccess --include=.htaccess --exclude=.env --exclude=vendor --exclude-from=.gitignore --exclude=".*" --exclude=".env.local" --exclude="public/documents" --exclude=MailHog_windows_386.exe --exclude=deploy.sh
