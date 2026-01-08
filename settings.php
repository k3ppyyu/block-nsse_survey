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
 * @copyright  None
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die;

if ($hassiteconfig) {
    $package = 'block_nsse_survey';
    $ADMIN->add('blocksettings', new admin_category('block_nsse_survey_settings',
        new lang_string('pluginname', $package)));

    if ($ADMIN->fulltree) {
        // NSSE csv data.
        $name = new lang_string('csvdata', $package);
        $description = new lang_string('csvdata_help', $package);
        $default = get_string('csvdatadefault', $package);
        $settings->add(new admin_setting_configtextarea('block_nsse_survey/csvdata',
                                                        $name,
                                                        $description,
                                                        $default));
        // Default email for upcoming course archiving.
        $name = new lang_string('blockmessage', $package);
        $description = new lang_string('blockmessage_help', $package);
        $default = get_string('blockmessagedefault', $package);
        $settings->add(new admin_setting_confightmleditor('block_nsse_survey/blockmessage',
                                                          $name,
                                                          $description,
                                                          $default));
        
        // User variable to match on.
        $name = new lang_string('matchfield', $package);
        $description = new lang_string('matchfield_help', $package);
        $default = 'idnumber';
        $matchfield = ['idnumber' => get_string("idnumber"),
                       'email' => get_string("email"),
                       'username' => get_string("username"),
                       'id' => 'Moodle ID',
        ];
        $settings->add(new admin_setting_configselect('block_nsse_survey/matchfield',
                                                      $name,
                                                      $description,
                                                      $default,
                                                      $matchfield));

        // Place survey link at top or bottom.
        $name = new lang_string('placement', $package);
        $description = new lang_string('placement_help', $package);
        $default = 'bottom';
        $placement = ['top' => 'Top',
                      'bottom' => 'Bottom',
        ];
        $settings->add(new admin_setting_configselect('block_nsse_survey/placement',
                                                      $name,
                                                      $description,
                                                      $default,
                                                      $placement));
    }
}