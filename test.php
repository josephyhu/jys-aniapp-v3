<?php
function test($userId, $mediaId, $score) {
    $query = 'mutation ($score: Float) {
        SaveMediaListEntry(userId: $userId, mediaId: $mediaId, score $score) {
            score,
        }
    }';
    $variables = [
        "userId" => $userId,
        "mediaId" => $mediaId,
        "score" => $score,
    ];

    $http = new GuzzleHttp\Client;
    $response = $http->post('https://graphql.anilist.co', [
        'json' => [
            'query' => $query,
            'variables' => $variables,
        ]
    ]);
}

test(5391009, 9253, 9);