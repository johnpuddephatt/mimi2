<?php

// See link below for sample sanitizer config.
// https://github.com/advoor/nova-editor-js/blob/70f48f6d7e30396c03169635abb253cb47dad4f8/src/config/nova-editor-js.php

return [
  'tools' => [

    'header' => [
      'text' => [
        'type' => 'string',
        'required' => true,
        'allowedTags' => 'b,i,a[href]',
      ],
      'level' => [
        'type' => 'int',
        'canBeOnly' => [
          0 => 2,
          1 => 3,
          2 => 4,
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
            'allowedTags' => 'i,b,u',
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
            'type' => 'string'
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
    'table' => [
      'content' => [
        'type' => 'array',
        'data' => [
            '-' => [
                'type' => 'array',
                'data' => [
                    '-' => [
                        'type' => 'string',
                        'allowedTags' => 'i,b,u,br,a'
                    ]
                ],
            ]
        ],
      ]
    ],
  ]
];
