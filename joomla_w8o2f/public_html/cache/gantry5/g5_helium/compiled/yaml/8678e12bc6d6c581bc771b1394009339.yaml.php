<?php
return [
    '@class' => 'Gantry\\Component\\File\\CompiledYamlFile',
    'filename' => '/home/c/cp42004/joomla_w8o2f/public_html/templates/g5_helium/blueprints/styles/link.yaml',
    'modified' => 1651774202,
    'data' => [
        'name' => 'Link Colors',
        'description' => 'Link colors for the Helium theme',
        'type' => 'core',
        'form' => [
            'fields' => [
                'regular' => [
                    'type' => 'input.colorpicker',
                    'label' => 'Link Color',
                    'default' => '#4db2b3'
                ],
                'hover' => [
                    'type' => 'input.colorpicker',
                    'label' => 'Hover Color',
                    'default' => '#424753'
                ]
            ]
        ]
    ]
];
