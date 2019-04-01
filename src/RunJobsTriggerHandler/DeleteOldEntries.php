<?php

namespace BlueSpice\WhoIsOnline\RunJobsTriggerHandler;

use Exception;
use Status;
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

		$oneHourAgo = time() - ( 60 * 60 );
		try {
			$this->loadBalancer->getConnection( DB_MASTER )->delete(
				'bs_whoisonline',
				[ "wo_timestamp < $oneHourAgo" ],
				__METHOD__
			);
		} catch( Exception $e ) {
			$status->fatal( $e->getMessage() );
		}

		return $status;
	}

}
