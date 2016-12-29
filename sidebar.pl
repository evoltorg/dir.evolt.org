#!/usr/bin/perl -w

use strict;

use LWP::UserAgent;

my $ua = new LWP::UserAgent;
$ua->agent("Sidebar " . $ua->agent);

my $req = new HTTP::Request GET => 'http://www.evolt.org/xml/articles.xml';
my $res = $ua->request($req);

if ($res->is_success) {
    my $content = $res->content;

    while ($content =~ m|<a href=\"([^\"]+)\">(.+)</a>|gi) {
	print "      <p><a href='$1' target='_content'>$2</a></p>\n";
    }
}
else {
    print "Could not connect to evolt.org.\n";
}

