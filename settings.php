<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * NSSE Survey block settings
 *
 * @package    block_nsse_survey
 * @copyright  Rose-Hulman Institute of Technology
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

if ($hassiteconfig) {
    $package = 'block_nsse_survey';

    $ADMIN->add('blocksettings', new admin_category(
        'block_nsse_survey_settings',
        new lang_string('pluginname', $package)
    ));

    if ($ADMIN->fulltree) {

        // CSV data.
        $settings->add(new admin_setting_configtextarea(
            'block_nsse_survey/csvdata',
            new lang_string('csvdata', $package),
            new lang_string('csvdata_help', $package),
            get_string('csvdatadefault', $package)
        ));

        // Header image upload.
        $settings->add(new admin_setting_configstoredfile(
            'block_nsse_survey/headerimage',
            new lang_string('headerimage', $package),
            new lang_string('headerimage_help', $package),
            'headerimage',
            0,
            ['accepted_types' => 'image']
        ));

        // Header image URL link.
        $settings->add(new admin_setting_configtext(
            'block_nsse_survey/imageurl',
            new lang_string('imageurl', $package),
            new lang_string('imageurl_help', $package),
            ''
        ));

        // Block message shown above the survey link.
        $settings->add(new admin_setting_confightmleditor(
            'block_nsse_survey/blockmessage',
            new lang_string('blockmessage', $package),
            new lang_string('blockmessage_help', $package),
            get_string('blockmessagedefault', $package)
        ));

        // User field to match on.
        $matchoptions = [
            'idnumber' => get_string('idnumber'),
            'email'    => get_string('email'),
            'username' => get_string('username'),
            'id'       => get_string('moodleid', 'block_nsse_survey'),
        ];
        $settings->add(new admin_setting_configselect(
            'block_nsse_survey/matchfield',
            new lang_string('matchfield', $package),
            new lang_string('matchfield_help', $package),
            'idnumber',
            $matchoptions
        ));

        // Survey link placement (top or bottom of message).
        $placementoptions = [
            'top'    => get_string('top', 'block_nsse_survey'),
            'bottom' => get_string('bottom', 'block_nsse_survey'),
        ];
        $settings->add(new admin_setting_configselect(
            'block_nsse_survey/placement',
            new lang_string('placement', $package),
            new lang_string('placement_help', $package),
            'bottom',
            $placementoptions
        ));
    }
}