#!/usr/bin/perl
# This simple utility converts from the .md format that EBW has been using to the 
# .bqf format defined for betterquiz.

use constant BEFORE_META => 0;
use constant META => 1;
use constant QUESTIONS => 2;

my $state = BEFORE_META;
my $line = 0;

my $currentQuestion = 0;

while (<>) {
	$line++;

	my $leadingWhitespace = /^\s+/;

	#print "# line=$line, state=$state, $_";

	# copy all comments directly
	if (/^#/) {
		print;
		next;
	}
	# Skip any empty lines
	if (/^\s*$/) {
		next;
	}

	if (BEFORE_META==$state) {
		if ("---"==$_) {
			$state = META;
		} else {
			print "ERROR on line " . $line . ": unexpected content.";
			exit;
		}		
	} elsif (META==$state) {
		if (/^---$/) {
			$state = QUESTIONS;
		} else {
			print;
		}
	} else {	# QUESIONS == state
		if ($leadingWhitespace) {	# This is an ANSWER
			if (!$currentQuestion) {
				print "ERROR on line $line: unexpected answer before question defined.\n";
				exit;
			}
			my $correct = /\s*`correct`\{:\.correct-answer\}/;
			if ($correct) {
				s/\s*`correct`\{:\.correct-answer\}//;
			}
			print ($correct ? "+ " : "- ");

			# Strip numbering
			s/^\s*\d+\.\s*//;
			print;
		} else {	# This is a QUESTION
			print "\n";
			$currentQuestion = 1;
			# Strip numbering
			s/^\s*\d+\.\s*//;
			print;
		}

	}
}
