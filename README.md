# Eugene API Plugin

## Description

The Eugene API Plugin is a WordPress plugin that provides a Gutenberg block for displaying data fetched from an external API. The data is displayed in a table format with columns that can be toggled on or off in the block settings. The plugin also includes an admin page for manually refreshing the data from the API.

## Features

- Fetches data from an external API and caches it for improved performance.
- Displays data in a table format in a custom Gutenberg block.
- Allows users to toggle the visibility of table columns.
- Provides an admin page for refreshing the cached data manually.
- Fully translatable and localizable.

## Installation

### Prerequisites

- WordPress 5.8 or higher
- PHP 7.4 or higher
- Node.js and npm

### Manual Installation

1. **Download the Plugin**
   - Download the latest version of the plugin from the [GitHub repository](https://github.com/EugeneKyale/eugene-api-plugin).

2. **Extract the Plugin**
   - Extract the downloaded ZIP file to your WordPress plugins directory (`/wp-content/plugins/`).

3. **Install Dependencies**
   - Navigate to the plugin directory in your terminal:
     ```sh
     cd /path/to/wp-content/plugins/eugene-api-plugin
     ```

4. **Install Composer Dependencies**
   - If you have Composer installed, run:
     ```sh
     composer install
     ```

5. **Install Node.js Dependencies**
   - Install the required npm packages:
     ```sh
     npm install
     ```

6. **Build the Plugin**
   - Run the build process to compile the JavaScript and CSS files:
     ```sh
     npm run build
     ```

7. **Activate the Plugin**
   - Go to the WordPress admin dashboard, navigate to Plugins, and activate the Eugene API Plugin.

### Installation via Composer

1. **Add the Plugin to Your Project**
   - Use Composer to require the plugin:
     ```sh
     composer require eugene/api-plugin
     ```

2. **Install Node.js Dependencies**
   - Navigate to the plugin directory and install the npm packages:
     ```sh
     cd /path/to/wp-content/plugins/eugene-api-plugin
     npm install
     ```

3. **Build the Plugin**
   - Run the build process:
     ```sh
     npm run build
     ```

4. **Activate the Plugin**
   - Go to the WordPress admin dashboard, navigate to Plugins, and activate the Eugene API Plugin.

## Usage

1. **Adding the Block**
   - In the WordPress block editor, search for the "API Data Block" and add it to your post or page.

2. **Configuring the Block**
   - Use the block settings panel to toggle the visibility of the table columns.

3. **Refreshing Data**
   - Go to the Eugene API admin page under the Settings menu and click the "Refresh Data" button to manually refresh the cached data from the API.

## Development

### Directory Structure

- `src/`: Contains the PHP source code for the plugin.
- `src/blocks/`: Contains the JavaScript and CSS for the Gutenberg block.
- `build/`: Contains the compiled JavaScript and CSS files.
- `languages/`: Contains the translation files.

### Commands

- `npm run build`: Builds the JavaScript and CSS files.
- `composer install`: Installs the PHP dependencies.

## Contributing

Contributions are welcome! Please create a fork of the repository and submit a pull request with your changes.

## License

This project is licensed under the MIT License.
