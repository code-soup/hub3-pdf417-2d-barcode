PDF417 Changelog
================

1.0.0 (2026-08-25) - CodeSoup Fork
-----------------------------------

**Major modernization release - PHP 8.2+ fork of archived bigfish/pdf417**

### Breaking Changes
* **PHP 8.2+ required** (was PHP >= 5.4)
* **intervention/image 3.x** - ImageRenderer now returns string instead of Image object
  * Old: `$img->save('file.png')`
  * New: `file_put_contents('file.png', $imageString)`
* **PHPUnit 11** for testing (was PHPUnit 4/5)
* **Package name:** `codesoup/pdf417` (was `bigfish/pdf417`)

### New Features
* **Type safety:** All methods have strict types, type hints, and return types
* **declare(strict_types=1)** enabled throughout codebase
* **Property type declarations** for all class properties
* **Modern PHP 8.2 features:**
  - Typed properties
  - Union types where applicable
  - Modern type casts (`(int)`, `(bool)` vs old style)
* **Better documentation:** PHPDoc annotations with psalm/phpstan types
* **UPGRADE.md** guide for migration from bigfish/pdf417

### Improvements
* Updated README with current examples and requirements
* Removed obsolete .travis.yml CI configuration
* Fixed code style issues (foreach spacing, switch spacing)
* Updated PHPUnit test syntax (expectException instead of @expectedException)
* Modernized PHPUnit XML configuration
* Fixed BarcodeData pixel grid conversion bug (boolean casting)
* Fixed bcmath strict type compatibility (all arguments now strings)
* Fixed DOMElement setAttribute strict type requirements
* All 49 tests passing with 3119 assertions

### Credits
This fork maintains full backward compatibility (except breaking changes listed above) with the original bigfish/pdf417 library by Ivan Habunek. Special thanks to Ivan for creating this excellent barcode library.

---

## Original bigfish/pdf417 Changelog

0.3.0 (2017-07-29)
------------------

* Fixed a bug in the character table where `g` would be decoded as `"` if
  preceeded by punctuation (#8) thanks @wotan192

0.2.0 (2016-05-05)
------------------

* Added support for new formats in ImageRenderer (tif, bmp, data-url) (#1)
* Fixed bug when encoded text started with numbers or bytes (#2)

0.1.2 (2014-12-26)
------------------

* Fixed an edge case in calculating Reed Solomon factors

0.1.1 (2014-12-26)
------------------

* Added validation of options in renderers.
* Fixed a bug in ImageRenderer where padding was not in bgColor, but white.
* Upgraded Intrevention/Image to v2.
* Added 'quality' option to ImageRenderer (used for JPG only).

0.1.0 (2014-12-24)
------------------

* Initial release
