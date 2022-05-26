<?php
return [
    '@class' => 'Gantry\\Component\\File\\CompiledYamlFile',
    'filename' => '/home/c/cp42004/joomla_w8o2f/public_html/templates/g5_hydrogen/blueprints/styles/subfeature.yaml',
    'modified' => 1651774217,
    'data' => [
        'name' => 'Subfeature Colors',
        'description' => 'Subfeature colors for the Hydrogen theme',
        'type' => 'section',
        'form' => [
            'fields' => [
                'background' => [
                    'type' => 'input.colorpicker',
                    'label' => 'Background',
                    'default' => '#f0f0f0'
                ],
                'text-color' => [
                    'type' => 'input.colorpicker',
                    'label' => 'Text',
                    'default' => '#666666'
                ]
            ]
        ]
    ]
];
