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
 * @copyright  2026 York University
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/**
 * Serve files from the NSSE Survey block file areas.
 *
 * @param stdClass $course         The course object.
 * @param stdClass $cm             The course module object.
 * @param context  $context        The context object.
 * @param string   $filearea       The file area.
 * @param array    $args           Additional arguments.
 * @param bool     $forcedownload  Whether to force download.
 * @param array    $options        Additional options.
 * @return void
 */
function block_nsse_survey_pluginfile($course, $cm, $context, $filearea, $args, $forcedownload, array $options = []) {
    // The header image is stored in the system context.
    if ($context->contextlevel != CONTEXT_SYSTEM) {
        send_file_not_found();
    }

    // Only the headerimage area is served.
    if ($filearea !== 'headerimage') {
        send_file_not_found();
    }

    // Require the user to be logged in (guests cannot see the block anyway).
    require_login();

    $itemid   = array_shift($args);
    $filename = array_pop($args);
    $filepath = $args ? '/' . implode('/', $args) . '/' : '/';

    $fs   = get_file_storage();
    $file = $fs->get_file($context->id, 'block_nsse_survey', 'headerimage', $itemid, $filepath, $filename);

    if (!$file) {
        send_file_not_found();
    }

    \core\session\manager::write_close();
    send_stored_file($file, null, 0, $forcedownload, $options);
}
