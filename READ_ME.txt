

Written and tested for Moodle v3.1

This is a simple block that is table driven.

You will need direct admim sql access to the moodle database (meaning your moodle site is probably self-hosted).

You will need to create the table in the moodle database (see MySQL for table creation below).

Then you will need to insert rows into the table for each student that contains their unique
NSSE link and their moodle login username.

Install block as you would for any moodle third-party block.

Put block on moodle front page.

When the student logs in to moodle, if they have a row in the table (based on their username), 
their link to the survey will be displayed in the block.


---

MySQL statement for creating block table:


CREATE TABLE `NSSE_ASSESSMENT` (
 `id` bigint(20) NOT NULL AUTO_INCREMENT,
 `username` varchar(25) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
 `nsse_loginid` varchar(100) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
 PRIMARY KEY (`id`),
 KEY `nsse_ass_usern_ix` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci




sample insert (in this example, adan_n is the username of the student):

insert into NSSE_ASSESSMENT (nsse_loginid,username) values('https://nssesurvey.org/H1587AD9B3/60','adan_n');




