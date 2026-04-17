# Upgrade guide

## Upgrading to 1.0.1

First version of Image plugin.

### Requirements

- October CMS v4.0 or higher
- PHP 8.2 or higher
- GD or Imagick PHP extension

### New Features

- Twig filters for image manipulation
- Automatic caching and optimization
- Support for multiple image effects
- WebP conversion
- Watermark support
- Base64 encoding
- Image dimension getters

### Installation

After installation, ensure your storage directory is writable:

```bash
chmod -R 775 storage/app/uploads/public/
```

The plugin will automatically create the `manipulations` subdirectory when first used.

### Configuration

No configuration required. The plugin works out of the box with sensible defaults.

### Breaking Changes

None - this is the first version.

### Migration Notes

If you're migrating from custom image manipulation code:

1. Replace manual image processing with Twig filters
2. Remove any custom caching logic (plugin handles this automatically)
3. Update templates to use new filter syntax

Example migration:

```twig
{# Before (custom code) #}
{% set processedImage = customImageProcessor(image, {'blur': 10}) %}
<img src="{{ processedImage }}">

{# After (Image plugin) #}
<img src="{{ image|blur(10) }}">
```

### Performance Improvements

- Automatic caching of manipulated images
- MD5-based cache key generation prevents collisions
- Global optimization enabled by default
- Cache invalidation based on source file modification time

### Known Limitations

- Watermark positioning options are limited in this version
- Configuration options are hardcoded (planned for future release)
- Global optimization cannot be disabled

## Future Versions

Planned features for upcoming releases:

### Version 1.1.0 (Planned)

- Configurable cache directory
- Configurable optimization timeout
- Toggle for global optimization
- Custom default watermark path
- Watermark positioning options (top-left, top-right, center, etc.)
- Watermark opacity control

### Version 1.2.0 (Planned)

- Image resizing filters
- Crop functionality
- Rotation and flip filters
- Multiple format output
- Advanced color manipulation

### Version 2.0.0 (Planned)

- Backend cache management UI
- Statistics and cache size reporting
- Batch processing capabilities
- Custom optimizer configuration
- Event hooks for extending functionality

## Upgrading Best Practices

When upgrading the Image plugin:

1. **Backup your cache**: Although cache can be regenerated, backing up prevents unnecessary reprocessing
   ```bash
   cp -r storage/app/uploads/public/manipulations storage/backups/manipulations-backup
   ```

2. **Test in development first**: Always test plugin upgrades in a development environment

3. **Clear cache if needed**: After upgrading, you may want to regenerate manipulated images
   ```bash
   rm -rf storage/app/uploads/public/manipulations/*
   ```

4. **Check logs**: Monitor `storage/logs/system.log` for any issues after upgrading

5. **Verify optimizer binaries**: Ensure system optimizers are still available after system updates

## Troubleshooting Upgrades

### Cache directory not created

**Solution:**
```bash
mkdir -p storage/app/uploads/public/manipulations
chmod -R 775 storage/app/uploads/public/manipulations
chown -R www-data:www-data storage/app/uploads/public/manipulations
```

### Images not regenerating after upgrade

**Solution:**
Clear the cache to force regeneration:
```bash
rm -rf storage/app/uploads/public/manipulations/*
```

### Permission issues

**Solution:**
Reset permissions:
```bash
chmod -R 775 storage/app/uploads
chown -R www-data:www-data storage/app/uploads
```

### Optimizer not working after system update

**Solution:**
Reinstall optimizer binaries:
```bash
# Ubuntu/Debian
sudo apt-get update
sudo apt-get install --reinstall jpegoptim optipng pngquant gifsicle webp
```

## Getting Support

If you encounter issues during upgrade:

1. Check this upgrade guide first
2. Review the [README.md](README.md) documentation
3. Check the [GitHub Issues](https://github.com/mplodowski/image-plugin-public/issues)
4. Contact support through the October CMS marketplace

## Contributing

Found a bug or have a feature request? Please open an issue on GitHub:
https://github.com/mplodowski/image-plugin-public/issues
