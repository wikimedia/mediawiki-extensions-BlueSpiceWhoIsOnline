<?php

namespace BlueSpice\WhoIsOnline\Statistics\Report;

use BlueSpice\ExtendedStatistics\ClientReportHandler;
use BlueSpice\ExtendedStatistics\IReport;

class LoginDuration implements IReport {

	/**
	 * @inheritDoc
	 */
	public function getSnapshotKey() {
		return 'wo-loginduration';
	}

	/**
	 * @inheritDoc
	 */
	public function getClientData( $snapshots, array $filterData, $limit = 20 ): array {
		// This report is always aggregated
		$filterForUsers = $filterData['users'] ?? [];
		if ( empty( $filterForUsers ) ) {
			return [];
		}
		$processed = [];

		foreach ( $snapshots as $snapshot ) {
			$data = $snapshot->getData();
			$users = $data['users'];
			foreach ( $users as $name => $duration ) {
				if ( !in_array( $name, $filterForUsers ) ) {
					continue;
				}
				$processed[] = [
					'name' => $snapshot->getDate()->forGraph(),
					'line' => $name,
					'value' => $duration
				];
			}
		}

		return $processed;
	}

	/**
	 * @inheritDoc
	 */
	public function getClientReportHandler(): ClientReportHandler {
		return new ClientReportHandler(
			[ 'ext.bluespice.whoisonline.statistics' ],
			'bs.whoisonline.report.LoginDurationReport'
		);
	}
}
