<?php

/**
 * Style Picker library
 *
 * @author  emanuele
 * @license BSD http://opensource.org/licenses/BSD-3-Clause
 *
 * @version 0.0.3
 */

class StylePicker
{
	protected $knownStyles = array();

	public function __construct()
	{
		$this->knownStyles = array(
			'color' => array(
				'value' => '',
				'type' => 'color',
				'validate' => 'color',
			),
			'background' => array(
				'value' => '',
				'type' => 'color',
				'validate' => 'color',
			),
			'font-size' => array(
				'value' => '',
				'type' => 'select',
				'values' => array('', '4px', '6px', '8px', '10px', '12px', '14px'),
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
					if (preg_match('~^((\d+)|(\d+\.\d+))([a-z]{1,3}|%)$~', $val, $matches))
						return $val;
					else
						return '';
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
				'validate' => function($val) {
					if (preg_match('~^((((\d+)|(\d+\.\d+))([a-z]{1,3}|%)\s){0,3}((\d+)|(\d+\.\d+))([a-z]{1,3}|%))(\s*/\s*(((\d+)|(\d+\.\d+))([a-z]{1,3}|%) ){0,3}((\d+)|(\d+\.\d+))([a-z]{1,3}|%)){0,1}$~', $val, $matches))
						return $val;
					else
						return '';
				},
			),
		);
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

		loadLanguage('StylePicker');
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
							$styles[$name] = $data['validate']($post);
						else
							$styles[$name] = Util::htmlspecialchars($post, ENT_QUOTES);
						break;
				}
			}
		}

		return $styles;
	}
}