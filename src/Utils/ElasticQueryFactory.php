<?php

namespace App\Utils;

class ElasticQueryFactory
{
  /**
   * @param int    $userId
   * @param string $search
   * @return array
   */
  public static function buildExpenseSearchQuery($userId, $search)
  {
    $query = [
      'bool' => [
        'must' => [
          [
            'nested' => [
              'path'  => 'user',
              'query' => [
                'match' => [
                  'user.id' => $userId
                ]
              ]
            ]
          ]
        ]
      ]
    ];

    if ($search) {
      $query['bool']['must'][] = ['query_string' => ['query' => $search]];
    }

    return ['query' => $query];
  }

  /**
   * @param int    $userId
   * @param string $search
   * @return array
   */
  public static function buildIncomeSearchQuery($userId, $search)
  {
    $query = [
      'bool' => [
        'must' => [
          [
            'nested' => [
              'path'  => 'user',
              'query' => [
                'match' => [
                  'user.id' => $userId
                ]
              ]
            ]
          ]
        ]
      ]
    ];

    if ($search) {
      $query['bool']['must'][] = ['query_string' => ['query' => $search]];
    }

    return ['query' => $query];
  }
}
