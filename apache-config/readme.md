# SSL 

openssl x509 -enddate -noout -in /etc/ssl/certs/ssl-cert-snakeoil.pem

openssl x509 -in /etc/ssl/certs/ssl-cert-snakeoil.pem -text -noout

## Location

/etc/apache2/sites-available/

## edit 

sudo vim agent.conf
sudo vim backend.conf
sudo vim crmapi.plugn.io.conf
sudo vim dashboard.conf
sudo vim partners.conf
sudo vim remail.conf
sudo vim shortner.conf
sudo vim z-api.conf

## validate apache config 

apachectl configtest

## apply changes 

sudo a2dissite agent.conf
sudo a2dissite backend.conf
#sudo a2dissite crmapi.plugn.io.conf
sudo a2dissite crmapi.conf
sudo a2dissite dashboard.conf
sudo a2dissite partners.conf
sudo a2dissite remail.conf
sudo a2dissite shortner.conf
sudo a2dissite z-api.conf

sudo a2ensite agent.conf
sudo a2ensite backend.conf
sudo a2ensite crmapi.conf
#sudo a2ensite crmapi.plugn.io.conf
sudo a2ensite dashboard.conf
sudo a2ensite partners.conf
sudo a2ensite remail.conf
sudo a2ensite shortner.conf
sudo a2ensite z-api.conf

sudo systemctl restart apache2

# to generate certificates 

sudo apt update
sudo apt install certbot python-certbot-apache

sudo certbot --apache -d agent.plugn.io -d admin.plugn.io -d crmapi.plugn.io -d partners.plugn.io -d i.plugn.io -d api.plugn.io
#-d dashboard.plugn.io -d remail.plugn.io

## dev 

sudo certbot --apache -d agent.dev.plugn.io -d admin.dev.plugn.io -d crmapi.dev.plugn.io  -d partners.dev.plugn.io  -d i.dev.plugn.io -d api.dev.plugn.io
#-d dashboard.dev.plugn.io  -d remail.dev.plugn.io

## cloudfront distribution we using for dev 

d2jqul3gxcx7oy.cloudfront.net.

load balancer for prod
Plugn-prod-web-1335029513.eu-west-2.elb.amazonaws.com
