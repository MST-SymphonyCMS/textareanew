<?php

class fieldTextareaNew extends fieldTextarea
{
    private $formatters;

    // init

    public function __construct()
    {
        parent::__construct();

        $this->_name = __('Textarea (New)');
    }

    // display settings

    public function displaySettingsPanel(XMLElement &$wrapper, $errors = null)
    {
        // get field method

        $fieldclass = new ReflectionMethod('Field', 'displaySettingsPanel');

            $fieldclass->invoke($this, $wrapper, $errors);

        // textarea size

        $input = Widget::Input('fields[' . $this->get('sortorder') .'][size]', (string)$this->get('size'));
        $label = Widget::Label(__('Number of default rows'), $input, 'column');

        $wrapper->appendChild($label);

        // text formatters

        if ($formatters = TextformatterManager::listAll()) {

            $input = Widget::Input('fields[' . $this->get('sortorder') .'][formatter]', (string)$this->get('formatter'));

                $input->setAttribute('readonly', 'true');

            $label = Widget::Label(__('Text Formatter'), $input, 'column');

                $label->appendChild(new XMLElement('i', __('Optional')));

            $wrapper->appendChild($label);

            // options

            $options = new XMLElement('ul', null, array(

                'class'            => 'tags',
                'data-interactive' => 'data-interactive'
            ));

            foreach ($formatters as $formatter) {

                $options->appendChild(new XMLElement('li', $formatter['name'], array(

                    'class' => $formatter['handle']
                )));
            }

            $wrapper->appendChild($options);
        }

        // requirements and table display

        $this->appendStatusFooter($wrapper);
    }

    // check data

    public function checkPostFieldData($data, &$message, $entry_id = null)
    {
        // check requirement

        if (!$data && $this->get('required') === 'yes') {

            $message = __('‘%s’ is a required field.', array($this->get('label')));

            return self::__MISSING_FIELDS__;
        }

        // shiny

        return self::__OK__;
    }

    // process data

    public function processRawFieldData($data, &$status, &$message = null, $simulate = false, $entry_id = null)
    {
        // set status

        $status = self::__OK__;

        // return if empty

        if (!$data) {

            return array();
        }

        // return raw data

        return array(

            'value' => $data
        );
    }

    public function appendFormattedElement(XMLElement &$wrapper, $data, $encode = false, $mode = null, $entry_id = null)
    {
        if ($mode === 'formatted') {

            $value = $this->runFormatters($data['value']);

            if ($encode) {

                $value = General::wrapInCDATA($value);
            }

        } else {

            $value = General::wrapInCDATA($data['value']);
        }

        $attributes = $mode ? array('mode' => $mode) : array();

        $wrapper->appendChild(

            new XMLElement($this->get('element_name'), $value, $attributes)
        );
    }

    // run formatters

    private function runFormatters($data)
    {
        foreach ($this->getFormatters() as $formatter) {

            $data = $formatter->run($data);
        }

        // todo: add caching

        return $data;
    }

    // get formatters

    private function getFormatters()
    {
        if (!$this->formatters) {

            if ($this->get('formatter')) {

                $formatters = explode(',', $this->get('formatter'));
            }

            if (!is_array($formatters)) {

                $formatters = array();
            }

            foreach ($formatters as $key => $formatter) {

                $formatters[$key] = TextformatterManager::create(trim($formatter));
            }

            $this->formatters = $formatters;
        }

        return $this->formatters;
    }
}
