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
 * NSSE Survey block library functions
 *
 * @package    block_nsse_survey
 * @copyright  None
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/**
 * Serve files from the NSSE Survey block file areas.
 *
 * @param stdClass $course The course object
 * @param stdClass $cm The course module object
 * @param stdClass $context The context object
 * @param string $filearea The file area
 * @param array $args Additional arguments
 * @param bool $forcedownload Whether to force download
 * @param array $options Additional options
 * @return void
 */
function block_nsse_survey_pluginfile($course, $cm, $context, $filearea, $args, $forcedownload, array $options = array()) {
    // Check the context is system level.
    if ($context->contextlevel != CONTEXT_SYSTEM) {
        return false;
    }

    // Check capability.
    if (!has_capability('moodle/site:config', $context)) {
        return false;
    }

    // Allow public access to the header image.
    if ($filearea === 'headerimage') {
        $itemid = array_shift($args);
        $filename = array_pop($args);

        if (!$args) {
            $filepath = '/';
        } else {
            $filepath = '/' . implode('/', $args) . '/';
        }

        $fs = get_file_storage();
        $file = $fs->get_file($context->id, 'block_nsse_survey', 'headerimage', $itemid, $filepath, $filename);

        if (!$file) {
            return false;
        }

        \core\session\manager::write_close();
        send_stored_file($file, null, 0, $forcedownload, $options);
    }

    return false;
}

