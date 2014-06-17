Xerox
=====

To create issues in GIT & Bitbucket

Requirement
===========

PHP > 5.3.0
CURL Enabled
Repo on GIT
Repo on Bitbucket

Installation
============

Download the code from the GIT. Use:
$ git clone https://github.com/ankitjaiinn/xerox.git <destination folder>


From the command line, run the following command to create Issue

On GIT
======
$ php index.php create-issue -u <git-username> -p <git-password> http://github.com/:username/:repo "<issue-title>" "<issue-description>"

On BitBucket
======
$ php index.php create-issue -u <bitbucket-username> -p <bitbucket-password> http://bitbucket.org/:owner/:repo "<issue-title>" "<issue-description>"