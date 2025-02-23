---
title: Mint Starter Kit Configuration Guide
description: Learn how to effectively utilize the configuration file in the Mint Starter Kit to customize your documentation site.
---

## Introduction

The Mint Starter Kit includes a powerful configuration file, `mint.json`, designed to help you customize and configure your documentation site with ease. This guide will walk you through the various aspects of the configuration file, providing you with the knowledge needed to tailor your site to your specific needs.

## Configuration Schema Overview

The `mint.json` file follows a specific schema that outlines the structure and options available for configuring your documentation site. The schema includes settings for themes, site name, color schemes, favicon, navigation tabs, logo, navbar links, and footer social links. Understanding this schema is the first step in customizing your documentation site.

```json
{
  "$schema": "https://mintlify.com/docs.json",
  "theme": "mint",
  "name": "Mint Starter Kit",
  "colors": {...},
  "favicon": "/favicon.svg",
  "navigation": {...},
  "logo": {...},
  "navbar": {...},
  "footer": {...}
}
```

## Customizing Themes

The `theme` property allows you to set the overall theme of your documentation site. Currently, the Mint Starter Kit supports a default theme named "mint". Future updates may introduce additional themes.

```json
{
  "theme": "mint"
}
```

## Defining Navigation Structure

The `navigation` object is where you define the structure of your site's navigation. It consists of tabs, groups within those tabs, and pages within each group. This hierarchical structure helps organize your documentation into easily navigable sections.

```json
{
  "navigation": {
    "tabs": [
      {
        "tab": "Guides",
        "groups": [
          {
            "group": "Get Started",
            "pages": ["introduction", "quickstart", "development"]
          }
        ]
      }
    ]
  }
}
```

## Setting Colors, Favicon, and Logo

Customize your site's color scheme, favicon, and logo through the `colors`, `favicon`, and `logo` properties. This allows you to align the look and feel of your documentation site with your brand's identity.

```json
{
  "colors": {
    "primary": "#16A34A",
    "light": "#07C983",
    "dark": "#15803D"
  },
  "favicon": "/favicon.svg",
  "logo": {
    "light": "/logo/light.svg",
    "dark": "/logo/dark.svg"
  }
}
```

## Configuring Navbar Links

The `navbar` object lets you define links and a primary button that appear in the site's navigation bar. This is useful for providing quick access to important resources or actions.

```json
{
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
  }
}
```

## Adding Footer Social Links

In the `footer` object, you can specify social media links to enhance your site's connectivity and reach. This helps users find your community or follow your updates on different platforms.

```json
{
  "footer": {
    "socials": {
      "x": "https://x.com/mintlify",
      "github": "https://github.com/mintlify",
      "linkedin": "https://linkedin.com/company/mintlify"
    }
  }
}
```

## Examples

### Basic Configuration Example

A simple configuration to get you started with customizing your documentation site.

```json
{
  "theme": "mint",
  "name": "My Documentation",
  "colors": {
    "primary": "#0055FF"
  },
  "favicon": "/favicon.png",
  "navigation": {...},
  "logo": {...},
  "navbar": {...},
  "footer": {...}
}
```

### Theme Customization Example

Customizing the theme involves setting the `theme` property and adjusting the `colors` to match your brand.

```json
{
  "theme": "mint",
  "colors": {
    "primary": "#FF5500",
    "light": "#FFA07A",
    "dark": "#FF4500"
  }
}
```

### Navigation Structure Example

Defining a detailed navigation structure to help users explore your documentation.

```json
{
  "navigation": {
    "tabs": [
      {
        "tab": "Guides",
        "groups": [
          {
            "group": "Advanced Topics",
            "pages": ["scaling", "security", "optimization"]
          }
        ]
      }
    ]
  }
}
```

This guide provides a comprehensive overview of the `mint.json` configuration file, empowering you to customize your Mint Starter Kit documentation site to better suit your project's needs.