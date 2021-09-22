<?php

namespace BlueSpice\WhoIsOnline\Statistics\SnapshotProvider;

use BlueSpice\ExtendedStatistics\ISnapshotProvider;
use BlueSpice\ExtendedStatistics\Snapshot;
use BlueSpice\ExtendedStatistics\SnapshotDate;
use Wikimedia\Rdbms\LoadBalancer;

class LoginDuration implements ISnapshotProvider {
	/** @var LoadBalancer */
	private $loadBalancer;

	/**
	 * @param LoadBalancer $loadBalancer
	 */
	public function __construct( LoadBalancer $loadBalancer ) {
		$this->loadBalancer = $loadBalancer;
	}

	/**
	 * @param SnapshotDate $date
	 * @return Snapshot
	 */
	public function generateSnapshot( SnapshotDate $date ): Snapshot {
		$db = $this->loadBalancer->getConnection( DB_REPLICA );

		$ts = $date->mwDate();
		$res = $db->select(
			'bs_whoisonline',
			[ 'wo_user_name as user', 'wo_log_ts as ping' ],
			[
				"wo_log_ts LIKE " . $db->addQuotes( "$ts%" ),
			],
			__METHOD__,
			[
				'ORDER BY' => 'wo_user_name, wo_log_ts'
			]
		);

		$users = [];
		$lastTs = 0;
		$lastUser = null;
		foreach ( $res as $row ) {
			if ( !isset( $users[$row->user] ) ) {
				$users[$row->user] = 0;
			}
			if ( !$lastUser ) {
				$lastUser = $row->user;
			} elseif ( $lastUser !== $row->user ) {
				$lastTs = 0;
				$lastUser = $row->user;
			}
			$ts = \DateTime::createFromFormat( 'YmdHis', $row->ping );
			if ( $lastTs === 0 ) {
				$lastTs = $ts;
			} else {
				$originalTs = $ts;
				$diff = $ts->getTimestamp() - $lastTs->getTimestamp();
				// Inactive more than 5 mins
				if ( $diff > 300 ) {
					$lastTs = $originalTs;
					// Assume user was online for 10 more seconds
					$users[$row->user] += 10;
					continue;
				}
				$users[$row->user] += $diff;
			}
		}
		$total = 0;
		foreach ( $users as $name => $duration ) {
			$total += $duration;
		}
		return new Snapshot( $date, $this->getType(), [ 'total' => $total, 'users' => $users ] );
	}

	/**
	 * @inheritDoc
	 */
	public function aggregate(
		array $snapshots, $interval = Snapshot::INTERVAL_DAY, $date = null
	): Snapshot {
		$total = 0;
		$perUser = [];
		foreach ( $snapshots as $snapshot ) {
			$item = $snapshot->getData();
			$total += $item['total'];
			foreach ( $item['users'] as $username => $duration ) {
				if ( !isset( $perUser[$username] ) ) {
					$perUser[$username] = 0;
				}
				$perUser[$username] += $duration;
			}
		}

		return new Snapshot(
			$date ?? new SnapshotDate(), $this->getType(),
			[ 'total' => $total, 'users' => $perUser ], $interval
		);
	}

	/**
	 * @inheritDoc
	 */
	public function getType() {
		return 'wo-loginduration';
	}

	/**
	 * @inheritDoc
	 */
	public function getSecondaryData( Snapshot $snapshot ) {
		return null;
	}
}
