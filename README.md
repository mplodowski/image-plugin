# Image plugin

Plugin provides powerful Twig filters for manipulating images on-the-fly with automatic caching and optimization.

![OctoberCMS Image Plugin](https://via.placeholder.com/800x400.png?text=Image+Manipulation+Plugin)

## Features

* Easy-to-use Twig filters for image manipulation
* Automatic caching of manipulated images
* Built-in image optimization
* WebP format conversion
* Watermark support
* Multiple image effects (blur, pixelate, greyscale, sepia, sharpen)
* Color adjustments (brightness, contrast, gamma, colorize)
* Quality control
* Image dimension getters
* Base64 encoding support
* No backend UI required - works directly in templates
* Based on robust [Spatie Image](https://github.com/spatie/image) package

## Why is this a paid plugin?

Something that is free has little or no perceived value. Users do not commit to free products and only use them until
something else looks nice and is free comes along. When I invest my time in the development of a new plugin I commit to
supporting and maintaining it. I ask my customers to do the same. I do not make money from this plugin by
advertisements, upgrades or additional services like hosting or setup.

Did you know that 30% of your purchase or donation goes to help fund the October Project?

My plugins take many hours to develop (40-120+) and even more hours to document and maintain. My paid plugins have to
pay for both this time, and the time I am spending on free plugins and less successful paid plugins. This means that it
will take even a successful plugin years to become profitable. Please consider buying an extended license if you want me
to continue to maintain these plugins for the very small fee I ask in return or hire me for adding functionality that
you feel is missing but valuable.

## Like this plugin?

If you like this plugin, give this plugin a Like or Make donation with [PayPal](https://www.paypal.me/mplodowski).

## My other plugins

Please check my other [plugins](https://octobercms.com/author/Renatio).

## Support

Please use [GitHub Issues Page](https://github.com/mplodowski/image-plugin-public/issues) to report any issues
with plugin.

> Reviews should not be used for getting support, if you need support please use the Plugin support link.

Icon made by [Freepik](https://www.flaticon.com/authors/freepik)
from [www.flaticon.com](https://www.flaticon.com/).

# Documentation

## Requirements

* October CMS v4.0 or higher
* PHP 8.2 or higher
* GD or Imagick PHP extension
* Composer

## Installation

### Via October CMS Marketplace

1. Navigate to **Settings** → **Updates & Plugins** in your October CMS backend
2. Click **Install plugins**
3. Search for "Renatio Image"
4. Click **Install**

### Via Composer

```bash
composer require renatio/image-plugin
```

After installation, run the migrations (although this plugin doesn't require database tables):

```bash
php artisan october:migrate
```

## How It Works

The Image plugin registers Twig filters that you can use directly in your templates. When you apply a filter to an image:

1. The plugin checks if a manipulated version already exists in the cache
2. If not, it creates the manipulated image using the Spatie Image library
3. The manipulated image is automatically optimized
4. The result is saved to `storage/app/uploads/public/manipulations/`
5. Subsequent requests serve the cached version for optimal performance

### Caching Strategy

Cached file names are generated using MD5 hashing based on:
- Original image path
- Manipulation type
- Manipulation parameters
- Original file modification time

This ensures that:
- Cache is automatically invalidated when source images change
- Different manipulations of the same image are cached separately
- Cache files have unique, collision-free names

## Usage

All filters are available in Twig templates. Simply pipe your image path through any filter:

### Basic Example

```twig
{# Apply greyscale effect #}
<img src="{{ 'path/to/image.jpg'|greyscale }}" alt="Greyscale image">

{# Chain multiple filters #}
<img src="{{ 'path/to/image.jpg'|brightness(20)|contrast(1.2)|sharpen(15) }}" alt="Enhanced image">
```

### Responsive Images with WebP

```twig
<picture>
    <source srcset="{{ 'assets/images/hero.jpg'|webp }}" type="image/webp">
    <img src="{{ 'assets/images/hero.jpg' }}" alt="Hero image">
</picture>
```

## Available Filters

### Brightness

Adjust image brightness.

**Parameters:**
- `brightness` (int): Brightness level from -100 (darker) to +100 (brighter)

**Example:**
```twig
{# Increase brightness #}
<img src="{{ 'images/dark-photo.jpg'|brightness(30) }}">

{# Decrease brightness #}
<img src="{{ 'images/bright-photo.jpg'|brightness(-20) }}">
```

### Contrast

Adjust image contrast.

**Parameters:**
- `level` (float): Contrast level. Use values like 0.5 (low contrast), 1.0 (normal), 1.5 (high contrast)

**Example:**
```twig
<img src="{{ 'images/photo.jpg'|contrast(1.3) }}">
```

### Gamma

Adjust image gamma correction.

**Parameters:**
- `gamma` (float): Gamma value. Typical range: 0.1 to 3.0

**Example:**
```twig
<img src="{{ 'images/photo.jpg'|gamma(1.5) }}">
```

### Colorize

Apply color tint to image.

**Parameters:**
- `red` (int): Red channel adjustment (-255 to +255)
- `green` (int): Green channel adjustment (-255 to +255)
- `blue` (int): Blue channel adjustment (-255 to +255)

**Example:**
```twig
{# Add red tint #}
<img src="{{ 'images/photo.jpg'|colorize(100, 0, 0) }}">

{# Add sepia-like tint #}
<img src="{{ 'images/photo.jpg'|colorize(100, 50, -50) }}">
```

### Blur

Apply blur effect to image.

**Parameters:**
- `blur` (int): Blur amount (0-100, higher values = more blur)

**Example:**
```twig
<img src="{{ 'images/background.jpg'|blur(15) }}">
```

### Pixelate

Apply pixelation effect to image.

**Parameters:**
- `pixelate` (int, optional): Pixel block size. Default: 50

**Example:**
```twig
{# Default pixelation #}
<img src="{{ 'images/photo.jpg'|pixelate }}">

{# Custom pixel size #}
<img src="{{ 'images/photo.jpg'|pixelate(20) }}">
```

### Greyscale

Convert image to greyscale (black and white).

**Parameters:** None

**Example:**
```twig
<img src="{{ 'images/color-photo.jpg'|greyscale }}">
```

**Alternative spelling:**
```twig
<img src="{{ 'images/color-photo.jpg'|grayscale }}">
```

### Sepia

Apply sepia tone effect to image.

**Parameters:** None

**Example:**
```twig
<img src="{{ 'images/photo.jpg'|sepia }}">
```

### Sharpen

Sharpen image details.

**Parameters:**
- `amount` (float): Sharpening amount (typical range: 10-50)

**Example:**
```twig
<img src="{{ 'images/blurry-photo.jpg'|sharpen(25) }}">
```

### Watermark

Add watermark image overlay.

**Parameters:**
- `watermarkImage` (string, optional): Path to watermark image. Default: `themes/demo/assets/images/watermark.png`
- `options` (array, optional): Additional watermark options (position, opacity, etc.)

**Example:**
```twig
{# Use default watermark #}
<img src="{{ 'images/photo.jpg'|watermark }}">

{# Use custom watermark #}
<img src="{{ 'images/photo.jpg'|watermark('assets/images/logo.png') }}">
```

**Note:** Watermark positioning options are currently being developed. The watermark will be placed according to Spatie Image defaults.

### WebP

Convert image to WebP format for better compression and web performance.

**Parameters:** None

**Example:**
```twig
{# Simple conversion #}
<img src="{{ 'images/photo.jpg'|webp }}">

{# Use in picture element for progressive enhancement #}
<picture>
    <source srcset="{{ 'images/hero.jpg'|webp }}" type="image/webp">
    <source srcset="{{ 'images/hero.jpg' }}" type="image/jpeg">
    <img src="{{ 'images/hero.jpg' }}" alt="Hero image">
</picture>
```

### Optimize

Optimize image file size without quality loss using image optimizers.

**Parameters:** None

**Example:**
```twig
<img src="{{ 'images/large-photo.jpg'|optimize }}">
```

**Note:** This filter uses the Spatie Image Optimizer package which requires system-level optimizer binaries (optipng, pngquant, jpegoptim, etc.). See [Installation Requirements](#optimizer-installation) for details.

### Quality

Adjust image quality (compression level).

**Parameters:**
- `quality` (int): Quality level (1-100, where 100 is highest quality/largest file size)

**Example:**
```twig
{# High quality for important images #}
<img src="{{ 'images/portfolio-item.jpg'|quality(90) }}">

{# Lower quality for thumbnails #}
<img src="{{ 'images/thumbnail.jpg'|quality(70) }}">
```

### Base64

Encode image as Base64 data URI for embedding directly in HTML.

**Parameters:**
- `imageFormat` (string, optional): Output format ('jpeg', 'png', 'gif', 'webp'). Default: 'jpeg'
- `prefixWithFormat` (bool, optional): Include data URI prefix. Default: true

**Example:**
```twig
{# Inline image as data URI #}
<img src="{{ 'images/small-icon.png'|base64 }}" alt="Icon">

{# Specify format #}
<img src="{{ 'images/logo.png'|base64('png') }}" alt="Logo">

{# Without data URI prefix (for CSS or other uses) #}
{% set imageData = 'images/icon.png'|base64('png', false) %}
```

**Best Practice:** Only use Base64 encoding for small images (icons, small logos) to avoid bloating your HTML.

### Get Width

Get image width in pixels.

**Parameters:** None

**Returns:** int

**Example:**
```twig
{% set imageWidth = 'images/photo.jpg'|get_width %}
<p>Image width: {{ imageWidth }}px</p>

{# Use in conditional logic #}
{% if 'images/photo.jpg'|get_width > 1200 %}
    <p>This is a large image</p>
{% endif %}
```

### Get Height

Get image height in pixels.

**Parameters:** None

**Returns:** int

**Example:**
```twig
{% set imageHeight = 'images/photo.jpg'|get_height %}
<p>Image height: {{ imageHeight }}px</p>

{# Calculate aspect ratio #}
{% set width = 'images/photo.jpg'|get_width %}
{% set height = 'images/photo.jpg'|get_height %}
{% set aspectRatio = (height / width * 100)|round(2) %}
<p>Aspect ratio: {{ aspectRatio }}%</p>
```

## Chaining Filters

You can chain multiple filters together to create complex image manipulations:

```twig
{# Create a vintage photo effect #}
<img src="{{ 'images/photo.jpg'|sepia|contrast(0.8)|brightness(10)|sharpen(15) }}">

{# Optimize and convert to WebP #}
<img src="{{ 'images/photo.jpg'|optimize|webp }}">

{# Create blurred background image #}
<div style="background-image: url({{ 'images/bg.jpg'|blur(30)|brightness(-20) }})">
    Content here
</div>
```

**Performance Note:** Each filter in the chain creates a new manipulation. The plugin caches the final result, so chained filters don't impact runtime performance on subsequent requests.

## Advanced Examples

### Responsive Product Images

```twig
{# Product image with optimization #}
<div class="product-image">
    <picture>
        <source srcset="{{ product.image|quality(85)|webp }}" type="image/webp">
        <img src="{{ product.image|quality(85) }}" alt="{{ product.name }}">
    </picture>
</div>
```

### Hero Section with Blurred Background

```twig
<section class="hero" style="background-image: url({{ 'images/hero.jpg'|blur(10)|brightness(-15) }})">
    <div class="hero-content">
        <h1>Welcome</h1>
    </div>
</section>
```

### Image Gallery with Greyscale Hover Effect

```twig
{% for image in gallery.images %}
    <div class="gallery-item">
        <img src="{{ image.path|greyscale }}"
             data-color="{{ image.path }}"
             alt="{{ image.title }}"
             onmouseover="this.src=this.dataset.color"
             onmouseout="this.src='{{ image.path|greyscale }}'">
    </div>
{% endfor %}
```

### Portfolio Grid with Watermarks

```twig
<div class="portfolio-grid">
    {% for item in portfolio.items %}
        <div class="portfolio-item">
            <img src="{{ item.image|watermark('assets/images/logo.png')|quality(90) }}"
                 alt="{{ item.title }}">
        </div>
    {% endfor %}
</div>
```

### Dynamic Image Dimensions

```twig
{% set imagePath = 'images/product.jpg' %}
{% set width = imagePath|get_width %}
{% set height = imagePath|get_height %}

<img src="{{ imagePath }}"
     width="{{ width }}"
     height="{{ height }}"
     alt="Product"
     loading="lazy">
```

## Storage and Caching

### Cache Location

Manipulated images are stored in:
```
storage/app/uploads/public/manipulations/
```

### Cache File Naming

Files are named using MD5 hashes to ensure uniqueness:
```
{md5_hash}.{extension}
```

The hash is generated from:
- Original image path
- Manipulation type
- Manipulation parameters
- Source file modification time

### Cache Invalidation

The cache is automatically invalidated when:
- The source image is modified (detected via file modification time)
- You manually delete the cached files

### Manual Cache Clearing

To clear the image manipulation cache:

```bash
# Remove all manipulated images
rm -rf storage/app/uploads/public/manipulations/*
```

Or create a backend controller action:

```php
use Illuminate\Support\Facades\File;

public function clearImageCache()
{
    $path = storage_path('app/uploads/public/manipulations');

    if (File::isDirectory($path)) {
        File::deleteDirectory($path, true);
        File::makeDirectory($path);
    }

    Flash::success('Image cache cleared successfully');
}
```

## Configuration

Currently, the plugin uses hardcoded defaults. Configuration options may be added in future versions for:

- Cache directory path
- Optimization timeout (currently 120 seconds)
- Global optimization toggle
- Default watermark image path
- Default quality settings

## Optimizer Installation

The `optimize` filter requires system-level image optimizer binaries to be installed on your server.

### Ubuntu/Debian

```bash
sudo apt-get install jpegoptim optipng pngquant gifsicle webp
```

### macOS (via Homebrew)

```bash
brew install jpegoptim optipng pngquant gifsicle webp
```

### Windows

Download and install optimizers manually, or use tools like Chocolatey:

```powershell
choco install jpegoptim optipng pngquant gifsicle webp
```

### Verify Installation

Check if optimizers are available:

```bash
which jpegoptim optipng pngquant gifsicle cwebp
```

For more details, see [Spatie Image Optimizer documentation](https://github.com/spatie/image-optimizer).

## Troubleshooting

### Images not appearing after applying filters

**Possible causes:**
1. Source image path is incorrect
2. GD or Imagick extension not installed
3. Insufficient disk space
4. Permission issues with storage directory

**Solutions:**
```bash
# Check if GD is installed
php -m | grep -i gd

# Check if Imagick is installed
php -m | grep -i imagick

# Fix storage permissions
chmod -R 775 storage/app/uploads/public/manipulations/
chown -R www-data:www-data storage/app/uploads/public/manipulations/
```

### "File not found" errors

**Problem:** The plugin can't find the source image.

**Solution:** Always use paths relative to your October CMS base directory:

```twig
{# ✅ Correct #}
<img src="{{ 'themes/mytheme/assets/images/photo.jpg'|blur(10) }}">

{# ❌ Incorrect - absolute path #}
<img src="{{ '/var/www/html/themes/mytheme/assets/images/photo.jpg'|blur(10) }}">
```

### Filter returns original image unchanged

**Possible causes:**
1. Exception occurred during processing (silently caught)
2. Source file doesn't exist
3. Image manipulation failed

**Debug:**
Enable October CMS debug mode in `config/app.php`:

```php
'debug' => true,
```

Check October CMS log files:
```bash
tail -f storage/logs/system.log
```

### Optimized images not smaller

**Problem:** The `optimize` filter doesn't reduce file size significantly.

**Possible causes:**
1. Optimizers not installed on the system
2. Images already optimized
3. Optimizer binaries not in system PATH

**Solution:**
Verify optimizers are installed (see [Optimizer Installation](#optimizer-installation)).

### Watermark not appearing

**Problem:** Watermark filter doesn't show the watermark.

**Possible causes:**
1. Watermark image path is incorrect
2. Watermark image doesn't exist
3. Watermark positioning options need to be configured

**Solution:**
```twig
{# Verify watermark file exists #}
{% if 'themes/demo/assets/images/watermark.png'|file_exists %}
    <img src="{{ 'images/photo.jpg'|watermark('themes/demo/assets/images/watermark.png') }}">
{% endif %}
```

### High memory usage

**Problem:** Image manipulation causes memory errors.

**Possible causes:**
1. Processing very large images
2. PHP memory limit too low

**Solutions:**

Increase PHP memory limit in `php.ini`:
```ini
memory_limit = 256M
```

Or temporarily in your code:
```php
ini_set('memory_limit', '256M');
```

Consider resizing large images before applying filters:
```twig
{# Use October's |resize filter first #}
<img src="{{ 'images/huge-photo.jpg'|resize(1920, 1080)|blur(10) }}">
```

### WebP conversion not working

**Problem:** WebP filter doesn't convert images.

**Possible causes:**
1. GD or Imagick doesn't support WebP
2. WebP libraries not installed

**Solutions:**

Verify WebP support:
```bash
# For GD
php -r "var_dump(function_exists('imagewebp'));"

# For Imagick
php -r "var_dump(in_array('WEBP', \Imagick::queryFormats()));"
```

Install WebP libraries:
```bash
# Ubuntu/Debian
sudo apt-get install libwebp-dev

# Rebuild PHP with WebP support or install/enable imagick extension
```

### Permission denied errors

**Problem:** Can't write to cache directory.

**Solution:**
```bash
# Create directory if it doesn't exist
mkdir -p storage/app/uploads/public/manipulations

# Set correct permissions
chmod -R 775 storage/app/uploads/public/manipulations/
chown -R www-data:www-data storage/app/uploads/public/manipulations/

# For development (less secure, don't use in production)
chmod -R 777 storage/app/uploads/public/manipulations/
```

## Testing

The plugin includes comprehensive tests using Pest PHP.

### Running Tests

```bash
# Run all Image plugin tests
php artisan test --testsuite="Image Plugin"

# Run specific test file
php artisan test plugins/renatio/image/tests/Unit/Classes/ImageManipulationTest.php

# Run with coverage
php artisan test --testsuite="Image Plugin" --coverage
```

For detailed testing documentation, see [tests/README.md](tests/README.md).

## Performance Considerations

### Best Practices

1. **Use appropriate quality settings**: Balance file size and visual quality
   ```twig
   {# Good for web #}
   <img src="{{ image|quality(85) }}">
   ```

2. **Chain filters efficiently**: Order filters to minimize processing
   ```twig
   {# Optimize last #}
   <img src="{{ image|brightness(10)|contrast(1.2)|optimize }}">
   ```

3. **Leverage caching**: The first request processes the image; subsequent requests serve from cache

4. **Use WebP for modern browsers**: Significantly smaller file sizes
   ```twig
   <picture>
       <source srcset="{{ image|webp }}" type="image/webp">
       <img src="{{ image }}" alt="Fallback">
   </picture>
   ```

5. **Avoid processing very large images**: Resize before manipulating if possible

### Cache Performance

- **First request**: ~100-500ms (depending on image size and manipulations)
- **Cached requests**: ~1-5ms (serving static files)
- **Storage impact**: Manipulated images are stored permanently until cache is cleared

## API Usage (PHP)

While designed for Twig templates, you can use the ImageManipulation class directly in PHP:

```php
use Renatio\Image\Classes\ImageManipulation;

$imageManipulation = new ImageManipulation();

// Apply brightness
$brightImage = $imageManipulation->brightness('path/to/image.jpg', 30);

// Apply blur
$blurredImage = $imageManipulation->blur('path/to/image.jpg', 20);

// Get image dimensions
$width = $imageManipulation->getWidth('path/to/image.jpg');
$height = $imageManipulation->getHeight('path/to/image.jpg');

// Convert to WebP
$webpImage = $imageManipulation->webp('path/to/image.jpg');
```

## Credits

This plugin is built on top of these excellent packages:

- [Spatie Image](https://github.com/spatie/image) - Image manipulation library
- [Spatie Image Optimizer](https://github.com/spatie/image-optimizer) - Image optimization
- [Intervention Image](https://image.intervention.io/) - Underlying image processing (used by Spatie Image)

## License

This is a proprietary commercial plugin. License is granted per October CMS installation.

## Changelog

See [UPGRADE.md](UPGRADE.md) for version history and upgrade notes.
