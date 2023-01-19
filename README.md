# kh-immobilier.com 


####**Faire la création de fichiers HTML pour l'interprétation des test Unitaire PhpUnits :**
`vendor/bin/phpunit --coverage-html public/test-coverage`
#####pour ne tester qu'une seule méthode à la fois :
`vendor/bin/phpunit --filter=testDefault`

#### .env.local :
 In all environments, the following files are loaded if they exist,
 the latter taking precedence over the former:

  * .env                contains default values for the environment variables needed by the app
  * .env.local          uncommitted file with local overrides
  * .env.$APP_ENV       committed environment-specific defaults
  * .env.$APP_ENV.local uncommitted environment-specific overrides

 Real environment variables win over .env files.

 DO NOT DEFINE PRODUCTION SECRETS IN THIS FILE NOR IN ANY OTHER COMMITTED FILES.

 Run "composer dump-env prod" to compile .env files for production use (requires symfony/flex >=1.2).
 https://symfony.com/doc/current/best_practices.htmluse-environment-variables-for-infrastructure-configuration

> symfony/framework-bundle 
APP_ENV=dev
APP_SECRET=fab62bdec32a4f310d12d180d756b3d4
TRUSTED_PROXIES=127.0.0.0/8,10.0.0.0/8,172.16.0.0/12,192.168.0.0/16
TRUSTED_HOSTS='^(localhost|example\.com)$'
< symfony/framework-bundle 

> doctrine/doctrine-bundle 
 Format described at https://www.doctrine-project.org/projects/doctrine-dbal/en/latest/reference/configuration.htmlconnecting-using-a-url
 IMPORTANT: You MUST configure your server version, either here or in config/packages/doctrine.yaml

 DATABASE_URL="sqlite:///%kernel.project_dir%/var/data.db"
 DATABASE_URL="mysql://root:@127.0.0.1:3306/edit_word"
 DATABASE_URL="mysql://bukwjwsa:121090cb.K4gur0@node1-ca.n0c.com:3306/bukwjwsa_edit-word"
DATABASE_URL="postgresql://db_user:db_password@127.0.0.1:5432/db_name?serverVersion=13&charset=utf8"
< doctrine/doctrine-bundle 

> symfony/mailer 
 MAILER_DSN=smtp://localhost
< symfony/mailer 

> symfony/google-mailer 
 Gmail SHOULD NOT be used on production, use it in development only.
MAILER_DSN=gmail://kenshin91cb:121090cb.K4gur0@localhost
MAILER_DSN=gmail+smtp://USERNAME:PASSWORD@default
MAILER_DSN=gmail+smtp://kenshin91cb@gmail.com:121090cb.K4gur0@default?verify_peer=false
< symfony/google-mailer 

 MAILHOG 
MAILER_DSN=smtp://localhost:1025
MAILER_URL=smtp://localhost:1025

> symfony/swiftmailer-bundle 
 For Gmail as a transport, use: "gmail://username:password@localhost"
 For a generic SMTP server, use: "smtp://localhost:25?encryption=&auth_mode="
 Delivery is disabled by default via "null://localhost"
MAILER_URL=gmail://kenshin91cb:121090cb.K4gur0@localhost
< symfony/swiftmailer-bundle 

> symfony/mercure-bundle 
 See https://symfony.com/doc/current/mercure.htmlconfiguration
 The URL of the Mercure hub, used by the app to publish updates (can be a local URL)
MERCURE_URL=https://localhost:443/.well-known/mercure
 The public URL of the Mercure hub, used by the browser to connect
MERCURE_PUBLIC_URL=https://localhost:443/.well-known/mercure
 The secret used to sign the JWTs
MERCURE_JWT_SECRET="eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJtZXJjdXJlIjp7InB1Ymxpc2giOlsiKiJdLCJzdWJzY3JpYmUiOlsiKiJdfX0.4DUMumRBcwown63lKUecQcFxGAr8Dg2vwpSuKfAoKcs"
< symfony/mercure-bundle 


