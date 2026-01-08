<?php

namespace BlueSpice\WhoIsOnline;

use BlueSpice\WhoIsOnline\Process\DeleteOldEntries;
use MediaWiki\MediaWikiServices;
use MWStake\MediaWiki\Component\ProcessManager\ManagedProcess;
use MWStake\MediaWiki\Component\WikiCron\WikiCronManager;

class DeleteOldEntriesCron {

	/**
	 * @return void
	 */
	public static function register(): void {
		if ( defined( 'MW_PHPUNIT_TEST' ) || defined( 'MW_QUIBBLE_CI' ) ) {
			return;
		}

		/** @var WikiCronManager $cronManager */
		$cronManager = MediaWikiServices::getInstance()->getService( 'MWStake.WikiCronManager' );

		// Interval: Daily at 01:00
		$cronManager->registerCron( 'bs-whoisonline-deleteoldentries', '0 1 * * *', new ManagedProcess( [
			'delete-old-entries' => [
				'class' => DeleteOldEntries::class,
				'services' => [
					'DBLoadBalancer',
				],
			]
		] ) );
	}
}
