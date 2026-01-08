<?php

namespace BlueSpice\WhoIsOnline\Process;

use BlueSpice\Timestamp;
use DateTime;
use Exception;
use MWStake\MediaWiki\Component\ProcessManager\IProcessStep;
use Wikimedia\Rdbms\ILoadBalancer;

class DeleteOldEntries implements IProcessStep {

	/**
	 * @param ILoadBalancer $loadBalancer
	 */
	public function __construct( private readonly ILoadBalancer $loadBalancer ) {
	}

	/**
	 * @inheritDoc
	 * @throws Exception
	 */
	public function execute( $data = [] ): array {
		$oneHourAgo = Timestamp::getInstance(
			( new DateTime() )->modify( '-1 day' )
		);
		try {
			$this->loadBalancer->getConnection( DB_PRIMARY )->delete(
				'bs_whoisonline',
				[ "wo_log_ts < {$oneHourAgo->getTimestamp( TS_MW )}" ],
				__METHOD__
			);
		} catch ( Exception $e ) {
			return [ 'failed' => $e->getMessage() ];
		}

		return [ 'success' => true ];
	}
}
