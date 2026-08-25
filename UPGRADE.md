# Upgrade Guide

## From bigfish/pdf417 to codesoup/pdf417

This guide helps you migrate from the archived `bigfish/pdf417` library to this modernized fork.

## Breaking Changes

### 1. PHP Version Requirement

**Before:**
```json
"php": ">=5.4"
```

**After:**
```json
"php": "^8.2"
```

**Action:** Ensure your environment runs PHP 8.2 or higher.

---

### 2. ImageRenderer Return Type

**Before (bigfish/pdf417 with intervention/image 2.x):**
```php
$renderer = new ImageRenderer(['format' => 'png']);
$image = $renderer->render($data);
$image->save('barcode.png');  // Image object
```

**After (codesoup/pdf417 with intervention/image 3.x):**
```php
$renderer = new ImageRenderer(['format' => 'png']);
$imageString = $renderer->render($data);
file_put_contents('barcode.png', $imageString);  // String
```

**Why:** intervention/image 3.x changed its API. The `render()` method now returns an encoded image string instead of an Image object.

---

### 3. Composer Package Name

**Before:**
```bash
composer require bigfish/pdf417
```

**After:**
```bash
composer require codesoup/pdf417
```

---

## Non-Breaking Changes

### Namespaces & Classes
✅ **No changes** - All class names and namespaces remain the same:
```php
use BigFish\PDF417\PDF417;
use BigFish\PDF417\Renderers\ImageRenderer;
use BigFish\PDF417\Renderers\SvgRenderer;
```

### Core Functionality
✅ **No changes** - Encoding logic remains identical:
```php
$pdf417 = new PDF417();
$data = $pdf417->encode($text);
```

### SvgRenderer & JsonRenderer
✅ **No changes** - These renderers work exactly the same way.

---

## Step-by-Step Migration

### Step 1: Update composer.json

```json
{
    "require": {
        "codesoup/pdf417": "^1.0"
    }
}
```

### Step 2: Run composer update

```bash
composer update codesoup/pdf417
```

### Step 3: Update ImageRenderer Usage

Find all occurrences of:
```php
$image = $renderer->render($data);
$image->save('path/to/file.png');
```

Replace with:
```php
$imageString = $renderer->render($data);
file_put_contents('path/to/file.png', $imageString);
```

Or for direct output:
```php
$imageString = $renderer->render($data);
header('Content-Type: image/png');
echo $imageString;
```

### Step 4: Test Your Application

Run your test suite to ensure everything works correctly.

---

## New Features in This Fork

- **Type safety:** All methods have type hints and return types
- **Strict types:** `declare(strict_types=1)` enabled throughout
- **Modern PHP:** Uses enums, match expressions, property promotion
- **Better tooling:** PHPStan for static analysis
- **Updated dependencies:** intervention/image 3.x, PHPUnit 11

---

## Need Help?

Open an issue on GitHub if you encounter any migration problems.
