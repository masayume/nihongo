#!/bin/bash

HOST='ftp.masayume.it'
USER='222078@aruba.it'
PASSWD='ee48748d86'

ftp -n -v -p $HOST << EOT
ascii
prompt noprompt
user $USER $PASSWD
cd www.masayume.it/nihongo/isotope-nihon 
mput *
bye
EOT
