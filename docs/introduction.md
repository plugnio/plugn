---
title: Introduction to Mint Starter Kit
description: Learn about the Mint Starter Kit and how it simplifies setting up your documentation site with enhanced configuration capabilities.
---

Welcome to the **Mint Starter Kit** documentation! This guide is designed to help you get started with the Mint Starter Kit, a comprehensive solution for setting up and customizing your documentation site. Whether you're documenting a project, API, or creating guides, the Mint Starter Kit provides a robust foundation with extensive configuration capabilities.

## What is the Mint Starter Kit?

The Mint Starter Kit is a configuration-driven approach to setting up your documentation site. It leverages a JSON-based configuration file (`mint.json`) to customize various aspects of your documentation, from theming and navigation to social links and more. This approach allows for a more streamlined and intuitive setup process, enabling you to focus on creating content rather than managing infrastructure.

## Key Features

- **Theming**: Customize the look and feel of your site with theme colors, logos, and favicons to match your brand.
- **Navigation**: Define your documentation structure with tabs and groups for better organization and user experience.
- **Global Links**: Easily add global navigation links to your documentation header, including links to your community, blog, or any external resources.
- **Social Links**: Promote your social media channels in the footer to increase engagement with your audience.
- **Customizable Navbar**: Configure the navigation bar with links to important pages or external sites, and highlight a primary action button.

## Configuration Overview

The `mint.json` file is at the heart of the Mint Starter Kit. Here's a brief overview of its structure:

```json
{
  "$schema": "https://mintlify.com/docs.json",
  "theme": "mint",
  "name": "Mint Starter Kit",
  "colors": {
    "primary": "#16A34A",
    "light": "#07C983",
    "dark": "#15803D"
  },
  "favicon": "/favicon.svg",
  "navigation": {
    "tabs": [
      {
        "tab": "Guides",
        "groups": [
          {
            "group": "Get Started",
            "pages": [
              "introduction",
              "quickstart",
              "development"
            ]
          }
        ]
      }
    ]
  },
  "logo": {
    "light": "/logo/light.svg",
    "dark": "/logo/dark.svg"
  },
  "navbar": {
    "links": [
      {
        "label": "Support",
        "href": "mailto:hi@mintlify.com"
      }
    ],
    "primary": {
      "type": "button",
      "label": "Dashboard",
      "href": "https://dashboard.mintlify.com"
    }
  },
  "footer": {
    "socials": {
      "x": "https://x.com/mintlify",
      "github": "https://github.com/mintlify",
      "linkedin": "https://linkedin.com/company/mintlify"
    }
  }
}
```

## Getting Started

To get started with the Mint Starter Kit:

1. **Setup**: Clone the starter kit repository and install dependencies.
2. **Configuration**: Edit the `mint.json` file to match your project's needs.
3. **Content Creation**: Start adding your documentation content in Markdown or MDX format.
4. **Customization**: Further customize your site by adjusting the theme, navigation, and other settings as needed.

For detailed instructions on each step and further customization options, refer to the [Mint Configuration Guide](/docs/essentials/mint-configuration-guide).

## Conclusion

The Mint Starter Kit offers a powerful and flexible starting point for your documentation projects. By leveraging the capabilities of the `mint.json` configuration file, you can easily set up a documentation site that not only looks great but is also well-organized and easy to navigate. Start documenting your project today and provide your users with an exceptional learning experience.