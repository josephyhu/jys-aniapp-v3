<?php
function test($userId, $mediaId, $score) {
    $query = 'mutation ($userId: Int, $mediaId: Int, $score: Float) {
        SaveMediaListEntry(userId: $userId, mediaId: $mediaId, score $score) {
            userId,
            mediaId,
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
    $arr = json_decode($response->getBody()->getContents(), true);
    return $arr['data']['SaveMediaListEntry'];
}

$data = test(5391009, 9253, 9);
var_dump($data);