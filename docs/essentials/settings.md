---
title: Customizing Your Documentation Settings
description: Learn how to tailor your Mintlify documentation settings to fit your project's needs.
---

## Overview

Customizing your documentation settings allows you to tailor the look and feel of your Mintlify documentation to better match your project's branding and organizational structure. This guide will walk you through the basics of configuring your documentation settings and introduce you to the Mint Configuration Guide for more advanced customizations.

## Getting Started with Basic Settings

Your documentation settings can be easily customized by modifying the `mint.json` file in your project. This file serves as the central configuration for your documentation site, allowing you to adjust themes, colors, navigation, and more.

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
  ...
}
```

## Key Configuration Options

- **`$schema`**: Specifies the schema URL for the configuration file. This helps with validation and autocompletion in supported editors.
- **`theme`**: Determines the overall theme of your documentation site. The default is `mint`.
- **`name`**: The name of your project or documentation site.
- **`colors`**: Defines the primary color scheme for your documentation, allowing you to match your project's branding.
- **`favicon`**: The URL or path to your project's favicon.

## Navigation and Structure

The `navigation` object within your `mint.json` file allows you to define the structure and organization of your documentation. You can specify tabs, groups within those tabs, and the pages that belong to each group.

```json
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
        },
        ...
      ]
    },
    ...
  ],
  "global": {
    "anchors": [
      {
        "anchor": "Documentation",
        "href": "https://mintlify.com/docs",
        "icon": "book-open-cover"
      },
      ...
    ]
  }
}
```

## Advanced Customization

For users looking to dive deeper into customization options, the Mint Configuration Guide provides comprehensive instructions and examples. This guide covers advanced topics such as custom themes, integrating third-party services, and leveraging the Mintlify API for dynamic content generation.

## Further Reading

- **Mint Configuration Guide**: For a detailed walkthrough of all available configuration options and advanced customization techniques, refer to the Mint Configuration Guide. This resource is designed to help you maximize the potential of your documentation site.

For more information on how to leverage these settings to their full potential, please refer to the [Mint Configuration Guide](/docs/essentials/mint-configuration-guide).

## Conclusion

Customizing your Mintlify documentation settings is a powerful way to ensure your documentation aligns with your project's identity and meets the needs of your users. By following the guidelines outlined in this document and exploring the Mint Configuration Guide, you can create a documentation site that is both informative and visually appealing.