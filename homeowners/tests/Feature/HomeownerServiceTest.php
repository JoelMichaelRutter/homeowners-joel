<?php

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\DataProvider;
use App\Contracts\HomeownerDriver;
use Tests\TestCase;

class HomeownerServiceTest extends TestCase
{
    #[DataProvider('homeOwnerProvider')]
    public function testThatHomeOwnerServiceHandlesOwnersAsExpected(array $ownerData, array $expectation): void
    {
        $homeownerService = app(HomeownerDriver::class);

        $result = $homeownerService->process($ownerData);

        $this->assertSame($expectation, $result);
    }

    public static function homeOwnerProvider(): array
    {
        return [
            'with single owner' => [
                'ownerData' => [
                    'Mr Joe Bloggs',
                ],
                'expectation' => [
                    [
                        'title' => 'Mr',
                        'first_name' => 'Joe',
                        'initial' => null,
                        'last_name' => 'Bloggs',
                    ]
                ]
            ],
            'with single owner using initial instead of first name' => [
                'ownerData' => [
                    'Mr J. Bloggs',
                ],
                'expectation' => [
                    [
                        'title' => 'Mr',
                        'first_name' => null,
                        'initial' => 'J',
                        'last_name' => 'Bloggs',
                    ]
                ]
            ],
            'with dual owners with shared surname and missing first name' => [
                'ownerData' => [
                    'Mr & Mrs Smith',
                ],
                'expectation' => [
                    [
                        'title' => 'Mr',
                        'first_name' => null,
                        'initial' => null,
                        'last_name' => 'Smith',
                    ],
                    [
                        'title' => 'Mrs',
                        'first_name' => null,
                        'initial' => null,
                        'last_name' => 'Smith',
                    ]
                ]
            ],
            'with dual owners with shared surname with first name available' => [
                'ownerData' => [
                    'Mr Gary Smith & Mrs Diane Smith',
                ],
                'expectation' => [
                    [
                        'title' => 'Mr',
                        'first_name' => 'Gary',
                        'initial' => null,
                        'last_name' => 'Smith',
                    ],
                    [
                        'title' => 'Mrs',
                        'first_name' => 'Diane',
                        'initial' => null,
                        'last_name' => 'Smith',
                    ]
                ]
            ],
            'with dual owners with unshared surname' => [
                'ownerData' => [
                    'Mr Tom Bombadil & Mr Gandalf Grey',
                ],
                'expectation' => [
                    [
                        'title' => 'Mr',
                        'first_name' => 'Tom',
                        'initial' => null,
                        'last_name' => 'Bombadil',
                    ],
                    [
                        'title' => 'Mr',
                        'first_name' => 'Gandalf',
                        'initial' => null,
                        'last_name' => 'Grey',
                    ]
                ]
            ],
            'with dual owners with unshared surname using initials instead of first name' => [
                'ownerData' => [
                    'Mr T Bombadil & Mr G Grey',
                ],
                'expectation' => [
                    [
                        'title' => 'Mr',
                        'first_name' => null,
                        'initial' => 'T',
                        'last_name' => 'Bombadil',
                    ],
                    [
                        'title' => 'Mr',
                        'first_name' => null,
                        'initial' => 'G',
                        'last_name' => 'Grey',
                    ]
                ]
            ]
        ];
    }
}
