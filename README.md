# Text-Konvertierung

German-language website for **text extraction (OCR)**, **machine translation**, and **text-to-speech**. The UI is a single-page layout built on the [UIdeck “Basic”](https://uideck.com/) template (Bootstrap, jQuery, and bundled vendor scripts under `assets/`).

## Features

- **OCR**: Upload a PDF or image (`jpg`, `jpeg`, `png`, `gif`, `pdf`), choose the document language, and extract text. The implementation delegates OCR to a remote service (`convert_file.php` on a separate host—**not included in this repository**).
- **Translation**: Paste or upload text, select source and target languages; text is sent to the [WhatsMate](http://api.whatsmate.net/) translation API in chunks (~2400 characters). The translated result is written to `uploads/` and offered as a download.
- **Text-to-speech**: After translation, optional playback and download of WAV audio via [VoiceRSS](http://api.voicerss.org/) (proxied through `audio_download.php`).
- **Upload housekeeping**: Files in `uploads/` older than seven days are deleted on each request to `index.php`.

## Requirements

- **PHP** 7.x or 8.x with:
  - `curl` (translation requests)
  - File uploads enabled
  - `allow_url_fopen` if you rely on `file_get_contents()` for remote OCR/TTS flows
- A web server (Apache, nginx + PHP-FPM, or PHP’s built-in server for local checks).
- Writable **`uploads/`** directory.

## Project layout

| Path | Role |
|------|------|
| `index.php` | Main page: forms, server-side processing, Impressum/Datenschutz/Kontakt |
| `audio_download.php` | POST endpoint: calls VoiceRSS, saves WAV under `uploads/`, returns public URL |
| `assets/` | CSS, JS, images, fonts (theme + plugins) |
| `uploads/` | Generated `.txt` / `.wav` and uploaded working files (gitignored recommended) |
| `license.txt` | UIdeck Basic template license terms |

Optional stylesheets referenced in `index.php` (`./style.css`, `./index.css`) are not present in the tree; add them at the project root if you use site-specific overrides.

## Local development

From the project root:

```bash
php -S localhost:8080
```

Open `http://localhost:8080/index.php`. OCR and some TTS flows call **external URLs**; they will only work if those endpoints are reachable and correctly configured for your environment.

## Configuration

1. **WhatsMate** (translation): Set client credentials in `index.php` (variables `CLIENT_ID` / `CLIENT_SECRET`) per your WhatsMate account.
2. **VoiceRSS** (speech): API key is used in `audio_download.php`. Replace with your own key and respect their terms/limits.
3. **OCR**: `index.php` builds a URL to `convert_file.php` on another deployment. For a self-contained setup you must host or replace that service and update the URL accordingly.
4. **Production URLs**: `audio_download.php` echoes a fixed base URL for generated WAV files. Adjust that to match your domain when deploying elsewhere.

**Security:** API keys and secrets should not live in version control. Prefer environment variables or a server-only config file excluded from git, and rotate any credentials that were ever committed.

## External services

- [WhatsMate Translation API](http://api.whatsmate.net/v1/translation/translate)
- [VoiceRSS](http://api.voicerss.org/)
- Remote OCR (`convert_file.php` – external to this repo)

## Template license

The landing-page theme is the **free lite** version of UIdeck “Basic”. See `license.txt` for restrictions (e.g. no commercial use, footer credit). For commercial use, purchase a license from [UIdeck](https://uideck.com/templates/basic).

## Contributing / maintenance notes

- Long texts are split for translation; inspect API responses if you see truncated or malformed output.
- SSL verification is disabled in one helper (`post_request` in `index.php`); tightening this is recommended for production (proper CA bundle instead of `verify_peer => false`).
