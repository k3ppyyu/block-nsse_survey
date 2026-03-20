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
 * @copyright  2026 York University
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

/**
 * NSSE Survey block class.
 *
 * Displays a personalised survey link to the matched student.
 *
 * @package    block_nsse_survey
 * @copyright  2026 York University
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class block_nsse_survey extends block_base {

    /**
     * Initialise the block instance.
     */
    public function init() {
        $this->title = get_string('pluginname', 'block_nsse_survey');
    }

    /**
     * Does this block have any editable configuration?
     *
     * @return bool
     */
    public function has_config() {
        return true;
    }

    /**
     * Specifies the formats this block is available in.
     *
     * @return array
     */
    public function applicable_formats() {
        return [
            'site' => true,
            'my'   => true,
        ];
    }

    /**
     * Returns the content of the block.
     *
     * @return stdClass|null
     */
    public function get_content() {
        global $USER, $OUTPUT;

        // Only for logged-in, non-guest users.
        if (!isloggedin() || isguestuser()) {
            return null;
        }

        if ($this->content !== null) {
            return $this->content;
        }

        // Pull CSV data from plugin config into an array.
        $csvdata = get_config('block_nsse_survey', 'csvdata');
        if (empty($csvdata)) {
            return null;
        }

        $csvfile = fopen('php://temp', 'r+');
        fwrite($csvfile, $csvdata);
        rewind($csvfile);
        $csv = [];
        while (($row = fgetcsv($csvfile)) !== false) {
            $csv[] = $row;
        }
        fclose($csvfile);

        if (empty($csv)) {
            return null;
        }

        // Remove the CSV header row.
        $headers = array_shift($csv);

        if (empty($headers)) {
            return null;
        }

        // Find which column holds the STUDENTID.
        $idkey = array_search('STUDENTID', $headers);
        if ($idkey === false) {
            return null;
        }

        // Find which column holds the SURVEYLINK.
        $linkkey = array_search('SURVEYLINK', $headers);
        if ($linkkey === false) {
            return null;
        }

        // Determine which user field to match on.
        $matchfield = get_config('block_nsse_survey', 'matchfield');
        if (empty($matchfield)) {
            $matchfield = 'idnumber';
        }

        // Find the row whose STUDENTID matches the current user's field value.
        $match = array_search($USER->$matchfield, array_column($csv, $idkey));

        if ($match === false) {
            return null;
        }

        // Retrieve and sanitise the survey link URL.
        $surveylink = clean_param(trim($csv[$match][$linkkey]), PARAM_URL);

        if (empty($surveylink)) {
            return null;
        }

        $this->content          = new stdClass();
        $this->content->text    = '';
        $this->content->footer  = '';

        $blockmessage    = get_config('block_nsse_survey', 'blockmessage');
        $headerimageurl  = $this->get_header_image_url();
        $imageurl        = clean_param(get_config('block_nsse_survey', 'imageurl'), PARAM_URL);
        $placement       = get_config('block_nsse_survey', 'placement');
        if (empty($placement)) {
            $placement = 'bottom';
        }

        // Build context array for Mustache template.
        $context = [
            'headerimage'     => $headerimageurl,
            'imageurl'        => $imageurl,
            'blockmessage'    => $blockmessage,
            'surveylink'      => $surveylink,
            'placementtop'    => ($placement === 'top'),
            'placementbottom' => ($placement === 'bottom'),
        ];

        $this->content->text = $OUTPUT->render_from_template('block_nsse_survey/content', $context);

        return $this->content;
    }

    /**
     * Get the URL of the header image file if one has been uploaded.
     *
     * @return string|null The URL of the header image, or null if not set.
     */
    private function get_header_image_url() {
        $fs    = get_file_storage();
        $files = $fs->get_area_files(
            \core\context\system::instance()->id,
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
        )->out(false);
    }
}
