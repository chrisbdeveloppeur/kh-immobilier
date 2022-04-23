#!/bin/sh
rsync -avz ./ -e "ssh -p 5022"  weskyknm@node1-ca.n0c.com:~/public_html/edit-word --include=public/build --include=public/bundles --include=public/.htaccess --include=.env --exclude-from=.gitignore --exclude=".*" --exclude=".env.local" --exclude="public/images" --exclude="public/documents"
