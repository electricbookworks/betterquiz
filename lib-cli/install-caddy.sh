#!/bin/bash
set -e
if [[ ! -x ./caddy ]]; then
	# If you update caddy version, be sure to update EXPECTED_CHECKSUM too.
	CADDYURL="https://github.com/caddyserver/caddy/releases/download/v2.6.2/caddy_2.6.2_linux_amd64.tar.gz";
	EXPECTED_CHECKSUM="ae18c0facae7c8ee872492a1ba63a4c7608915d6d9fe267aef4f869cf65ebd4b7f9ff57f609aff2bd52db98c761d877b574aea2c0c9ddc2ec53d0d5e174cb542";
	php -r "copy('$CADDYURL', 'caddy.tar.gz');"
	ACTUAL_CHECKSUM="$(php -r "echo hash_file('sha512', 'caddy.tar.gz');")"

	if [ "$EXPECTED_CHECKSUM" != "$ACTUAL_CHECKSUM" ]
	then
	    >&2 echo "ERROR: Invalid installer checksum: got $ACTUAL_CHECKSUM expected $EXPECTED_CHECKSUM";
	    rm caddy.tar.gz
	    exit 1
	fi
	tar -xzf caddy.tar.gz
	rm caddy.tar.gz
fi

