#!/usr/bin/perl
###############################################################################
## Basic Analysis and Security Engine (BASE)
## Copyright (C) 2004 BASE Project Team
## Copyright (C) 2000 Carnegie Mellon University
##
## (see the file 'base_main.php' for license details)
##
## Project Leads: Kevin Johnson <kjohnson@secureideas.net>
##                Sean Muller <samwise_diver@users.sourceforge.net>
## Built upon work by Roman Danyliw <rdd@cert.org>, <roman@danyliw.com>
##
## Purpose: stand-alone status and event/dns/whois cache maintenance 
################################################################################
## Authors:
################################################################################
## Kevin Johnson <kjohnson@secureideas.net
################################################################################
## Usage: ./base_maintenance.pl <command>
## Valid Commands are:
##      ualert -- Update Alert Cache
##      ralert -- Rebuild Alert Cache
##      uip -- Update IP Cache
##      rip -- Rebuild IP Cache
##      uwhois -- Update Whois Cache
##      rwhois -- Rebuild Whois Cache
##      rtables -- Repair DB Tables
################################################################################

# Change below URL to match the one for your install
my($url, $user, $pwd);
$url = "http://127.0.0.1/~kjohnson/base/base_maintenance.php";

# Set $user to the username used to log in.
# Leave blank if you do not use Authentication in BASE
$user = "";

# Set $pwd to the password for the user above.
# Leave blank if you do not use Authentication in BASE
$pwd = "";

################################################################################
## You should not need to change anything below these lines!
################################################################################

if (scalar(@ARGV) != "1" ) {
    print("***".scalar(@ARGV));
    die("\nNo arguments passed to this command!\nUsage: ./base_maintenance.pl <command>\n\n");
}

my $command;
my $postvar;
my $resp;
$command = $ARGV[0];

if ($command eq "ualert") {
        $postvar = "Update Alert Cache";
    } elsif ($command eq "ralert") {
        $postvar = "Rebuild Alert Cache";
    } elsif ($command eq "uip") {
        $postvar = "Update IP Cache";
    } elsif ($command eq "rip") {
        $postvar = "Rebuild IP Cache";
    } elsif ($command eq "uwhois") {
        $postvar = "Update Whois Cache";
    } elsif ($command eq "rwhois") {
        $postvar = "Rebuild Whois Cache";
    } elsif ($command eq "rtables") {
        $postvar = "Repair Tables";
    } else {
        die("\nInvalid command!\nValid Commands are: ualert, ralert, uip, rip, uwhois, rwhois, rtables.\n\n");
    }

use LWP::UserAgent;
$ua = LWP::UserAgent->new;
$ua->agent("base_maintenance.pl/1.0 ");
$resp = $ua->post($url,
                  { "submit" => "$postvar",
                    "standalone" => "yes",
                    "user" => "$user",
                    "pwd" => "$pwd" });

if ($resp->code != 200 )
{
    my $code;
    $code = $resp->code;
    print "An Error occurred while posting the form!\n The error was a $code\n\n";
}