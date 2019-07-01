<?php

namespace BlueSpice\WhoIsOnline\RunJobsTriggerHandler;

use Exception;
use Status;
use BlueSpice\Timestamp;
use DateTime;
use BlueSpice\RunJobsTriggerHandler;
use BlueSpice\RunJobsTriggerHandler\Interval;
use BlueSpice\RunJobsTriggerHandler\Interval\OnceEveryHour;

class DeleteOldEntries extends RunJobsTriggerHandler {

	/**
	 *
	 * @return Interval
	 */
	public function getInterval() {
		return new OnceEveryHour();
	}

	/**
	 * @return Status
	 */
	protected function doRun() {
		$status = Status::newGood();

		$oneHourAgo = Timestamp::getInstance(
			( new DateTime() )->modify( '-1 hour' )
		);
		try {
			$this->loadBalancer->getConnection( DB_MASTER )->delete(
				'bs_whoisonline',
				[ "wo_log_ts < {$oneHourAgo->getTimestamp( TS_MW )}" ],
				__METHOD__
			);
		} catch ( Exception $e ) {
			$status->fatal( $e->getMessage() );
		}

		return $status;
	}

}
