# Image Plugin Tests

## Running Tests

```bash
php vendor/bin/pest plugins/renatio/image/tests -c plugins/renatio/image/phpunit.xml -p
```

## Test Structure

### Unit Tests

- **PluginTest** - Tests plugin registration, details, and filter mappings
- **Classes/ImageManipulationTest** - Tests ImageManipulation class comprehensively

### Integration Tests

- **PluginRegistrationTest** - Tests plugin registration within October CMS

## Test Coverage

### PluginTest (4 tests)
- Plugin extends PluginBase
- Plugin details contain required keys and correct author
- All 16 image manipulation filters are registered
- Filters are correctly mapped to ImageManipulation methods

### ImageManipulationTest (85 tests using datasets)

#### Null Path Handling
- All 16 methods return null when path is null

#### Non-Existent File Handling
- Manipulation methods return original path for missing files
- `base64`, `getWidth`, `getHeight` return null for missing files

#### Input Validation
- Invalid values throw `InvalidArgumentException`
- Boundary values are accepted
- Exception messages include parameter name

#### Default Values
- `pixelate` defaults to 50
- `base64` defaults to jpeg format with prefix

#### Return Types
- Manipulation methods return `?string`
- Dimension methods return `?int`

#### Configuration
- Default manipulations directory
- Default optimization timeout (120s)
- Optimize after manipulation enabled by default

#### Error Handling
- Invalid paths handled gracefully

#### URL Parsing
- Full URLs parsed correctly
- Paths without domain handled
- Null paths handled

### PluginRegistrationTest (1 test)
- Plugin registers and boots without errors

## Validation Ranges

| Method | Min | Max |
|--------|-----|-----|
| brightness | -100 | 100 |
| contrast | -100 | 100 |
| gamma | 0.1 | 9.99 |
| colorize (r,g,b) | -100 | 100 |
| blur | 0 | 100 |
| pixelate | 1 | 1000 |
| sharpen | 0 | 100 |
| quality | 0 | 100 |
