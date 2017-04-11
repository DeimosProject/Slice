<?php

include_once dirname(__DIR__) . '/vendor/autoload.php';

$helper  = new \Deimos\Helper\Helper();

$slice = new \Deimos\Slice\Slice($helper, [
    'dir' => [
        'images' => [
            'my'         => ['image.jpg'],
            'collection' => [
                '2017' => [
                    'praga' => '%collection.2017.praga%'
                ]
            ]
        ]
    ]
], [
    'collection' => [
        '2017' => [
            'praga' => ['image1.jpg', 'image2.jpg', 'image3.jpg']
        ]
    ]
]);

var_dump($slice);

$slice['dir.images.collection.2016'] = [
    'praga' => ['image13.jpg', 'image4.jpg']
];

var_dump($slice['dir.images.collection']);

unset($slice['dir.images.collection.2016']);

var_dump($slice->getSlice('dir.images'));
var_dump($slice->getData('dir.images'));

var_dump(isset($slice['dir.images.collection.2016']));
var_dump(isset($slice['dir.images.collection.2017']));

foreach ($slice->getSlice('dir.images') as $key => $slouse)
{
    var_dump([$key => $slouse]);
}
