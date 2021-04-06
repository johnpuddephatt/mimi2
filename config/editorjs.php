<?php

// See link below for sample sanitizer config.
// https://github.com/advoor/nova-editor-js/blob/70f48f6d7e30396c03169635abb253cb47dad4f8/src/config/nova-editor-js.php

return [
  'tools' => [
    'embed' => [
      'service' => 'string',
      'source' => 'string',
      'embed' => 'string',
      'width' => 'integer',
      'height' => 'integer',
      'caption' => 'string'
    ],
    'header' => [
      'text' => [
        'type' => 'string',
        'required' => true,
        'allowedTags' => '*',
      ],
      'level' => [
        'type' => 'int',
        'canBeOnly' => [
          0 => 4,
        ],
      ],
    ],
    'paragraph' => [
      'text' => [
        'type' => 'string',
        'allowedTags' => '*',
      ],
    ],
    'pairedHeading' => [
      'heading' => [
        'type' => 'string',
      ],
      'subheading' => [
        'type' => 'string',
      ],
    ],
    'list' => [
      'style' => [
        'type' => 'string',
        'canBeOnly' =>
          [
            0 => 'ordered',
            1 => 'unordered',
          ],
      ],
      'items' => [
        'type' => 'array',
        'data' => [
          '-' => [
            'type' => 'string',
            'allowedTags' => '*',
          ],
        ],
      ],
    ],

    'video' => [
      'file' => [
        'type' => 'array',
        'data' => [
          'url' => [
            'type' => 'string',
          ],
          'playlist' => [
            'type' => 'string',
          ]
        ]
      ],
      'caption' => [
        'type' => 'string'
      ]
    ],

    'audio' => [
      'file' => [
        'type' => 'array',
        'data' => [
          'url' => [
            'type' => 'string',
          ],
          'name' => [
            'type' => 'string',
          ],
          'extension' => [
            'type' => 'string',
          ],
          'size' => [
            'type' => 'integer',
          ],
        ]
      ],
      'title' => [
        'type' => 'string'
      ]
    ],

    'image' => [
        'file' => [
            'type' => 'array',
            'data' => [
                'url' => [
                    'type' => 'string',
                ],
                'thumbnails' => [
                    'type' => 'array',
                    'required' => false,
                    'data' => [
                        '-' => [
                            'type' => 'string',
                        ]
                    ],
                ]
            ],
        ],
        'caption' => [
            'type' => 'string',
            'allowedTags' => '*',
        ],
        'withBorder' => [
            'type' => 'boolean'
        ],
        'withBackground' => [
            'type' => 'boolean'
        ],
        'stretched' => [
            'type' => 'boolean'
        ]
    ],
    'warning' => [
      'title' => [
        'type' => 'string'
      ],
      'message' => [
        'type' => 'string'
      ]
    ],
    'delimiter' => [
    ],
    'table' => [
      'content' => [
        'type' => 'array',
        'data' => [
            '-' => [
                'type' => 'array',
                'data' => [
                    '-' => [
                        'type' => 'string',
                        'allowedTags' => '*',
                    ]
                ],
            ]
        ],
      ]
    ],
  ]
];
