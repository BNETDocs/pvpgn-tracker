# pvpgn-tracker
This project transforms PvPGN tracker solicitations over UDP datagrams into
usable data for users to browse using a PHP web user interface.

## Usage
There are two parts to PvPGN tracking, they are split into the daemon and the
web user interface.

The daemon binds to 6114/udp on a server and listens for UDP datagrams from
PvPGN servers. It reads and writes a state file that can be accessed by PHP. The
state file is the source of truth, the daemon will keep it pruned as necessary.

The PHP web user interface requires Nginx and PHP-FPM software, and the
environment needs to be able to access the daemon's state file for reading. All
requests should be sent to the [main.php](/web/src/main.php) file.

For information on the format of the UDP datagram, read the [PvPGN Tracking
Protocol](https://bnetdocs.org/document/35/pvpgn-tracking-protocol) document.

## License &amp; Warranty
See our [License](/LICENSE.txt) file included with this repository.

tl;dr: You're responsible for yourself and everything you do or that happens.
