# Lost and Found Central

A web application for managing lost and found items.

## Overview

Lost and Found Central is a PHP-based web application that allows users to:

- View lost and found items on the homepage
- Browse detailed listings of all lost or found items
- View detailed information about specific items
- Submit new lost or found items through a simple form

## Features

- **Home Page**: Displays carousels of both lost and found items
- **Lost Items Page**: Grid view of all lost items
- **Found Items Page**: Grid view of all found items
- **Detail Page**: Unified detail page for both lost and found items
- **Modal Forms**: Easy-to-use forms for submitting new items
- **Responsive Design**: Works on mobile and desktop devices

## Project Structure

```
lost-and-found-central/
├── assets/
│   ├── css/          # Stylesheets
│   ├── js/           # JavaScript files
│   └── images/       # Image files
├── lib/              # Data files
│   └── items.json    # Combined data for lost and found items
├── pages/            # PHP pages
│   ├── home.php      # Homepage
│   ├── lost.php      # Lost items page
│   ├── found.php     # Found items page
│   └── detail.php    # Item detail page
├── templates/        # HTML templates
│   ├── index.html    # Template for homepage
│   ├── lost.html     # Template for lost items page
│   ├── found.html    # Template for found items page
│   └── detail.html   # Template for detail page
└── index.php         # Redirect to homepage
```

## Technology Stack

- **Frontend**: HTML, CSS, JavaScript
- **Backend**: PHP
- **Data Storage**: SQL seeded by JSON file

## Setup Instructions

1. Clone the repository
2. Place the files in your web server's document root
3. Navigate to the project URL in your browser

## License

This project is open-source and available under the MIT License.
