# Contexis GDPR Cookie Consent

A customizable WordPress block-based cookie consent system for GDPR compliance. Includes a modal dialog, REST API integration, cookie handling, and conditional content rendering based on user consent.

## Features

-   Gutenberg block to add a **Cookie Settings Button** and **Modal**
-   Customizable texts and layout inside the block editor
-   Supports both **required** and **third-party** cookies
-   Remembers consent using cookies
-   Simple **REST API** to handle user interaction
-   Optional filtering/removal of forbidden blocks (like YouTube embeds) when consent is not granted
-   Fully stylable and extendable

---

## How it Works

1. Users can open the modal to configure cookie preferences.
2. The modal contains options for:
    - Required cookies (always active)
    - Third-party cookies (user choice)
3. Clicking **Accept All** or **Save Settings** stores the consent via the REST API.
4. If full consent is not granted, defined blocks (e.g. embeds) are removed from frontend rendering.
5. Consent is stored in two cookies:
    - `ctx_cookie_ok`: basic consent
    - `ctx_cookie_all`: full consent

---

## Gutenberg Block

You can add the `ctx-gdpr/modal` block to any post, page, or template. It contains:

-   A button with optional icon and custom title
-   Modal window with:
    -   Paragraph/text blocks (editable)
    -   Two checkboxes (required cookies, third-party cookies)
    -   Accept/Save buttons

Use the block inspector to configure modal options such as:

-   Modal title
-   Fullscreen mode
-   Icon symbol
-   Default value for third-party cookies
-   Block types to disable (e.g. `core/embed`, `someplugin/custom-embed`)

---

## REST API

**POST /wp-json/ctx-gdpr/v2/consent**

Saves user consent.

### Parameters

| Name | Type       | Description                               |
| ---- | ---------- | ----------------------------------------- |
| all  | `1` or `0` | `1` enables full consent, `0` disables it |
