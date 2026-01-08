# NSSE Survey Block

A Moodle block plugin that displays personalized NSSE (National Survey of Student Engagement) survey links to students based on their login credentials.

## Overview

The NSSE Survey Block is a simple, table-driven Moodle plugin designed for institutions that administer the National Survey of Student Engagement to their students. It allows administrators to:

- Display customized survey links to individual students
- Automatically show survey access links only to students who have been registered in the system
- Customize the message and placement of the survey link within the block

## Requirements

- Moodle 3.1 or higher (plugin version tested on Moodle 3.1 and 4.1)
- Direct admin SQL access to the Moodle database
- Self-hosted Moodle installation (not compatible with hosted solutions without database access)

## Installation

1. Download or clone the plugin into your Moodle installation at `/blocks/nsse_survey/`
2. Navigate to **Site Administration > Notifications** in your Moodle site
3. Follow the prompts to install the block plugin
4. Create the required database table (see Database Setup section below)
5. Add the block to your site front page via **Dashboard > Manage blocks**

## Configuration

After installation, configure the block at **Site Administration > Plugins > Blocks > NSSE Survey**:

1. **CSV Data**: Upload or paste the student data in CSV format with columns: `STUDENTID` and `SURVEYLINK`
   - Default format includes a header row
   - The plugin will match student IDs against the configured match field

2. **Block Message**: Customize the message displayed alongside the survey link
   - Supports HTML formatting
   - Can be displayed above or below the survey link

3. **Match Field**: Select which user field to match against the CSV `STUDENTID` column
   - Common options: `username`, `idnumber`, `email`

4. **Link Placement**: Choose whether the survey link appears above or below the block message
   - Options: Top or Bottom

## Features

- **CSV-based data**: Easy to update student survey links via CSV upload
- **Flexible matching**: Support for different user field matching (username, email, ID number, etc.)
- **Customizable messaging**: Add instructions or context about the survey
- **Student-only display**: Links only appear when logged-in students view the block
- **Responsive design**: Works on desktop and mobile devices

## Usage

Once configured and deployed:

1. Students log into Moodle
2. If their username/ID exists in the NSSE_ASSESSMENT table, the survey link is displayed in the block
3. Students click the link to access their personalized NSSE survey
4. The link opens in a new browser window

## Supported Formats

The block is available on:
- Site homepage (Dashboard)
- My Courses page

The block does not appear in individual course pages.

## License

This plugin is licensed under the GNU General Public License v3.0. See the LICENSE file for full details.

## Copyright

This plugin was developed for early alerts and assessment purposes. Copyright information can be found in the source code headers.

## Support

For issues or questions about this plugin, contact your Moodle administrator or institution's assessment team.

