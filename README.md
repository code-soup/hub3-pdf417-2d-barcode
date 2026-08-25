PDF 417 Barcode Generator
=========================

[![License](https://img.shields.io/packagist/l/codesoup/pdf417.svg?style=flat-square)](https://packagist.org/packages/codesoup/pdf417)

Modern PHP 8.2+ fork of the archived [bigfish/pdf417](https://github.com/ihabunek/pdf417-php) library.

## About This Fork

This is a modernized and actively maintained fork of Ivan Habunek's excellent PDF417 barcode library. The original project was archived in 2017. This fork brings the library up to modern PHP standards with:

- **PHP 8.2+** compatibility
- **Modern dependencies** (intervention/image 3.x, PHPUnit 11)
- **Type safety** (strict types, type hints, return types)
- **Modern PHP features** (enums, match expressions, property promotion)
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
composer require codesoup/pdf417
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

## Migration from bigfish/pdf417

If you're migrating from the original library, see [UPGRADE.md](UPGRADE.md) for details.

Main changes:
- ImageRenderer now returns a **string** (not an Image object)
- Use `file_put_contents()` instead of `$image->save()`
- intervention/image 3.x requires PHP 8.1+

## Credits

**Original Implementation:** [Ivan Habunek](https://github.com/ihabunek) (bigfish/pdf417)

Without these resources, the original implementation would have been much harder:
* http://grandzebu.net/informatique/codbar-en/pdf417.htm
* http://www.idautomation.com/barcode-faq/2d/pdf417/

## License

MIT License - see [LICENSE.md](LICENSE.md)
