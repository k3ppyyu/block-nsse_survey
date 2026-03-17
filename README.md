# NSSE Survey Block

A Moodle block plugin that displays the National Survey of Student Engagement (NSSE) survey link to eligible students with customizable messaging and branding.

## Overview

The NSSE Survey block allows administrators to:
- Upload a custom header image with optional clickable link
- Display a customizable HTML-formatted message
- Automatically deliver personalized survey links to students based on CSV data
- Match students using configurable user fields (ID number, email, username, or Moodle ID)
- Position the survey link at the top or bottom of the block content

The block uses a responsive Bootstrap 5 design that works seamlessly with Moodle 5.1+.

## Features

### CSV Data Management
- Configure student data via CSV format with STUDENTID and SURVEYLINK columns
- Supports any user field for matching (ID number, email, username, Moodle ID)
- Each student receives their unique survey link based on their user data

### Header Image
- Upload a custom header image to brand the block
- Optional clickable image that directs users to a specified URL
- Responsive image display using Bootstrap 5's `img-fluid` class
- Images are securely served through Moodle's file system

### Customizable Content
- Edit block message using Moodle's HTML editor
- Support for rich text formatting, links, and styling
- Comes with a default message template (customizable for your institution)

### Flexible Layout
- Choose survey link placement: top or bottom of block content
- Clean, responsive design using Bootstrap 4 CSS framework
- Survey link displays as a prominent call-to-action button

## Installation

1. Download or clone the plugin into your Moodle blocks directory:
   ```
   /blocks/nsse_survey/
   ```

2. Log in to your Moodle site as an administrator

3. Navigate to **Site Administration > Notifications** to trigger the plugin installation

4. Configure the block settings at **Site Administration > Plugins > Blocks > NSSE Survey**

## Configuration

Navigate to **Site Administration > Plugins > Blocks > NSSE Survey** to configure:

### CSV Data
- Paste CSV formatted data with columns: `STUDENTID, SURVEYLINK`
- Each row represents one student and their unique survey URL
- Example:
  ```
  STUDENTID,SURVEYLINK
  1234567890,https://examplesurveylink.com?id=1234567890
  0987654321,https://examplesurveylink.com?id=0987654321
  ```

### Header Image
- Upload an image file (PNG, JPG, GIF, etc.) to display at the top of the block
- The image will scale responsively on all devices
- Leave blank to display block without a header image

### Image URL Link (Optional)
- Enter a URL that users will navigate to when clicking the header image
- Opens in a new browser tab
- Leave blank if you don't want the image to be clickable

### Block Message
- Customize the HTML message displayed in the block
- Use Moodle's rich text editor to format content
- Default message includes institution name and survey information
- Supports links, formatted text, and HTML elements

### Match User Field
- Select which user field will be matched against the STUDENTID in your CSV data
- Options:
  - **ID Number** (default) - matches user's ID number field
  - **Email** - matches user's email address
  - **Username** - matches user's login username
  - **Moodle ID** - matches user's internal Moodle user ID

### Link Placement
- **Top** - Display survey link at the top of the block (above message)
- **Bottom** (default) - Display survey link at the bottom of the block

## Block Display

The block displays content in the following order:

1. **Header Image** (if configured)
2. **Block Message** (customizable HTML content)
3. **Survey Link Button** (prominent Bootstrap button)

The survey link displays as a primary-colored button with a checkmark icon and "Click here to access your survey" text.

## Usage

### Adding the Block to Pages

The block is available on:
- **Site Home** page
- **My Dashboard** (My Courses page)

To add the block:

1. Navigate to the desired page
2. Click **Edit page** or **Customise this page**
3. Click **Add a block**
4. Select **NSSE Survey** from the block list

### Block Display Behavior

- The block is only visible to **logged-in users** (not guest users)
- Students without a matching STUDENTID in the CSV data will not see the block
- Only students who have a matching entry in your CSV data receive their survey link

## File Structure

```
nsse_survey/
├── block_nsse_survey.php     # Main block class
├── settings.php              # Plugin settings configuration
├── version.php               # Plugin metadata
├── lib.php                   # File serving callback function
├── README.md                 # This file
├── LICENSE                   # License information
├── db/
│   └── access.php           # Capability definitions
├── lang/
│   └── en/
│       └── block_nsse_survey.php  # English language strings
└── templates/
    └── content.mustache      # Block content Mustache template
```

## Requirements

- Moodle 5.1 or higher
- PHP 8.2 or higher
- Bootstrap 5 (included with Moodle 5.1+)

## License

This plugin is licensed under the GNU General Public License v3 or later. See the LICENSE file for details.

## Credits

### Original Development
- Created by: Matt Lovell, Senior Director at Rose-Hulman Institute of Technology

### Recent Updates (2026)
- Upgraded to Moodle 5.1 compatibility
- Migrated from Bootstrap 4 to Bootstrap 5
- Replaced raw Font Awesome icon tags with Moodle pix icon helper
- Updated context API to use `\core\context\system`
- Added `MATURITY_STABLE` and release metadata to version.php
- Updates by: Patrick Thibaudeau & Christian Quan Kep at York University

## Support

For issues, feature requests, or contributions, please contact your Moodle administrator or institution's IT support team.

## See Also

- [NSSE Survey Official Website](https://nsse.indiana.edu/)
- [Moodle Block Documentation](https://docs.moodle.org/en/Block)
- [Bootstrap 5 Documentation](https://getbootstrap.com/docs/5.0/)

