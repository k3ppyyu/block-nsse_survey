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
 * NSSE Survey block
 *
 * @package    block_nsse_survey
 * @copyright  None
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

class block_nsse_survey extends block_list {

    /**
     * Initialise the block instance.
     * Sets up the common part of the block object.
     */
    public function init() {
        $this->title = get_string('pluginname', 'block_nsse_survey') ;
    }
    
    /**
     * Does this block have any editable configuration?
     *
     * @return bool True if the block has any editable configuration, false otherwise
     */
    public function has_config() {
        return true;
    }

	/**
	 * Specifies the block is only available on the site page, not in any course pages.
	 * 
	 * @return array An array where the key is the block format, and the value is a boolean
	 * indicating whether the block is available in that format.
	 */
	public function applicable_formats() {
	    return [
	        'site' => true, // Only available on the site page.
            'my' => true,
        ];
	}

    /**
     * Returns the content of the block.
     *
     * @return stdClass|bool The content object if there is content, false otherwise
     */
    public function get_content() {
        global $CFG, $USER, $DB, $OUTPUT;

        // shortcut -  only for logged in users!
        if (!isloggedin() || isguestuser()) {
            return false;
        }

        if ($this->content !== NULL) {
            return $this->content;
        }

        // Pull .csv file into array.
        $csvdata = get_config('block_nsse_survey', 'csvdata');
        $csvfile = fopen("php://temp", 'r+');
        fputs($csvfile, $csvdata);
        rewind($csvfile);
        $csv = [];
        while (($row = fgetcsv($csvfile, 0, ",")) !== FALSE) {
            $csv[] = $row;
        }
        fclose($csvfile);

        // Remove the csv header row.
        $headers = array_shift($csv);

        // Find which column the STUDENTID column is.
        $idkey = array_search('STUDENTID', $headers);

        // Find what userfield will be used to match STUDENTID.
        $matchfield = get_config('block_nsse_survey', 'matchfield');

        // Find row where STUDENTID matches.
        $match = array_search($USER->$matchfield, array_column($csv, $idkey));

        if ($match === false) {
            return false; // No match found.
        }

        // Find which column the SURVEYLINK column is.
        $linkkey = array_search('SURVEYLINK', $headers);

        // Get the surveylink for the matched user.
        $surveylink = $csv[$match][$linkkey];

        $this->content = new stdClass();
        $this->content->items = [];
        $this->content->icons = [];
        $this->content->footer = '';

        $blockmessage = get_config('block_nsse_survey', 'blockmessage');
        $linktag = '<center>
                        <a href="' . format_string($surveylink) . '" target="_blank">
                            <i class="fa fa-check"></i> <strong>Click here to access your survey</strong>
                        </a>
                    </center>';

        // Add survey link either on the top or bottom of the block.
        $text = get_config('block_nsse_survey', 'placement') == 'top' ? $linktag . $blockmessage : $blockmessage . $linktag;
        $this->content->items[] = $text;

        return $this->content;
    }
}
