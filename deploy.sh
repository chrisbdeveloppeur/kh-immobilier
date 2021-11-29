#!/bin/sh
rsync -avz ./ -e "ssh -p 5022"  weskyknm@node1-ca.n0c.com:~/public_html/edit-word --include=public/build --include=public/bundles --include=public/.htaccess --include=vendor --exclude-from=.gitignore --exclude=".env.local" --exclude="migrations/*" --exclude="public/images" --exclude="public/documents"
