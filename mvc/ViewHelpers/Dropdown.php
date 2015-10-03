<?php

namespace DH\Mvc\ViewHelpers;


class Dropdown implements IView
{
    const TAG_NAME = 'select';
    private $options;
    private $attributes;
    private $selectedOption;

    public function __construct($name, $options = [])
    {
        $this->name = $name;
        $this->options = $options;
        array('value', 'text');
    }

    public function setSelectedOption($value)
    {
        $this->selectedOption = $value;
    }

    public function __get($name)
    {
        return $this->attributes[$name];
    }

    public function __set($name, $value)
    {
        $this->attributes[$name] = $value;
    }

    protected function getAttributesAsString()
    {
        $result = '';
        foreach ($this->attributes as $name => $value) {
            $result .= $name . ($value !== null ? '="' . $value . '" ' : '');
        }

        return trim($result);
    }

    public function render()
    {
        $output = '<' . self::TAG_NAME . ' ' . $this->getAttributesAsString() . '>';
        foreach ($this->options as $option) {

            $output .= PHP_EOL . '    <option value="'.$option['value'].'" ';
            if($this->selectedOption == $option['value']) {
                $output .= 'selected ';
            }

            $output .= '>' . $option['text'] . '</option>';
        }

        $output .= PHP_EOL . '</' . self::TAG_NAME . '>';
        return $output;
    }
}