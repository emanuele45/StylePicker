# Style Picker library

A library for ElkArte addons that provides some basic tools to let people pick CSS styles (borders, colors, sizes, etc.) from the web interface.

Used for example in the addon [Colored Names](http://addons.elkarte.net/2014/07/Colored%20Names/)

# Usage

```php
$picker = new StylePicker();
loadTemplate('StylePicker');

$picker->addStyle(array(
	'padding' => array(
		'value' => '',
		'type' => 'text',
		'validate' => function($val) {
			if (preg_match('~^((\d+)|(\d+\.\d+))([a-z]{1,3}|%)$~', $val, $matches))
				return $val;
			else
				return '';
		},
	),
));
```

Rendering:
```php
global $context;

$context['style_picker_elements'] = $picker->getAttributes();

template_profile_style_picker();
```

Validation:
```php
require_once(SUBSDIR . '/DataValidator.class.php');

$validator = new Data_Validator();
$result = $picker->validate($_POST, $validator);

// Returns an array of valid styles or empty if no valid styles have been found
```


[![Build Status](https://travis-ci.org/emanuele45/StylePicker.svg)](https://travis-ci.org/emanuele45/StylePicker)