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
        if (empty($csvdata)) {
            return null;
        }

        $csvfile = fopen("php://temp", 'r+');
        fputs($csvfile, $csvdata);
        rewind($csvfile);
        $csv = [];
        while (($row = fgetcsv($csvfile, 0, ",", '"', "\\")) !== FALSE) {
            $csv[] = $row;
        }
        fclose($csvfile);

        // Remove the csv header row.
        $headers = array_shift($csv);

        if (empty($headers)) {
            return null;
        }

        // Find which column the STUDENTID column is.
        $idkey = array_search('STUDENTID', $headers);
        if ($idkey === false) {
            return null;
        }

        // Find which column the SURVEYLINK column is — check early so we
        // don't do expensive work if the column is missing.
        $linkkey = array_search('SURVEYLINK', $headers);
        if ($linkkey === false) {
            return null;
        }

        // Find what userfield will be used to match STUDENTID.
        $matchfield = get_config('block_nsse_survey', 'matchfield');

        // Retrieve the user's match value — hide block if empty or field doesn't exist.
        $uservalue = property_exists($USER, $matchfield) ? trim((string)$USER->$matchfield) : '';
        if (empty($uservalue)) {
            return null;
        }

        // Find the row whose STUDENTID matches the current user's field value.
        // Trim all STUDENTID values from the CSV to guard against whitespace in exports.
        // The strict=true flag prevents loose matches (e.g. "0" == false, "" == null).
        $studentids = array_map('trim', array_column($csv, $idkey));
        $match = array_search($uservalue, $studentids, true);

        if ($match === false) {
            return null; // No matching student ID — hide the block.
        }

        // Get the surveylink for the matched user.
        $surveylink = clean_param(trim($csv[$match][$linkkey]), PARAM_URL);
        if (empty($surveylink)) {
            return null;
        }

        $this->content = new stdClass();
        $this->content->items = [];
        $this->content->icons = [];
        $this->content->footer = '';

        $blockmessage = get_config('block_nsse_survey', 'blockmessage');
        $linktag = '<div class="text-center">
                        <a href="' . format_string($surveylink) . '" target="_blank" class="btn btn-primary btn-lg">
                            <i class="fa fa-check"></i> <strong>Click here to access your survey</strong>
                        </a>
                    </div>';

        // Get header image URL from stored file setting.
        $headerimageurl = $this->get_header_image_url();

        // Get image link URL from settings.
        $imageurl = get_config('block_nsse_survey', 'imageurl');

        // Prepare context for Mustache template.
        $context = [
            'headerimage' => $headerimageurl,
            'imageurl' => $imageurl,
            'blockmessage' => $blockmessage,
            'surveylink' => $linktag,
        ];

        // Render the template.
        $this->content->items[] = $OUTPUT->render_from_template('block_nsse_survey/content', $context);

        return $this->content;
    }

    /**
     * Get the URL of the header image file if it exists.
     *
     * @return string|null The URL of the header image, or null if not set
     */
    private function get_header_image_url() {
        global $OUTPUT;

        $fs = get_file_storage();
        $files = $fs->get_area_files(
            context_system::instance()->id,
            'block_nsse_survey',
            'headerimage',
            0,
            'itemid',
            false
        );

        if (empty($files)) {
            return null;
        }

        $file = reset($files);
        return moodle_url::make_pluginfile_url(
            $file->get_contextid(),
            $file->get_component(),
            $file->get_filearea(),
            $file->get_itemid(),
            $file->get_filepath(),
            $file->get_filename(),
            false
        )->out();
    }
}
