<?php
namespace TYPO3SurfCms\SurfTools\Task;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "TYPO3SurfCms.SurfTools".*
 *                                                                        *
 *                                                                        */

use TYPO3\Surf\Domain\Model\Node;
use TYPO3\Surf\Domain\Model\Application;
use TYPO3\Surf\Domain\Model\Deployment;
use TYPO3\Flow\Annotations as Flow;
use TYPO3\Surf\Exception\InvalidConfigurationException;

/**
 * Clear TYPO3 caches
 * This task requires the extension typo3_console.
 */
class FlushCachesTask extends AbstractTypo3CliTask {

	/**
	 * Execute this task
	 *
	 * @param \TYPO3\Surf\Domain\Model\Node $node
	 * @param \TYPO3\Surf\Domain\Model\Application $application
	 * @param \TYPO3\Surf\Domain\Model\Deployment $deployment
	 * @param array $options
	 * @return void
	 */
	public function execute(Node $node, Application $application, Deployment $deployment, array $options = array()) {
		$this->executeCliCommand(
			$this->getSuitableCliArguments($node, $application, $deployment),
			$node,
			$application,
			$deployment,
			$options
		);
	}

	/**
	 * @param Node $node
	 * @param Application $application
	 * @param Deployment $deployment
	 * @return array
	 * @throws InvalidConfigurationException
	 */
	protected function getSuitableCliArguments(Node $node, Application $application, Deployment $deployment) {
		switch ($this->getAvailableCliPackage($node, $application, $deployment)) {
			case 'typo3_console':
				return array('./typo3cms', 'cache:flush', '--force');
			case 'coreapi':
				return array('typo3/cli_dispatch.phpsh', 'extbase', 'cacheapi:clearallcaches');
			default:
				throw new InvalidConfigurationException('No suitable arguments could be resolved', 1405527588);
		}
	}
}
