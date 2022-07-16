<?php
// Require autoload.
require 'vendor/autoload.php';

// Get the access token.
function get_token($code) {
    $http = new GuzzleHttp\Client;

    $response = $http->post('https://anilist.co/api/v2/oauth/token', [
        'form_params' => [
            'grant_type' => 'authorization_code',
            'client_id' => getenv('CLIENT_ID'),
            'client_secret' => getenv('CLIENT_SECRET'),
            'redirect_uri' => 'https://jys-aniapp-v3.herokuapp.com', // http://example.com/callback
            'code' => $code, // The Authorization code received previously
        ],
        'headers' => [
            'Accept' => 'application/json'
        ]
    ]);

    return json_decode($response->getBody()->getContents())->access_token;
}

// Get current user id.
function get_userId($accessToken) {
    $query = '
        query {
            Viewer {
                id
            }
        }';

    $http = new GuzzleHttp\Client;
    $response = $http->request('POST', 'https://graphql.anilist.co', [
        'headers' => [
            'Authorization' => 'Bearer ' . $accessToken,
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ],
        'json' => [
            'query' => $query,
        ]
    ]);

    $arr = json_decode($response->getBody()->getContents(), true);
    return $arr['data']['Viewer']['id'];
}

// Get the current username.
function get_username($userId) {
    $query = '
        query ($id: Int) {
            User (id: $id) {
                name
            }
        }';
    $variables = [
        'id' => $userId,
    ];

    $http = new GuzzleHttp\Client;
    $response = $http->post('https://graphql.anilist.co', [
        'json' => [
            'query' => $query,
            'variables' => $variables,
        ]
    ]);

    $arr = json_decode($response->getBody()->getContents(), true);
    return $arr['data']['User']['name'];
}

// Get current user animelist.
function get_userAnimeList($userId, $status) {
    $query = '
    query ($userId: Int, $status: MediaListStatus) {
        MediaListCollection (userId: $userId, type: ANIME, status: $status, sort: SCORE_DESC) {
            lists {
                entries {
                    media {
                        id,
                        title {
                            romaji,
                        },
                        coverImage {
                            medium,
                        },
                    }
                }
            }
        }
    }';
    $variables = [
        'userId' => $userId,
        'status' => $status,
    ];

    $http = new GuzzleHttp\Client;
    $response = $http->post('https://graphql.anilist.co', [
        'json' => [
            'query' => $query,
            'variables' => $variables,
        ]
    ]);
    $arr = json_decode($response->getBody()->getContents(), true);
    return $arr['data']['MediaListCollection']['lists'][0]['entries'];
}

// Get current user mangalist.
function get_userMangaList($userId, $status) {
    $query = '
    query ($userId: Int, $status: MediaListStatus) {
        MediaListCollection (userId: $userId, type: MANGA, status: $status, sort: SCORE_DESC) {
            lists {
                entries {
                    media {
                        id,
                        title {
                            romaji,
                        },
                        coverImage {
                            medium,
                        },
                    }
                }
            }
        }
    }';
    $variables = [
        'userId' => $userId,
        'status' => $status,
    ];

    $http = new GuzzleHttp\Client;
    $response = $http->post('https://graphql.anilist.co', [
        'json' => [
            'query' => $query,
            'variables' => $variables,
        ]
    ]);
    $arr = json_decode($response->getBody()->getContents(), true);
    return $arr['data']['MediaListCollection']['lists'][0]['entries'];
}

// Get media based on type (anime/manga) and a search term.
function search_media($type, $search) {
    $query = 'query ($type: MediaType, $search: String) {
        Page {
            media (type: $type, search: $search, sort: SCORE_DESC) {
                id,
                title {
                    romaji,
                },
                coverImage {
                    medium,
                },
            }
        }
    }';

    $variables = [
        'type' => $type,
        'search' => $search,
    ];

    $http = new GuzzleHttp\Client;
    $response = $http->post('https://graphql.anilist.co', [
        'json' => [
            'query' => $query,
            'variables' => $variables,
        ]
    ]);
    $arr = json_decode($response->getBody()->getContents(), true);
    return $arr['data']['Page']['media'];
}

// Get specific anime details.
function get_animeDetails($id) {
    $query ='query ($id: Int) {
        Media (id: $id, type: ANIME) {
            title {
                english,
                romaji,
            },
            format,
            status,
            description,
            coverImage {
                large,
            },
            bannerImage,
            startDate {
                year,
                month,
                day,
            },
            endDate {
                year,
                month,
                day,
            },
            season,
            seasonYear,
            episodes,
            duration,
            genres,
            averageScore,
            meanScore,
            favourites,
            popularity,
            source,
            countryOfOrigin,
            siteUrl,
        }
    }';

    $variables = [
        'id' => $id,
    ];

    $http = new GuzzleHttp\Client;
    $response = $http->post('https://graphql.anilist.co', [
        'json' => [
            'query' => $query,
            'variables' => $variables,
        ]
    ]);
    $arr = json_decode($response->getBody()->getContents(), true);
    return $arr['data']['Media'];
}

// Get specific manga details.
function get_mangaDetails($id) {
    $query ='query ($id: Int) {
        Media (id: $id, type: MANGA) {
            title {
                english,
                romaji,
            },
            format,
            status,
            description,
            coverImage {
                large,
            },
            bannerImage,
            startDate {
                year,
                month,
                day,
            },
            endDate {
                year,
                month,
                day,
            },
            chapters,
            volumes,
            genres,
            averageScore,
            meanScore,
            favourites,
            popularity,
            source,
            countryOfOrigin,
            siteUrl,
        }
    }';

    $variables = [
        'id' => $id,
    ];

    $http = new GuzzleHttp\Client;
    $response = $http->post('https://graphql.anilist.co', [
        'json' => [
            'query' => $query,
            'variables' => $variables,
        ]
    ]);
    $arr = json_decode($response->getBody()->getContents(), true);
    return $arr['data']['Media'];
}

// Get specific anime details for the current user.
function get_userAnimeDetails($userId, $mediaId) {
    $query ='query ($userId: Int, $mediaId: Int) {
        MediaList(userId: $userId, mediaId: $mediaId, type: ANIME) {
            id,
            status,
            startedAt {
                year,
                month,
                day,
            },
            completedAt {
                year,
                month,
                day,
            },
            progress,
            score,
            repeat,
        }
    }';

    $variables = [
        'userId' => $userId,
        'mediaId' => $mediaId,
    ];

    $http = new GuzzleHttp\Client;
    $response = $http->post('https://graphql.anilist.co', [
        'json' => [
            'query' => $query,
            'variables' => $variables,
        ]
    ]);
    $arr = json_decode($response->getBody()->getContents(), true);
    return $arr['data']['MediaList'];
}

// Get specific manga details for the current user.
function get_userMangaDetails($userId, $mediaId) {
    $query ='query ($userId: Int, $mediaId: Int) {
        MediaList(userId: $userId, mediaId: $mediaId, type: MANGA) {
            id,
            status,
            startedAt {
                year,
                month,
                day,
            },
            completedAt {
                year,
                month,
                day,
            },
            progress,
            progressVolumes,
            score,
            repeat,
        }
    }';

    $variables = [
        'userId' => $userId,
        'mediaId' => $mediaId,
    ];

    $http = new GuzzleHttp\Client;
    $response = $http->post('https://graphql.anilist.co', [
        'json' => [
            'query' => $query,
            'variables' => $variables,
        ]
    ]);
    $arr = json_decode($response->getBody()->getContents(), true);
    return $arr['data']['MediaList'];
}

// Add anime to current user's list.
function add_anime($accessToken, $mediaId, $status, $startedAt, $completedAt, $score, $progress) {
    $query = 'mutation ($mediaId: Int, $status: MediaListStatus, $startedAt: FuzzyDateInput, $completedAt: FuzzyDateInput, $score: Float, $progress: Int) {
        SaveMediaListEntry (mediaId: $mediaId, status: $status, startedAt: $startedAt, completedAt: $completedAt, score: $score, progress: $progress) {
            id,
            status,
            startedAt {
                year,
                month,
                day,
            },
            completedAt {
                year,
                month,
                day,
            },
            score,
            progress,
        }
    }';

    $variables = [
        "mediaId" => $mediaId,
        "status" => $status,
        "startedAt" => [
            "year" => (!empty($startedAt['year'])) ? $startedAt['year'] : null,
            "month" => (!empty($startedAt['month'])) ? $startedAt['month'] : null,
            "day" => (!empty($startedAt['day'])) ? $startedAt['day'] : null,
        ],
        "completedAt" => [
            "year" => (!empty($completedAt['year'])) ? $completedAt['year'] : null,
            "month" => (!empty($completedAt['month'])) ? $completedAt['month'] : null,
            "day" => (!empty($completedAt['day'])) ? $completedAt['day'] : null,
        ],
        "score" => $score,
        "progress" => $progress,
    ];

    $http = new GuzzleHttp\Client;
    $response = $http->request('POST', 'https://graphql.anilist.co', [
        'headers' => [
            'Authorization' => 'Bearer ' . $accessToken,
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ],
        'json' => [
            'query' => $query,
            'variables' => $variables,
        ]
    ]);
    $arr = json_decode($response->getBody()->getContents(), true);
    return $arr['data']['SaveMediaListEntry']['id'];
}

// Add manga to current user's list.
function add_manga($accessToken, $mediaId, $status, $startedAt, $completedAt, $score, $progress, $progressVolumes) {
    $query = 'mutation ($mediaId: Int, $status: MediaListStatus, $startedAt: FuzzyDateInput, $completedAt: FuzzyDateInput, $score: Float, $progress: Int, $progressVolumes: Int) {
        SaveMediaListEntry (mediaId: $mediaId, status: $status, startedAt: $startedAt, completedAt: $completedAt, score: $score, progress: $progress, progressVolumes: $progressVolumes) {
            id,
            status,
            startedAt {
                year,
                month,
                day,
            },
            completedAt {
                year,
                month,
                day,
            },
            score,
            progress,
            progressVolumes,
        }
    }';

    $variables = [
        "mediaId" => $mediaId,
        "status" => $status,
        "startedAt" => [
            "year" => (!empty($startedAt['year'])) ? $startedAt['year'] : null,
            "month" => (!empty($startedAt['month'])) ? $startedAt['month'] : null,
            "day" => (!empty($startedAt['day'])) ? $startedAt['day'] : null,
        ],
        "completedAt" => [
            "year" => (!empty($completedAt['year'])) ? $completedAt['year'] : null,
            "month" => (!empty($completedAt['month'])) ? $completedAt['month'] : null,
            "day" => (!empty($completedAt['day'])) ? $completedAt['day'] : null,
        ],
        "score" => $score,
        "progress" => $progress,
        "progressVolumes" => $progressVolumes,
    ];

    $http = new GuzzleHttp\Client;
    $response = $http->request('POST', 'https://graphql.anilist.co', [
        'headers' => [
            'Authorization' => 'Bearer ' . $accessToken,
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ],
        'json' => [
            'query' => $query,
            'variables' => $variables,
        ]
    ]);
    $arr = json_decode($response->getBody()->getContents(), true);
    return $arr['data']['SaveMediaListEntry']['id'];
}

// Update anime on current user's list.
function update_anime($accessToken, $mediaId, $status, $startedAt, $completedAt, $score, $progress) {
    $query = 'mutation ($mediaId: Int, $status: MediaListStatus, $startedAt: FuzzyDateInput, $completedAt: FuzzyDateInput, $score: Float, $progress: Int) {
        SaveMediaListEntry(mediaId: $mediaId, status: $status, startedAt: $startedAt, completedAt: $completedAt, score: $score, progress: $progress) {
            id,
            status,
            startedAt {
                year,
                month,
                day,
            },
            completedAt {
                year,
                month,
                day,
            },
            score,
            progress,
        }
    }';
    $variables = [
        "mediaId" => $mediaId,
        "status" => $status,
        "startedAt" => [
            "year" => (!empty($startedAt['year'])) ? $startedAt['year'] : null,
            "month" => (!empty($startedAt['month'])) ? $startedAt['month'] : null,
            "day" => (!empty($startedAt['day'])) ? $startedAt['day'] : null,
        ],
        "completedAt" => [
            "year" => (!empty($completedAt['year'])) ? $completedAt['year'] : null,
            "month" => (!empty($completedAt['month'])) ? $completedAt['month'] : null,
            "day" => (!empty($completedAt['day'])) ? $completedAt['day'] : null,
        ],
        "score" => $score,
        "progress" => $progress,
    ];

    $http = new GuzzleHttp\Client;
    $response = $http->request('POST', 'https://graphql.anilist.co', [
        'headers' => [
            'Authorization' => 'Bearer ' . $accessToken,
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ],
        'json' => [
            'query' => $query,
            'variables' => $variables,
        ]
    ]);
    $arr = json_decode($response->getBody()->getContents(), true);
    return $arr['data']['SaveMediaListEntry'];
}

// Update manga on current user's list.
function update_manga($accessToken, $mediaId, $status, $startedAt, $completedAt, $score, $progress, $progressVolumes) {
    $query = 'mutation ($mediaId: Int, $status: MediaListStatus, $startedAt: FuzzyDateInput, $completedAt: FuzzyDateInput, $score: Float, $progress: Int, $progressVolumes: Int) {
        SaveMediaListEntry(mediaId: $mediaId, status: $status, startedAt: $startedAt, completedAt: $completedAt, score: $score, progress: $progress, progressVolumes: $progressVolumes) {
            id,
            status,
            startedAt {
                year,
                month,
                day,
            },
            completedAt {
                year,
                month,
                day,
            },
            score,
            progress,
        }
    }';
    $variables = [
        "mediaId" => $mediaId,
        "status" => $status,
        "startedAt" => [
            "year" => (!empty($startedAt['year'])) ? $startedAt['year'] : null,
            "month" => (!empty($startedAt['month'])) ? $startedAt['month'] : null,
            "day" => (!empty($startedAt['day'])) ? $startedAt['day'] : null,
        ],
        "completedAt" => [
            "year" => (!empty($completedAt['year'])) ? $completedAt['year'] : null,
            "month" => (!empty($completedAt['month'])) ? $completedAt['month'] : null,
            "day" => (!empty($completedAt['day'])) ? $completedAt['day'] : null,
        ],
        "score" => $score,
        "progress" => $progress,
        "progressVolumes" => $progressVolumes,
    ];

    $http = new GuzzleHttp\Client;
    $response = $http->request('POST', 'https://graphql.anilist.co', [
        'headers' => [
            'Authorization' => 'Bearer ' . $accessToken,
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ],
        'json' => [
            'query' => $query,
            'variables' => $variables,
        ]
    ]);
    $arr = json_decode($response->getBody()->getContents(), true);
    return $arr['data']['SaveMediaListEntry'];
}

// Get user stats.
function get_userStats($userId) {
    $query = '
        query ($id: Int) {
            User (id: $id) {
                avatar {
                    large,
                },
                bannerImage,
                about,
                name,
                statistics {
                    anime {
                        count,
                        meanScore,
                        standardDeviation,
                        minutesWatched,
                        episodesWatched,
                    }
                    manga {
                        count,
                        meanScore,
                        standardDeviation,
                        chaptersRead,
                        volumesRead,
                    },
                }
            }
        }
    ';
    $variables = [
        'id' => $userId,
    ];

    $http = new GuzzleHttp\Client;
    $response = $http->post('https://graphql.anilist.co', [
        'json' => [
            'query' => $query,
            'variables' => $variables,
        ]
    ]);

    $arr = json_decode($response->getBody()->getContents(), true);
    return $arr['data']['User'];
}

// Delete an anime from current user's list.
function delete_userAnime($accessToken, $id) {
    $query = 'mutation ($id: Int) {
        DeleteMediaListEntry(id: $id) {
            deleted
        }
    }';
    $variables = [
        "id" => $id
    ];

    $http = new GuzzleHttp\Client;
    $response = $http->request('POST', 'https://graphql.anilist.co', [
        'headers' => [
            'Authorization' => 'Bearer ' . $accessToken,
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ],
        'json' => [
            'query' => $query,
            'variables' => $variables,
        ]
    ]);
    $arr = json_decode($response->getBody()->getContents(), true);
    return $arr['data']['DeleteMediaListEntry'];
}

// Delete a manga from current user's list.
function delete_userManga($accessToken, $id) {
    $query = 'mutation ($id: Int) {
        DeleteMediaListEntry(id: $id) {
            deleted
        }
    }';
    $variables = [
        "id" => $id
    ];

    $http = new GuzzleHttp\Client;
    $response = $http->request('POST', 'https://graphql.anilist.co', [
        'headers' => [
            'Authorization' => 'Bearer ' . $accessToken,
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ],
        'json' => [
            'query' => $query,
            'variables' => $variables,
        ]
    ]);
    $arr = json_decode($response->getBody()->getContents(), true);
    return $arr['data']['DeleteMediaListEntry'];
}


// Get media related to the current media.
function get_relatedMedia($id) {
    $query = 'query ($id: Int) {
        Media (id: $id) {
            relations {
                edges {
                    relationType,
                    node {
                        id,
                        title {
                            romaji,
                        },
                        coverImage {
                            medium,
                        },
                        type,
                    }
                }
            }
        }
    }';

    $variables = [
        'id' => $id,
    ];

    $http = new GuzzleHttp\Client;
    $response = $http->post('https://graphql.anilist.co', [
        'json' => [
            'query' => $query,
            'variables' => $variables,
        ]
    ]);
    $arr = json_decode($response->getBody()->getContents(), true);
    return $arr['data']['Media']['relations']['edges'];
}

// Get characters for the current media.
function get_characters($id) {
    $query = 'query ($id: Int) {
        Media (id: $id) {
            characters (sort: ROLE) {
                edges {
                    role,
                    node {
                        id,
                        name {
                            userPreferred,
                        },
                        image {
                            medium,
                        }
                    }
                }
            }
        }
    }';

    $variables = [
        'id' => $id,
    ];

    $http = new GuzzleHttp\Client;
    $response = $http->post('https://graphql.anilist.co', [
        'json' => [
            'query' => $query,
            'variables' => $variables,
        ]
    ]);
    $arr = json_decode($response->getBody()->getContents(), true);
    return $arr['data']['Media']['characters']['edges'];
}

// Get character details.
function get_characterDetails($id) {
    $query ='query ($id: Int) {
        Character (id: $id) {
            name {
                userPreferred,
            },
            image {
                large,
            },
            description,
            dateOfBirth {
                year,
                month,
                day,
            },
            age,
            gender,
            siteUrl,
            favourites,
        }
    }';

    $variables = [
        'id' => $id,
    ];

    $http = new GuzzleHttp\Client;
    $response = $http->post('https://graphql.anilist.co', [
        'json' => [
            'query' => $query,
            'variables' => $variables,
        ]
    ]);
    $arr = json_decode($response->getBody()->getContents(), true);
    return $arr['data']['Character'];
}

// Get media that includes the current character.
function get_characterMedia($id) {
    $query = 'query ($id: Int) {
        Character (id: $id) {
            media {
                nodes {
                    id,
                    title {
                        romaji,
                    },
                    coverImage {
                        medium,
                    },
                    siteUrl,
                }
            }
        }
    }';

    $variables = [
        'id' => $id,
    ];

    $http = new GuzzleHttp\Client;
    $response = $http->post('https://graphql.anilist.co', [
        'json' => [
            'query' => $query,
            'variables' => $variables,
        ]
    ]);
    $arr = json_decode($response->getBody()->getContents(), true);
    return $arr['data']['Character']['media']['nodes'];
}

// Get staff for the current media.
function get_staff($id) {
    $query = 'query ($id: Int) {
        Media (id: $id) {
            staff (sort: ROLE) {
                edges {
                    role,
                    node {
                        id,
                        name {
                            userPreferred,
                        },
                        image {
                            medium,
                        }
                    }
                }
            }
        }
    }';

    $variables = [
        'id' => $id,
    ];

    $http = new GuzzleHttp\Client;
    $response = $http->post('https://graphql.anilist.co', [
        'json' => [
            'query' => $query,
            'variables' => $variables,
        ]
    ]);
    $arr = json_decode($response->getBody()->getContents(), true);
    return $arr['data']['Media']['staff']['edges'];
}

// Get staff details.
function get_staffDetails($id) {
    $query ='query ($id: Int) {
        Staff (id: $id) {
            name {
                userPreferred,
            },
            languageV2,
            image {
                large,
            },
            description,
            dateOfBirth {
                year,
                month,
                day,
            },
            dateOfDeath {
                year,
                month,
                day,
            },
            age,
            yearsActive,
            gender,
            siteUrl,
            favourites,
            primaryOccupations,
        }
    }';

    $variables = [
        'id' => $id,
    ];

    $http = new GuzzleHttp\Client;
    $response = $http->post('https://graphql.anilist.co', [
        'json' => [
            'query' => $query,
            'variables' => $variables,
        ]
    ]);
    $arr = json_decode($response->getBody()->getContents(), true);
    return $arr['data']['Staff'];
};

// Get media the current staff is involved in.
function get_staffMedia($id) {
    $query = 'query ($id: Int) {
        Staff (id: $id) {
            staffMedia {
                nodes {
                    id,
                    type,
                    title {
                        romaji,
                    },
                    coverImage {
                        medium,
                    },
                    siteUrl,
                }
            }
        }
    }';

    $variables = [
        'id' => $id,
    ];

    $http = new GuzzleHttp\Client;
    $response = $http->post('https://graphql.anilist.co', [
        'json' => [
            'query' => $query,
            'variables' => $variables,
        ]
    ]);
    $arr = json_decode($response->getBody()->getContents(), true);
    return $arr['data']['Staff']['staffMedia']['nodes'];
}

// Get characters voice by the current voice actor.
function get_staffCharacters($id) {
    $query = 'query ($id: Int) {
        Staff (id: $id) {
            characters (sort:ROLE) {
                nodes {
                    id,
                    name {
                        userPreferred,
                    },
                    image {
                        medium,
                    },
                }
            }
        }
    }';

    $variables = [
        'id' => $id,
    ];

    $http = new GuzzleHttp\Client;
    $response = $http->post('https://graphql.anilist.co', [
        'json' => [
            'query' => $query,
            'variables' => $variables,
        ]
    ]);
    $arr = json_decode($response->getBody()->getContents(), true);
    return $arr['data']['Staff']['characters']['nodes'];
};