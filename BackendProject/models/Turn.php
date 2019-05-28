<?php

namespace eTorn\Models;

use eTorn\Constants\ConstantsFirebase;
use GuzzleHttp\Client;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class Turn
 * @package eTorn\Models
 * @property int $id
 * @property int $number
 * @property string $state
 * @property string $type
 * @property array $config
 * @property string $atended_at
 * @property string $ended_at
 */
class Turn extends Model
{
    protected $table = 'turns';

    public $timestamps = true;

    protected $fillable = [
        'id', 'number', 'state', 'type',
		'atended_at', 'ended_at', 'config'
    ];

    protected $casts = [
    	'config' => 'array'
	];

	/**
	 * @return BelongsTo
	 */
	public function bucket(): BelongsTo
    {
        return $this->belongsTo('eTorn\Models\Bucket', 'id_bucket');
    }

	/**
	 * @return BelongsTo
	 */
	public function till(): BelongsTo
    {
        return $this->belongsTo('eTorn\Models\till', 'id_till');
    }

    public function hasNumber(): bool
    {
        return ($this->number != null);
    }

    public function hasToken(): bool
	{
		return array_key_exists('token', $this->config);
	}

    public function notify($title, $body): bool
	{
		if (!$this->hasToken()) {
			return false;
		}

		$client = new Client();

		$response = $client->post(ConstantsFirebase::FCM_URI, [
			'headers' => [
				'authorization' => 'key=' . ConstantsFirebase::FCM_TOKEN,
				'content-type' => 'application/json'
			],
			'body' => [
				'to' => $this->config['token'],
				'notification' => [
					'title' => $title,
					'body' => $body
				]
			]
		]);

		$responseArray = json_decode($response->getBody()->getContents(), true);

		return (bool) $responseArray['success'];
	}
}