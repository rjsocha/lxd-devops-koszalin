[![Travis Build Status](https://travis-ci.org/warmcat/libwebsockets.svg)](https://travis-ci.org/warmcat/libwebsockets)
[![Appveyor Build status](https://ci.appveyor.com/api/projects/status/qfasji8mnfnd2r8t?svg=true)](https://ci.appveyor.com/project/lws-team/libwebsockets)
[![Coverity Scan Build Status](https://scan.coverity.com/projects/3576/badge.svg)](https://scan.coverity.com/projects/3576)

![lws-overview](./doc-assets/lws-overview.png)

News
----

## Lws has the first official ws-over-h2 server support

![wss-over-h2](https://libwebsockets.org/sc-wss-over-h2.png)

There's a new standard on the RFC track that enables multiplexing ws connections
over an http/2 link.  Compared to making individual tcp and tls connections for
each ws link back to the same server, this makes your site start up radically
faster, and since all the connections are in one tls tunnel, with considerable memory
reduction serverside.

To enable it on master you just need -DLWS_WITH_HTTP2=1 at cmake.  No changes to
existing code are necessary for either http/2 (if you use the official header creation
apis if you return your own headers, as shown in the test apps for several versions)
or to take advantage of ws-over-h2.  When built with http/2 support, it automatically
falls back to http/1 and traditional ws upgrade if that's all the client can handle.

Currently only Chrome Canary v67 supports this ws-over-h2 encapsulation (chrome
must be started with `--enable-websocket-over-http2` switch to enable it currently)
but the other browsers will catch up soon.

## New "minimal examples"

https://github.com/warmcat/libwebsockets/tree/master/minimal-examples

These are like the test apps, but focus on doing one thing, the best way, with the minimum amount of code.  For example the minimal-http-server serves the cwd on http/1 or http/2 in 50 LOC.  Same thing with tls is just three more lines.

They build standalone, so it's easier to copy them directly to start your own project; they
are CC0 licensed (public domain) to facilitate that.

## Windows binary builds

32- and 64-bit Windows binary builds are available via Appveyor.  Visit [lws on Appveyor](https://ci.appveyor.com/project/lws-team/libwebsockets),
click on a build, the ARTIFACTS, and unzip the zip file at `C:\Program Files (x86)/libwebsockets`.

## Latest Stable

 - v2.4.2 is out... HTTP/2 server support and mbedTLS as a TLS backend.

see the changelog https://github.com/warmcat/libwebsockets/blob/v2.4-stable/changelog

Please note the additional READMEs have moved to ./READMEs/

## ESP32 is supported

ESP32 is now supported in lws!  Download the

 - factory https://github.com/warmcat/lws-esp32-factory and
 - test server app https://github.com/warmcat/lws-esp32-test-server-demos

The ESP32 stuff has my dynamic mbedtls buffer allocation patches applied,
which reduce allocation for small payload TLS links by around 26KiB per connection.

## Support

This is the libwebsockets C library for lightweight websocket clients and
servers.  For support, visit

 https://libwebsockets.org
 https://github.com/warmcat/libwebsockets

and consider joining the project mailing list at

 https://libwebsockets.org/mailman/listinfo/libwebsockets

You can get the latest version of the library from git:

- https://github.com/warmcat/libwebsockets
- https://libwebsockets.org/git

Doxygen API docs for master: https://libwebsockets.org/lws-api-doc-master/html/index.html


After libwebsockets 1.3, tags will be signed using a key corresponding to this public key

```
-----BEGIN PGP PUBLIC KEY BLOCK-----
Version: GnuPG v1

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
+AWJGq/WfB7N6pPh8S/XMW4e6ptuUodjiA==
=HV8t
-----END PGP PUBLIC KEY BLOCK-----
```
