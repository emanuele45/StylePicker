<?php

/**
 * Style Picker library
 *
 * @author  emanuele
 * @license BSD http://opensource.org/licenses/BSD-3-Clause
 *
 * @version 0.0.3
 */

if (!defined('ELK'))
	die('No access...');

class StylePicker
{
	protected $knownStyles = array();
	protected $prefix_dir = '';

	public function __construct($directory = '')
	{
		$this->prefix_dir = $directory != '' ? $directory . '/' : '';

		$this->knownStyles = array(
			'color' => array(
				'value' => '',
				'type' => 'color',
				'validate' => 'color',
			),
			'font-size' => array(
				'value' => '',
				'type' => 'select',
				'values' => array('', '4px', '6px', '8px', '10px', '12px', '14px'),
			),
			'text-shadow' => array(
				'value' => '',
				'type' => 'text',
				'validate' => function($val) {
					return $this->validateShadows($val);
				},
			),
			'box-shadow' => array(
				'value' => '',
				'type' => 'text',
				'validate' => function($val) {
					return $this->validateShadows($val);
				},
			),
			'padding' => array(
				'value' => '',
				'type' => 'text',
				'validate' => function($val) {
					return $this->validateSize($val, 1, 4);
				},
			),
			'margin' => array(
				'value' => '',
				'type' => 'text',
				'validate' => function($val) {
					return $this->validateSize($val, 1, 4);
				},
			),
			'background' => array(
				'value' => '',
				'type' => 'color',
				'validate' => 'color',
			),
			'border-style' => array(
				'value' => '',
				'type' => 'select',
				'values' => array('none', 'hidden', 'dotted', 'dashed', 'solid', 'double', 'groove', 'ridge', 'inset', 'outset', 'initial', 'inherit'),
			),
			'border-width' => array(
				'value' => '',
				'type' => 'text',
				'validate' => function($val) {
					return $this->validateSize($val);
				},
			),
			'border-color' => array(
				'value' => '',
				'type' => 'color',
				'validate' => 'color',
			),
			'border-radius' => array(
				'value' => '',
				'type' => 'text',
				// This validates something like:
				//   1px [2px [3px [4px]]]
				// but not border-radius with the "/".
				'validate' => function($val) {
					if ($this->validateSize($val, 1, 4))
						return $val;
					else
						return '';
				},
			),
		);
	}

	// text-shadow: h-shadow v-shadow blur-radius color|none|initial|inherit;
	// text-shadow: 0 0 0 transparent, 0 1px 0 #6ef3ff;
	protected function validateShadows($string)
	{
		$multi_shadows = explode(',', $string);
		$valid = true;

		foreach ($multi_shadows as $shadow)
		{
			$pieces = array_map('trim', explode(' ', $shadow));
			$valid = $valid && count($pieces) === 4;

			if ($valid)
			{
				$valid = $valid && $this->validateSize(array($pieces[0], $pieces[1], $pieces[2]), 3, 3) !== '';

				$validator->validation_rules(array('text-shadow' => 'valid_color'));
				$valid = $valid && $validator->validate(array('text-shadow' => $pieces[3]));
			}
		}

		if ($valid)
			return $string;
		else
			return '';
	}

	protected function validateSize($string, $min = 1, $max = 1)
	{
		$sizes = explode(' ', $string);
		$valid = count($sizes) >= $min && count($sizes) <= $max;

		foreach ($sizes as $size)
			$valid = $valid && preg_match('~^((\d+)|(\d+\.\d+))(em|ex|px|%){0,1}$~', $string, $matches);

		if ($valid)
			return $string;
		else
			return '';
	}

	public function addStyle($name, $data)
	{
		$this->knownStyles[$name] = $data;
	}

	protected function known_style_attributes()
	{
		return $this->knownStyles;
	}

	public function getAttributes()
	{
		global $context, $txt;

		loadLanguage($this->prefix_dir . 'StylePicker');
		loadTemplate($this->prefix_dir . 'StylePicker');

		$return = array();
		foreach ($this->known_style_attributes() as $name => $data)
		{
			$return[$name] = array(
				'value' => $data['value'],
				'type' => $data['type'],
				'values' => isset($data['values']) ? $data['values'] : array()
			);
		}

		return $return;
	}

	public function validate($values, $validator)
	{
		$styles = array();

		foreach ($this->known_style_attributes() as $name => $data)
		{
			$post = isset($values['style_picker_vals'][$name]) ? trim($values['style_picker_vals'][$name]) : '';
			if ($post !== '')
			{
				switch ($data['type'])
				{
					case 'select':
						if (isset($data['values'][$post]))
							$styles[$name] = $data['values'][$post];
						break;
					case 'color':
						if (empty($values['style_picker_vals']['default_' . $name]))
						{
							$validator->validation_rules(array($name => 'valid_color'));
							if ($validator->validate(array($name => $post)))
								$styles[$name] = $post;
						}
						break;
					case 'text':
					default:
						if (isset($data['validate']))
							$styles[$name] = $data['validate']($post, $validator);
						else
							$styles[$name] = Util::htmlspecialchars($post, ENT_QUOTES);
						break;
				}
			}
		}

		return $styles;
	}
}