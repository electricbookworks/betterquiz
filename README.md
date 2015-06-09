BetterQuiz Code README
======================

# Summary

This document provides a quick guide to the BetterQuiz codebase.

# Installation

Installing a BetterQuiz server is relatively simple.

1. Assuming the betterquiz repo is checked out to `/opt/betterquiz`, you can install a basic Apache server with required libraries using the `src/tools/install.sh` script. You can also do this all yourself- the script is very self-evident.

2. Create an empty database and configure a user with password and all privileges.

3. Copy `public/lib/settings.template.php` to `public/lib/settings.local.php` and edit this file to contain your database details and your Panacea SMS API account details.

4. Migrate the database. This is done by executing every script of the form `src/sql/betterquizN.sql` against the database in ascending numerical order 0, 1, 2 ...

You're ready to go!

# Usage

From the Admin system, upload your quiz. Then find the ID for the quiz: you can use Quiz->Find and locate the quiz you've uploaded.

Direct the user to:

http://your.betterquiz.com/?qz={ID}&r={REDIRECT}

Set the `qz` parameter to the ID of your quiz, and the `r` parameter to the URL to which you want the user returned when the quiz is completed.

# Code Layout

There are two important *root* directories in the codebase: `public` and `src`. The `public` directory contains the base directory to be served as the BetterQuiz website. The `src` directory contains code that is compiled or transformed in some fashion, and typically inserted into the `public` directory in compiled form.

## public/admin

The code for the BetterQuiz admin site. The `public/admin/lib` contains some general library files used on the admin site.

## public/components

Generated webcomponents, from the code in `src/bq-components`.

## public/css

CSS code generated from SASS in src/scss

## public/js

Javascript code generated from Coffeescript in `src/coffee`.

## public/lib

General libraries for inclusion on betterquiz pages. The `settings.local.php` in this directory in particular contains site-specific settings for the database and for PanaceaSMS API usage.

### public/lib/betterquiz

This directory contains most of the BetterQuiz specific code. It can be divided into three broad groups:

1. BetterQuiz Models and database access type classes. These being with BQ.
2. The BQF tokenizer, parser and related classes. These are the Parser*, StringStream*, Token* and State* classes, and some related miscellaneous files such as `func.bqf_unescape_string.php` that provide some utility to the tokenization and parsing processes.
3. Some general web site type classes. In particular `class.Errors.php`, `class.Flash.php`, `class.ReturnSite.php` and `class.SessionStore.php`

## src/bq-components

The source code for the web components that are used on the admin site. These are all experimental, and will probably give trouble as the webcomponents standard evolves (!) - but fine for now.

## src/coffee

Coffeescript sources. These are compiled through the gulpfile contained in /, and become compiled javascript in `src/js`.

## src/scss

SASS stylesheets that are compiled by running `compass watch` in the root directory. They become compiled CSS in `public/css`

## src/sql

SQL migrations scripts. These are each of the form betterquizN.sql and betterquiz-N.sql . Execute the positive forms (betterquizN.sql) in numerical sequence from 0 to migrate to a database version, and the negative forms (betterquiz-N.sql) in reverse numerical sequence to rollback a migration.

## src/tools

The `src/tools` directory contains a few basic tools for betterquiz operation.

### src/tools/install.sh

A very basic bash script to install betterquiz (which is assumed to be in /opt/betterquiz) on a fresh Ubuntu server. It does not migrate the database, nor setup database connection options. See Installation above for detailed installation instructions.

### src/tools/md2bqf.pl

A Perl script for converting BetterCare Quizzes in MarkDown format to BQF format. This is a command line tool, and is designed to be used with Unix pipes. It reads the MarkDown from stdin and writes it to stdout.

# BetterQuizFormat (BQF) Parsing

The Tokenization and Parsing of the BQF is managed using ideas expressed in this talk by Rob Pike: https://www.youtube.com/watch?v=HxaD_trXwRE

The mile high view is that a StringStream derived class provides methods for reading through the input string at a character level.

One of the State derived classes controls the reading of the StringStream, breaking the stream into Tokens and emitting Tokens as appropriate. A state-machine controls the state of the tokenization. The Parser related classes receive the Tokens, and populate a BQQuiz based on the received Tokens. Again, a state-machine controls the parsing state.

# Build Tools

The following build tools are used at various points in the betterquiz build process. Note, though, that all compiled assets are stored in the repo, so none of these build tools are required on production boxes.

* Coffeescript
	Compiler to convert coffeescript to Javascript.
* Compass
	Used to compile SASS with support for Foundation from ZURB.
* Gulp
	Watching for coffeescript changes.


