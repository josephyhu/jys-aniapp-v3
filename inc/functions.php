<?php
// Require autoload.
require 'vendor/autoload.php';

// Get the access token.
function get_token($code) {
    $http = new GuzzleHttp\Client;

    $response = $http->post('https://anilist.co/api/v2/oauth/token', [
        'form_params' => [
            'grant_type' => 'authorization_code',
            'client_id' => '8687',
            'client_secret' => 'KqGJr2JqIi8wdCq3lXdy4VsGlYK8yzeDElU7hW6a',
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

// Enable search functionality.
function search_media($type, $search) {
    $query = 'query ($type: MediaType, $search: String) {
        Page {
            media (type: $type, search: $search, sort: SCORE_DESC) {
                id,
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
            genre,
            source,
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
            genre,
            averageScore,
            popularity,
            source,
            countryOfOrigin,
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
