=== Swim Team ===
Contributors: mpwalsh8
Donate link: http://www.wp-swimteam.org/
Tags: swimteam, swim, team
Requires at least: 3.1
Tested up to: 3.3.1
Stable tag: trunk

Swim Team (aka wp-SwimTeam) is a comprehensive WordPress plugin to run a swim
team including registration, volunteer assignments, scheduling, and much more.

== Description ==

The wp-SwimTeam plugin builds a swim team management system on top of WordPress.  The use model
is targeted at Summer Swim League teams but is not limited to that use model.  wp-SwimTeam
features include:

* Electronic Registration for Parents and Swimmers
* Each Swimmer can have two Parents or Guardians
* Configurable custom fields for the Swim Team parent or guardian profile
* Configurable custom fields for the Swimmer profile
* Seamless integration with WordPress registration and login
* Define and Manage Seasons
* Define and Manage Age Groups
* Define and Manage Opponent Profiles
* Define and Manage Meet Schedule
* Report Generator
* CSV Export for Reports and Roster
* SDIF (SD3) Export for Roster
* Short Code for Google Maps
* Short Code for Opponent Profiles
* Short Code for Meet Schedule
* Short Code for Flickr Photo Gallery
* Volunteer Signup and Management
* Opt-In/Opt-Out System for Meet Participation
* Volunteer registration and reporting

More information can be found on the [wp-SwimTeam](http://www.wp-swimteam.org) blog.  A fully
functioning [wp-SwimTeam Demo](http://demo.wp-swimteam.org) is also a good source for example
features and usage, particularly for the short codes.

== Installation ==

1. Ensure the dependent [phpHtmlLib](http://wordpress.org/extend/plugins/phphtmllib/) plugin is installed and active.
1. Unzip and Upload the wp-SwimTeam content to your /wp-content/plugins/ directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Refer to the official plugin page for documentation, usage and tips


== Frequently Asked Questions ==

Refer to the official [wp-SwimTeam](http://www.wp-swimteam.org) web site for questions and support.

== Screenshots ==

1. The overview tab which all users see.
2. List of Swim Team volunteer jobs which users can sign up for.
3. List of Swim Meets from the Management tab.
4. Job Assignment Report Generator
5. Swimmer Profile Options

== Changelog ==

The [wp-SwimTeam blog](http://www.wp-swimteam.org) provides full details on changes, bugs, enhancesments,
future developments and much more and is the definitive source for the Change Log.

= 1.18 =
* Fixed bug which prevented users from signing up for jobs.
* Phase 1 of overhauled Event Model is complete.  The new Event Model introduces the concept of Event Groups.  Events are now defined in the context of an Event Group.  Swim meets currently do not have any connection to Events but that will chance in a release fairly shortly in Phase 2.
* Added ability to import events from a Hy-tek Events File (.hyv) into an Event Group.
* Added ability to delete all events from an Event Group.
* Changed Google Maps API Key from required to optional.  If the API key hasn't been entered, wp-SwimTeam will now gracefully work without it.

= 1.17 =
* Fixed bug with unsupported action on Swimmer Tab introduced with changes to action bar in 1.16.

= 1.16 =
* Fixed another bug which caused display of "M/A" instead of first and/or last name in some instances.  Propogated this fix to a lower level class and eliminated duplicate code across numerous classes.
* Fixed inconsistencies across UI where some tabs had actions as drop downs and others had buttons.  All tabs now use the drop down UI model.

= 1.15 =
* Fixed bug which caused display of "M/A" instead of first and/or last name in some instances.

= 1.14 =
* Fixed odd use case where get_user_meta() sometimes returns an array with an empty string and other times returns an empty array.  This bug manifested itself with a PHP warning from the phpHtmlLib plugin which wp-SwimTeam references.

= 1.13 =

* Fixed bug which prevented display of first and last name is user list within the Swim Team and Manage menus.
* Fixed bug which caused default settings for Postal Code and State or Province not to be used leaving the form inoperable.
* Cleaned up Options->Swim Team form to remove debug borders (oops) and fix alignment.

= 1.12 =
* Fixed bug which was sending Opt-In/Opt-Out confirmation e-mails to Registration e-mail address instead of Opt-In/Opt-Out e-mail address.  This time it is fixed correctly!

= 1.11 =
* Added ability to display which user entered an opt-in/opt-out record on the Swim Meet report.
* Added ability to control inclusion of time stamp for opt-in/opt-out records on the Swim Meet report.
* Fixed bug which was sending Opt-In/Opt-Out confirmation e-mails to Registration e-mail address instead of Opt-In/Opt-Out e-mail address.

= 1.10 =
* Fixed bug which prevented opt-in/opt-out list from being displayed properly by the wpst_meet_report short code.

= 1.9 =
* Added ablity to send out Job Reminder e-mails on a per meet basis from the Manage->Swim Meets tab.

= 1.8 =
* Fixed bug with Users List which prevented using the GUI controls.
* Added new Event model for Opt-In/Opt-Out.  Can now opt in or out of specific events instead of strokes.
* Exposed Job Assignments Report to Users.  Users can now generate a report of their own Assignments.
* Added "Job Commitments" Report which reports Job Commitments versus Parents/Guardians of active swimmers.

= 1.7 =
* Added "My Jobs" tab to allow users to quickly see the jobs they have committed to.
* Added notification when a user has not met the minimum job commitment requirement.
* Fixed a plethora of small bugs and made numerous code improvements, particularly in the Reports module.
* Added new field to swim meet definition to allow participation to be open or closed.

= 1.6 =
* Fixed bug when optional user and/or swimmer field count is zero preventing reports from running.
* Added My Jobs tab for all users.  User can now easily see which jobs they've signed up for.
* Added e-mail field to user profile.  Users are familiar with the swim team profile, easy to update.
* Exposed Swim Meet report to all users allowing users to see their swimmer opt-in/out and jobs easily.
* Added Dashboard widget.
* Fixed quite a few minor bugs - too many to list!

= 1.5 =
* Fixed bug with duplicate e-mails for Registration, Opt-In/Opt-Out, and Job Sign Up.
* Fixed confirmation messages after saving Options to utlize WordPress message standard.

= 1.4 =
* Fixed bug in Opt-In/Opt-Out e-mail confirmation which duplicated recipients.
* Fixed bug in handling Country when set to US Only.
* Added Club Profile initialization based on State or Province in Team Profile.
* Added E-mail confirmation for Job Assignments.
* Added Job Options tab on Options page to configuring Jobs module.

= 1.3 =
* Fixed bug in wpst_meet_report short code.
* Fixed bug in Add/Update Club Profile due to invalid URL field(s).
* Added showmaplinks option to wpst_meet_report short code.
* Added URL sanitatization to Google Maps and Mapquest URL fields on club profile.
* Added notes to use Google URL shortner for URLs that don't validate.

= 1.2 =
* First release unde the WordPress plugin respository.

== Upgrade Notice ==

= 1.2 =
Addresses a serious issue when user or swimmer optional field counts are set to zero.
