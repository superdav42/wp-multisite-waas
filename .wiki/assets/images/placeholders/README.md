# Image Placeholders

This directory contains placeholder images for the documentation. The original images were hosted on external servers that require authentication to access.

## Missing Images

The original documentation contained images hosted on the following domains:
- wp-ultimo-space.fra1.cdn.digitaloceanspaces.com
- downloads.intercomcdn.com
- docs.nextpress.us
- support.delta.nextpress.co

These images could not be downloaded due to 403 Forbidden errors. To properly display these images, you would need to:

1. Obtain proper authentication credentials for these domains
2. Download the images using those credentials
3. Replace the placeholder references in the documentation

## Wayback Machine Option

Some of these images might be available through the Wayback Machine (web.archive.org). For example:

```
https://web.archive.org/web/20250127210724im_/https://wp-ultimo-space.fra1.cdn.digitaloceanspaces.com/hs-file-RvbtUn4r3w.png
```

A future enhancement could be to download these images from the Wayback Machine and replace the placeholders.

## Placeholder Strategy

For now, all image references have been updated to point to local paths in the `assets/images` directory. When the actual images become available, they can be added to this directory without needing to update the markdown files.
