<?php
return [
    '@class' => 'Gantry\\Component\\File\\CompiledYamlFile',
    'filename' => '/home/c/cp42004/joomla_w8o2f/public_html/templates/g5_helium/blueprints/styles/font.yaml',
    'modified' => 1651774202,
    'data' => [
        'name' => 'Font Families',
        'description' => 'Font families for the Helium theme',
        'type' => 'core',
        'form' => [
            'fields' => [
                'family-default' => [
                    'type' => 'input.fonts',
                    'label' => 'Body Font',
                    'default' => 'Raleway, Helvetica, Tahoma, Geneva, Arial, sans-serif'
                ],
                'family-title' => [
                    'type' => 'input.fonts',
                    'label' => 'Title Font',
                    'default' => 'Lato, Helvetica, Tahoma, Geneva, Arial, sans-serif'
                ]
            ]
        ]
    ]
];
