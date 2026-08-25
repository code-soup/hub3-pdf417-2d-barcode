HUB3 PDF417 2D Barcode PHP
==========================

[![License](https://img.shields.io/packagist/l/codesoup/hub3-pdf417-2d-barcode.svg?style=flat-square)](https://packagist.org/packages/codesoup/hub3-pdf417-2d-barcode)

Modern PHP 8.2+ library for generating **PDF417 2D barcodes**, specifically optimized for Croatian **HUB-3 banking payment slips**.

## About HUB-3

**HUB-3** is a payment slip format used by Croatian banks and published by the Croatian Banking Association. It defines a standard way to encode payment data as a 2D barcode in PDF417 format. This barcode is printed on HUB-3 payment slips and can be scanned by banking applications for quick payment processing.

Learn more about the HUB-3 standard at [hub3.bigfish.software](https://hub3.bigfish.software/).

## About This Library

This is a modernized and actively maintained fork of Ivan Habunek's excellent PDF417 barcode library. The original project ([bigfish/pdf417](https://github.com/ihabunek/pdf417-php)) was archived in 2017. This fork brings the library up to modern PHP standards with:

- **PHP 8.2+** compatibility with strict type safety
- **Modern dependencies** (intervention/image 3.x, PHPUnit 11)
- **Type safety** (declare strict types, type hints, return types, typed properties)
- **Comprehensive testing** (49 tests, 3119 assertions)
- **Continuous maintenance** and bug fixes

**Original Author:** [Ivan Habunek](https://github.com/ihabunek) (@ihabunek)
**Maintainer:** CodeSoup

For a Python implementation, check out [ihabunek/pdf417-py](https://github.com/ihabunek/pdf417-py/).

Requirements
------------

* **PHP** >= 8.2
* **PHP extensions:** bcmath, gd

Installation
------------

Install via Composer:

```bash
composer require codesoup/hub3-pdf417-2d-barcode
```

Usage overview
--------------

```php
require 'vendor/autoload.php';

use BigFish\PDF417\PDF417;
use BigFish\PDF417\Renderers\ImageRenderer;
use BigFish\PDF417\Renderers\SvgRenderer;

// Text to be encoded into the barcode
$text = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur
imperdiet sit amet magna faucibus aliquet. Aenean in velit in mauris imperdiet
scelerisque. Maecenas a auctor erat.';

// Encode the data, returns a BarcodeData object
$pdf417 = new PDF417();
$data = $pdf417->encode($text);

// Create a PNG image
$renderer = new ImageRenderer([
    'format' => 'png'
]);

// Returns encoded image as string
$imageString = $renderer->render($data);

// Save to file
file_put_contents('barcode.png', $imageString);

// Or output directly
header('Content-Type: image/png');
echo $imageString;

// Create an SVG representation
$renderer = new SvgRenderer([
    'color' => 'black',
]);

$svg = $renderer->render($data);
```

ImageRenderer
-------------

Renders the barcode to an image using [Intervention Image 3.x](https://image.intervention.io/v3)

Render function returns an encoded image as a **string**.

#### Options

Option  | Default | Description
------- | ------- | -----------
format  | png     | Output format, one of: `jpg`, `png`, `gif`, `tif`, `bmp`, `data-url`
quality | 90      | Jpeg encode quality (1-10)
scale   | 3       | Scale of barcode elements (1-20)
ratio   | 3       | Height to width ration of barcode elements (1-10)
padding | 20      | Padding in pixels (0-50)
color   | #000000 | Foreground color as a hex code
bgColor | #ffffff | Background color as a hex code

#### Examples

```php
$pdf417 = new PDF417();
$data = $pdf417->encode("My hovercraft is full of eels");

// Create a PNG image, red on green background, extra big
$renderer = new ImageRenderer([
    'format' => 'png',
    'color' => '#FF0000',
    'bgColor' => '#00FF00',
    'scale' => 10,
]);

$imageString = $renderer->render($data);
file_put_contents('hovercraft.png', $imageString);
```

The `data-url` format is not like the others, it returns a base64 encoded PNG
image which can be used in an image `src` or in CSS. When you create an image
using this format:

```php
$pdf417 = new PDF417();
$data = $pdf417->encode('My nipples explode with delight');

$renderer = new ImageRenderer([
    'format' => 'data-url'
]);
$img = $renderer->render($data);
```

You can use it directly in your HTML code:

```php
echo '<img src="' . $img . '" />';
```

This will be rendered as:
```html
<img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAA.... " />
```

## Use Cases

This library is perfect for:

- **Croatian banking applications** - Generate HUB-3 payment slip barcodes
- **Payment processing systems** - Encode payment data for bank processing
- **Invoice generation** - Add 2D barcodes to invoices for Croatian market
- **General PDF417 needs** - Any application requiring PDF417 2D barcodes

## Migration from bigfish/pdf417

If you're migrating from the original library, see [UPGRADE.md](UPGRADE.md) for details.

Main changes:
- ImageRenderer now returns a **string** (not an Image object)
- Use `file_put_contents()` instead of `$image->save()`
- intervention/image 3.x requires PHP 8.1+

## Credits

**Original Implementation:** [Ivan Habunek](https://github.com/ihabunek) ([@ihabunek](https://github.com/ihabunek))
**PHP 8.2+ Modernization & HUB-3 Optimization:** CodeSoup

**Original Library:**
- Repository: https://github.com/ihabunek/pdf417-php
- Website: https://hub3.bigfish.software/
- Author's Website: https://bigfish.software/
- Author's GitHub: https://github.com/ihabunek

Resources that helped the original implementation:
* http://grandzebu.net/informatique/codbar-en/pdf417.htm
* http://www.idautomation.com/barcode-faq/2d/pdf417/
* HUB-3 standard by Croatian Banking Association

Uses [Intervention Image](https://image.intervention.io/) for image rendering.

## License

MIT License - see [LICENSE.md](LICENSE.md)
