helpscout-dashboard
===================

Simple, LIVE dashboard solution for keeping track of helpscout tickets in real-time.
Current features include soothing colors when new tickets come in or are closed. Since API key
is only entered via a form, it is very secure as well.

overview:
===================
1. License
2. How it works
3. Installation

1. license:
===================
It's GPL, so do whatever you want with it - it's chill :).

2. how it works:
===================
Built with angularjs. It pulls the new mailbox data every 3 seconds. If you start to hit the rate limit, just modify the
polling freqeuncy.

3. installation:
===================
Just stick the files into the public folder ("/var/www") of your web server. Don't have a server? Be one of the cool kids and get one for free: http://gweb.io/#/